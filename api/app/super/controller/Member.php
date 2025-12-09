<?php
//下级
namespace app\super\controller;

use app\BaseController;
use app\common\model\BalanceLogModel;
use app\common\model\UserModel;
use app\common\validate\UserValidate;
use think\App;


class Member extends BaseController
{

    //验证登录
    protected $middleware = [\app\middleware\LoginAuth::class];

    public function items(){
        $model = new UserModel();

        $page = intval($this->request->param('page'));
        $limit = $this->request->param('pagesize',20,'intval');
        if($page <= 0){
            $page = 1;
        }
        $time=$this->request->param('time/a');

        $keyword = trim($this->request->param('keyword'));

        $where=[];
        if(!empty($keyword)){
            $where[]=['user_model.username|puser.username','like',"%$keyword%"];
        }
        if(is_array($time) && count($time) == 2){
            $where[]=['user_model.create_time','between',[$time[0]/1000,$time[1]/1000]];
        }
        $fields=['user_model.id,user_model.type,user_model.username,user_model.create_time,user_model.balance,user_model.vip_time,user_model.status'];


        $data=$model
            ->withJoin('puser','LEFT')
            ->where($where)->limit(($page - 1)*$limit,$limit)->field($fields)->order('user_model.id desc')->select();
        $count = $model
            ->withJoin('puser','LEFT')
            ->where($where)->count();
        $items=[];
        foreach($data as $item){
            $_item=[];
            $_item['id']=$item['id'];
            $_item['type']=$item['type'];
            $_item['username']=$item['username'];
            $_item['balance']=$item['balance'];
            $_item['create_time']=decode_time($item['create_time']);
            $_item['vip_time']=decode_time($item['vip_time']);
            $_item['status']=$item['status'];
            $_item['rate_balance_withdrawal']=$item['rate_balance_withdrawal'];

            if($item->puser && isset($item->puser['id'])){
                $_item['pusername'] = $item->puser['username'];
            }
            $items[]=$_item;
        }
        return $this->success("操作成功",array('items'=>$items,'count'=>$count));
    }

    public function add(){
        $model = new UserModel();
        $data=[];
        $data['type']=$this->request->param('type','user');
        $data['username']=$this->request->param('username');
        $data['password']=$this->request->param('password');
        $data['rate_balance_withdrawal']=round(doubleval($this->request->param('rate_balance_withdrawal')),2);
        $data['pid']=intval($this->request->param('pid'));

        if($data['pid'] > 0){
            $puser= $model->getItemById($data['pid']);
            if(!$puser){
                return $this->error("上级用户不存在");
            }
        }
        $ret = $model->where('username','=',$data['username'])->field('id')->find();
        if($ret){
            return $this->error("用户名已存在");
        }

        $data['rand_code'] = get_rand_str(4);
        $data['password']=get_password($data['password'],$data['rand_code']);
        $data['status']=$model::STATUS_ON;
        $data['create_time']=time();

        $req = $model->insert($data);
        if($req){
            return $this->success("操作成功");
        }else{
            return $this->error("操作失败");
        }
    }

    public function edit(){
        $id=intval($this->request->param('id'));
        $data['type']=$this->request->param('type','user');
        $password=$this->request->param('password');
        $pid=intval($this->request->param('pid'));
        $status=intval($this->request->param('status'));

        if($id <= 0){
            return $this->error("访问错误");
        }

        $data=[];
        if(!empty($password)){
            $validate = $this->uservalidate->scene('password')->check(['password'=>$password]);
            if(!$validate){
                return $this->error($this->uservalidate->getError());
            }
            $data['rand_code'] = get_rand_str(4);
            $data['password'] = get_password($password,$data['rand_code']);
        }
        $data['update_time']=time();
        $data['pid']=$pid;
        $data['rate_balance_withdrawal']=round(doubleval($this->request->param('rate_balance_withdrawal')),2);

        if($data['pid'] > 0){
            $puser= $this->usermodel->getItemById($data['pid']);
            if(!$puser){
                return $this->error("上级用户不存在");
            }
        }

        if($status == $this->usermodel::STATUS_OFF){
            $data['status'] = $this->usermodel::STATUS_OFF;
        }else{
            $data['status'] = $this->usermodel::STATUS_ON;
        }

        $req = $this->usermodel->where('id','=',$id)->update($data);
        if($req){
            return $this->success("操作成功");
        }else{
            return $this->error("操作失败");
        }
    }

    public function del(){
        $ids=$this->request->param('ids/a');

        if(count($ids) <= 0){
            return $this->error("至少删除一个");
        }
        $model = new UserModel();
        $req=$model->where('id','in',$ids)->delete();
        if($req){
            return $this->success("操作成功");
        }else{
            return $this->error("操作失败");
        }
    }


    public function balanceIn(){
        $uid= intval($this->request->param('id'));
        $desc = $this->request->param('desc');
        $money = $this->request->param('money');
        $money = abs(intval($money * 100) / 100);

        if($uid <= 0 || $money <= 0){
            return $this->error("参数错误");
        }

        $user = new UserModel();
        $balance = new BalanceLogModel();

        $user->startTrans();
        $user_item = $user->where('id','=',$uid)->lock(true)->field($user->fields)->find();
        if(!$user_item){
            $user->rollback();
            return $this->error("用户不存在");
        }

        $data=[];
        $data['balance'] = $user_item['balance'] + $money;
        $user->where('id','=',$uid)->update($data);

        $data=[];
        $data['type']=$balance::TYPE_RECHARGE;
        $data['user_id'] = $uid;
        $data['money'] = $money;
        $data['balance'] = $user_item['balance'] + $money;
        $data['desc'] = $desc;
        $data['create_time'] = time();

        $req=$balance->insert($data);
        if($req){
            $user->commit();
            return $this->success("操作成功");
        }else{
            $user->rollback();
            return $this->error("操作失败");
        }
    }
    public function balanceOut(){
        $uid= intval($this->request->param('id'));
        $desc = $this->request->param('desc');
        $money = $this->request->param('money');
        $money = abs(intval($money * 100) / 100);

        if($uid <= 0 || $money == 0){
            return $this->error("参数错误");
        }

        $user = new UserModel();
        $balance = new BalanceLogModel();

        $user->startTrans();
        $user_item = $user->where('id','=',$uid)->lock(true)->field($user->fields)->find();
        if(!$user_item){
            $user->rollback();
            return $this->error("用户不存在");
        }

        $data=[];
        $data['balance'] = $user_item['balance'] - $money;
        $user->where('id','=',$uid)->update($data);

        $data=[];
        $data['type']=$balance::TYPE_OUT;
        $data['user_id'] = $uid;
        $data['money'] = 0-$money;
        $data['balance'] = $user_item['balance'] - $money;
        $data['desc'] = $desc;
        $data['create_time'] = time();

        $req=$balance->insert($data);
        if($req){
            $user->commit();
            return $this->success("操作成功");
        }else{
            $user->rollback();
            return $this->error("操作失败");
        }
    }


}
