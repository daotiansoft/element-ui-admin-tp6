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
            $where[]=['username','like',"%$keyword%"];
        }
        if(is_array($time) && count($time) == 2){
            $where[]=['create_time','between',[$time[0]/1000,$time[1]/1000]];
        }
        $fields=$model->getFields();


        $data=$model
            ->where($where)->limit(($page - 1)*$limit,$limit)->field($fields)->order('id desc')->select();
        $count = $model
            ->where($where)->count();
        $items=[];
        foreach($data as $item){
            $_item=[];
            $_item['id']=$item['id'];
            $_item['type']=$item['type'];
            $_item['username']=$item['username'];
            $_item['create_time']=decode_time($item['create_time']);
            $_item['status']=$item['status'];
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
        $model = new UserModel();

        $data=[];
        $data['id'] = $this->request->param('id',0,'intval');
        $data['type']=$this->request->param('type','user','trim');
        $data['password']=$this->request->param('password','','trim');
        $data['status'] = $this->request->param('status',0,'intval');

        if($data['id'] <= 0){
            return $this->error("访问错误");
        }
        if(empty($data['password'])){
            unset($data['password']);
        }
        $data['update_time']=time();
        if($data['status'] == UserModel::STATUS_OFF){
            $data['status'] = UserModel::STATUS_OFF;
        }else{
            $data['status'] = UserModel::STATUS_ON;
        }
        $req = $model->where('id','=',$data['id'])->update($data);
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
}
