<?php
//下级
namespace app\super\controller;

use app\BaseController;
use app\common\model\ActivationCateModel;
use app\common\model\ActivationCodeModel;
use app\common\model\BalanceLogModel;
use app\common\model\UserModel;
use think\App;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;


class BalanceLog extends BaseController
{
    //验证登录
    protected $middleware = [\app\middleware\LoginAuth::class];

    public function items(){
        $page = intval($this->request->param('page'));
        $limit = intval($this->request->param('pagesize'),20);
        if($page <= 0){
            $page = 1;
        }
        $time=$this->request->param('time/a');

        $keyword = trim($this->request->param('keyword'));

        $balance = new BalanceLogModel();
        $where=[];
        if(!empty($keyword)){
            $where[]=['user.username|balance_log_model.desc','like',"%$keyword%"];
        }
        if(is_array($time) && count($time) == 2){
            $where[]=['balance_log_model.create_time','between',[$time[0]/1000,$time[1]/1000]];
        }
        $fields=$balance->getFields('balance_log_model.');
        $fields[]='user.username';

        $data=$balance
            ->withJoin(['user'],'LEFT')
            ->where($where)->limit(($page - 1)*$limit,$limit)->field($fields)->order('balance_log_model.id desc')->select();
        $count = $balance
            ->withJoin(['user'],'LEFT')
            ->where($where)->count();
        $items=[];
        foreach($data as $item){
            $_item=[];
            $_item['id']=$item['id'];
            $_item['type']=$item['type'];
            $_item['user_id']=$item['user_id'];
            $_item['money']=$item['money'];
            $_item['balance']=$item['balance'];
            $_item['desc']=$item['desc'];
            $_item['create_time']=decode_time($item['create_time']);

            $_item['username']=$item->user?$item->user->username:'';
            $items[]=$_item;
        }
        return $this->success("操作成功",array('items'=>$items,'count'=>$count));
    }
    public function del(){
        $ids=$this->request->param('ids/a');

        if(count($ids) <= 0){
            return $this->error("至少删除一个");
        }
        $model = new BalanceLogModel();
        $req=$model->where('id','in',$ids)->delete();
        if($req){
            return $this->success("操作成功");
        }else{
            return $this->error("操作失败");
        }
    }
}
