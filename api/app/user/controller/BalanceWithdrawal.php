<?php
namespace app\user\controller;

use app\BaseController;
use app\common\model\BalanceWithdrawalModel;
use think\App;


class BalanceWithdrawal extends BaseController
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

        $model = new BalanceWithdrawalModel();
        $where=[];
        $where[]=['user_id','=',$user['id']];
        if(!empty($keyword)){
            $where[]=['order_id','like',"%$keyword%"];
        }
        if(is_array($time) && count($time) == 2){
            $where[]=['create_time','between',[$time[0]/1000,$time[1]/1000]];
        }

        $data=$model
            ->where($where)->limit(($page - 1)*$limit,$limit)->field($model->fields)->order('id desc')->select();
        $count = $model
            ->where($where)->count();
        $items=[];
        foreach($data as $item){
            $_item=[];
            $_item['order_id']=$item['order_id'];
            $_item['money']=$item['money'];
            $_item['pay']=$item['pay'];
            $_item['rate']=$item['rate'];
            $_item['account']=$item['account'];
            $_item['account_name']=$item['account_name'];
            $_item['status']=$item['status'];
            $_item['desc']=$item['desc'];
            $_item['create_time']=decode_time($item['create_time']);
            $_item['auth_time']=decode_time($item['auth_time']);
            $items[]=$_item;
        }
        return $this->success("操作成功",array('items'=>$items,'count'=>$count));
    }

    public function add(){
        $user= $this->request->userData;

        $model = new BalanceWithdrawalModel();

        $data=[];
        $data['user_id'] = $user['id'];
        $data['money'] = round(doubleval($this->request->param('money')),2);
        $data['account'] = trim($this->request->param('account'));
        $data['account_name'] = trim($this->request->param('account_name'));

        $model->startTrans();
        $ret = $model->add($data);
        if(!$ret){
            $model->rollback();
            return $this->error($model->msg);
        }
        $model->commit();
        return $this->success("提交成功，请等待审核！");
    }
}
