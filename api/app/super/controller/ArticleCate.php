<?php
//下级
namespace app\super\controller;

use app\BaseController;
use app\common\model\ActivationCateModel;
use app\common\model\ActivationCodeModel;
use app\common\model\ArticleCateModel;
use think\App;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;


class ArticleCate extends BaseController
{

    //验证登录
    protected $middleware = [\app\middleware\LoginAuth::class];

    public function items(){
        $page = intval($this->request->param('page'));
        $limit = $this->request->param('pagesize',20,'intval');
        if($page <= 0){
            $page = 1;
        }
        $time=$this->request->param('time/a');

        $keyword = trim($this->request->param('keyword'));

        $where=[];
        if(!empty($keyword)){
            $where[]=['name|desc','like',"%$keyword%"];
        }
        if(is_array($time) && count($time) == 2){
            $where[]=['create_time','between',[$time[0]/1000,$time[1]/1000]];
        }

        $model = new ArticleCateModel();

        $data=$model
            ->where($where)->limit(($page - 1)*$limit,$limit)->field($model->fields)->order('id desc')->select();
        $count = $model
            ->where($where)->count();
        $items=[];
        foreach($data as $item){
            $_item=[];
            $_item['id']=$item['id'];
            $_item['name']=$item['name'];
            $_item['desc']=$item['desc'];
            $_item['status']=$item['status'];
            $_item['create_time']=decode_time($item['create_time']);
            $_item['update_time']=decode_time($item['update_time']);
            $items[]=$_item;
        }
        return $this->success("操作成功",array('items'=>$items,'count'=>$count));
    }

    public function add(){
        $name=trim($this->request->param('name'));
        $desc=trim($this->request->param('desc'));
        $status=intval($this->request->param('status'));

        if(empty($name)){
            return $this->error("请输入名称");
        }
        $model = new ArticleCateModel();

        $item=[];
        $item['name']=$name;
        $item['desc']=$desc;
        $item['status']=$status;

        $req=$model->insert_data($item);
        if($req === true){
            return $this->success("操作成功");
        }
        if($req !== false){
            return $this->error($req);
        }
        return $this->error("操作失败");
    }
    public function edit(){
        $id=intval($this->request->param('id'));
        $name=trim($this->request->param('name'));
        $desc=trim($this->request->param('desc'));
        $status=intval($this->request->param('status'));

        if(empty($name)){
            return $this->error("请输入名称");
        }
        if($id <= 0){
            return $this->error("参数错误");
        }
        $model = new ArticleCateModel();

        $item=[];
        $item['id']=$id;
        $item['name']=$name;
        $item['desc']=$desc;
        $item['status']=$status;

        $req=$model->update_data($item);
        if($req === true){
            return $this->success("操作成功");
        }
        if($req !== false){
            return $this->error($req);
        }
        return $this->error("操作失败");
    }

    public function del(){
        $ids=$this->request->param('ids/a');

        if(count($ids) <= 0){
            return $this->error("至少删除一个");
        }
        $model = new ArticleCateModel();
        $req=$model->where('id','in',$ids)->delete();
        if($req){
            return $this->success("操作成功");
        }else{
            return $this->error("操作失败");
        }
    }

}
