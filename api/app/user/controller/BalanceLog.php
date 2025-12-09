<?php
//下级
namespace app\user\controller;

use app\BaseController;
use app\common\model\BalanceLogModel;
use app\common\model\UserModel;
use think\App;


class BalanceLog extends BaseController
{
    //验证登录
    protected $middleware = [\app\middleware\LoginAuth::class];

    public function items(){
        $user= $this->request->userData;
        $page = intval($this->request->param('page'));
        $limit = intval($this->request->param('pagesize'),20);
        if($page <= 0){
            $page = 1;
        }
        $time=$this->request->param('time/a');

        $keyword = trim($this->request->param('keyword'));

        $balance = new BalanceLogModel();
        $where=[];
        $where[]=['user_id','=',$user['id']];
        if(!empty($keyword)){
            $where[]=['desc','like',"%$keyword%"];
        }
        if(is_array($time) && count($time) == 2){
            $where[]=['create_time','between',[$time[0]/1000,$time[1]/1000]];
        }

        $data=$balance
            ->where($where)->limit(($page - 1)*$limit,$limit)->field($balance->fields)->order('id desc')->select();
        $count = $balance
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
            $items[]=$_item;
        }
        return $this->success("操作成功",array('items'=>$items,'count'=>$count));
    }


}
