<?php
namespace app\common\model;

class UserModel extends CommonModel
{
    protected $name = 'user';

    //账号类型
    const TYPE_ADMIN = 'admin';
    const TYPE_USER = 'user';
    //状态
    const STATUS_ON=1;
    const STATUS_OFF=-1;

    public $fields=[
        'id',
        'type',
        'username',
        'password',
        'rand_code',
        'balance',
        'create_time',
        'update_time',
        'vip_time',
        'status',
        'pid',
        'activation_data',
        'rate_balance_withdrawal',
    ];

    public function getItemByUsername($username){
        return $this->where('username','=',$username)->field($this->fields)->find();
    }
    public function puser(){
        return $this->belongsTo(UserModel::class,'pid','id');
    }
    public function changeBalance($user_id,$money,$type,$desc){
        $user_item = $this->where('id','=',$user_id)->lock(true)->field($this->fields)->find();
        if(!$user_item){
            $this->msg = '用户不存在';
            return false;
        }
        if($user_item['balance'] + $money < 0){
            $this->msg = '余额不足';
            return false;
        }
        $data=[];
        $data['balance'] = $user_item['balance'] + $money;
        $this->where('id','=',$user_id)->update($data);

        $data=[];
        $data['type']=$type;
        $data['user_id'] = $user_id;
        $data['money'] = $money;
        $data['balance'] = $user_item['balance'] + $money;
        $data['desc'] = $desc;
        $data['create_time'] = time();

        $balanceLog = new BalanceLogModel();
        $req=$balanceLog->insert($data);
        if($req){
            return true;
        }else{
            $this->msg = '操作失败';
            return false;
        }
    }

}
