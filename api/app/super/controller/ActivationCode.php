<?php
//下级
namespace app\super\controller;

use app\BaseController;
use app\common\model\ActivationCateModel;
use app\common\model\ActivationCodeModel;
use think\App;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;


class ActivationCode extends BaseController
{

    //验证登录
    protected $middleware = [\app\middleware\LoginAuth::class];

    public function items(){
        $page = intval($this->request->param('page'));
        $limit = intval($this->request->param('pagesize'));
        $cate_id = intval($this->request->param('cate_id'));
        if($page <= 0){
            $page = 1;
        }
        if($limit <=0 || $limit > 100){
            $limit = 20;
        }


        $time=$this->request->param('time/a');

        $keyword = trim($this->request->param('keyword'));

        $where=[];
        if(!empty($keyword)){
            $where[]=['activation_code_model.code|user.username|activation_code_model.desc','like',"%$keyword%"];
        }
        if(is_array($time) && count($time) == 2){
            $where[]=['activation_code_model.create_time','between',[$time[0]/1000,$time[1]/1000]];
        }
        if($cate_id > 0){
            $where[]=['activation_code_model.cate_id','=',$cate_id];
        }
        $fields=['activation_code_model.id,activation_code_model.code,activation_code_model.time,activation_code_model.amount,activation_code_model.desc,activation_code_model.active_time,activation_code_model.active_uid'];

        $activation_code = new ActivationCodeModel();

        $data=$activation_code
            ->withJoin(['user','cate'],'LEFT')
            ->where($where)->limit(($page - 1)*$limit,$limit)->field($fields)->order('activation_code_model.id desc')->select();
        $count = $activation_code
            ->withJoin(['user','cate'],'LEFT')
            ->where($where)->count();
        $items=[];
        foreach($data as $item){
            $_item=[];
            $_item['id']=$item['id'];
            $_item['code']=$item['code'];
            $_item['time']=$item['time'];
            $_item['amount']=$item['amount'];
            $_item['desc']=$item['desc'];
            $_item['create_time']=decode_time($item['create_time']);
            $_item['active_time']=decode_time($item['active_time']);

            if($item->user && isset($item->user['id'])){
                $_item['username'] = $item->user['username'];
            }
            if($item->cate && isset($item->cate['name'])){
                $_item['cate_name'] = $item->cate['name'];
            }
            $items[]=$_item;
        }
        return $this->success("操作成功",array('items'=>$items,'count'=>$count));
    }

    public function add(){
        $cate_id=intval($this->request->param('cate_id'));
        $time=intval($this->request->param('time'));
        $amount=intval($this->request->param('amount'));
        $count=intval($this->request->param('count'));
        $desc=trim($this->request->param('desc'));

        if($cate_id <= 0){
            return $this->error("请选择分类");
        }
        if($time <= 0 && $amount <= 0){
            return $this->error("时长和次数至少选择一个");
        }
        if($count <= 0){
            return $this->error("至少生成一个");
        }
        $activation_code = new ActivationCodeModel();
        $activation_cate =new ActivationCateModel();
        $cate_item = $activation_cate->getItemById($cate_id);
        if(!$cate_item){
            return $this->error("分类不存在");
        }

        $items=[];
        $this_time=time();
        for($i=0;$i<$count;$i++){
            $code=md5($this_time.get_rand_str(12).$i);
            $item=[];
            $item['cate_id']=$cate_id;
            $item['code']=$code;
            $item['amount']=$amount;
            $item['time']=$time;
            $item['desc']=$desc;
            $item['create_time']=$this_time;

            $items[]=$item;
        }

        $req=$activation_code->insertAll($items);
        if($req){
            return $this->success("成功生成：".count($items));
        }else{
            return $this->error("生成失败");
        }
    }

    public function del(){
        $ids=$this->request->param('ids/a');

        if(count($ids) <= 0){
            return $this->error("至少删除一个");
        }
        $activation_code = new ActivationCodeModel();
        $req=$activation_code->where('id','in',$ids)->delete();
        if($req){
            return $this->success("操作成功");
        }else{
            return $this->error("操作失败");
        }
    }

    public function export(){
        $time=$this->request->param('time/a');

        $keyword = trim($this->request->param('keyword'));

        $where=[];
        if(!empty($keyword)){
            $where[]=['activation_code_model.code|user.username|activation_code_model.desc','like',"%$keyword%"];
        }
        if(is_array($time) && count($time) == 2){
            $where[]=['activation_code_model.create_time','between',[$time[0]/1000,$time[1]/1000]];
        }
        $fields=['activation_code_model.id,activation_code_model.code,activation_code_model.time,activation_code_model.amount,activation_code_model.desc,activation_code_model.active_time,activation_code_model.active_uid'];

        $activation_code = new ActivationCodeModel();

        $data=$activation_code
            ->withJoin('user','LEFT')
            ->where($where)->limit(1000)->field($fields)->order('activation_code_model.id desc')->select();

        $newExcel = new Spreadsheet();  //创建一个新的excel文档
        $objSheet = $newExcel->getActiveSheet();  //获取当前操作sheet的对象
        $objSheet->setTitle('激活码');  //设置当前sheet的标题

        //设置宽度为true,不然太窄了
        $newExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $newExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $newExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $newExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $newExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $newExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $newExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);

        //设置第一栏的标题
        $objSheet->setCellValue('A1', '激活码')
            ->setCellValue('B1', '时长')
            ->setCellValue('C1', '次数')
            ->setCellValue('D1', '备注')
            ->setCellValue('E1', '生成时间')
            ->setCellValue('F1', '激活时间')
            ->setCellValue('G1', '激活会员');

        //第二行起，每一行的值,setCellValueExplicit是用来导出文本格式的。
        //->setCellValueExplicit('C' . $k, $val['admin_password']PHPExcel_Cell_DataType::TYPE_STRING),可以用来导出数字不变格式
        foreach ($data as $k => $item) {
            $k = $k + 2;

            $username='';
            if($item->user && isset($item->user['id'])){
                $username = $item->user['username'];
            }

            $objSheet->setCellValue('A' . $k, $item['code'])
                ->setCellValue('B' . $k, $item['time'])
                ->setCellValue('C' . $k, $item['amount'])
                ->setCellValue('D' . $k, $item['desc'])
                ->setCellValue('E' . $k, decode_time($item['create_time']))
                ->setCellValue('F' . $k,  decode_time($item['active_time']))
                ->setCellValue('G' . $k,  $username);

        }

        $path = $this->app->getRootPath().'public/export';
        if(!is_dir($path)){
            mkdir(iconv("UTF-8", "GBK", $path),0777,true);
        }
        $file_name = date('YmdHis').'-'.get_rand_str(32).'.Xlsx';

        $objWriter = IOFactory::createWriter($newExcel, 'Xlsx');
        $objWriter->save($path.'/'.$file_name);
        return $this->success('导出成功',['url'=>$this->request->domain().'/export/'.$file_name]);

    }

}
