<?php
namespace app\common\model;

class TokenModel extends CommonModel
{
    protected $name = 'token';

    const DEVICE_API='api';

    public $fields=[
        'id',
        'uid',
        'token',
        'device',
        'create_time',
        'expire_time'
    ];

    public function login($uid,$device){
        $where=[];
        $where[]=['uid','=',$uid];
        $where[]=['device','=',$device];
        $item=$this->where($where)->field($this->fields)->find();
        if(!$item){
            $item=[];
            $item['uid']=$uid;
            $item['token']=md5(get_rand_str(30).$uid);
            $item['device'] = $device;
            $item['create_time']=time();
            $item['expire_time']=time()+60*60*24*7;
            $item['id']=$this->insertGetId($item);
            if($item['id']<=0){
                return false;
            }
        }else{
            $item['token']=md5(get_rand_str(30).$uid);
            $item['expire_time']=time()+60*60*24*7;
            $this->where('id','=',$item['id'])->save(array('token'=>$item['token'],'expire_time'=>$item['expire_time']));
        }
        return $item;
    }

    public function logout($uid,$device){
        $where=[];
        $where[]=['uid','=',$uid];
        $where[]=['device','=',$device];
        $item=$this->where($where)->update(array('expire_time'=>time()));
    }

    public function getItemByToken($token){
        return $this->where('token','=',$token)->field($this->fields)->find();
    }
}
