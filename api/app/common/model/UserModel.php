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
        'create_time',
        'update_time',
        'status'
    ];

    public function getItemByUsername($username){
        return $this->where('username','=',$username)->field($this->fields)->find();
    }

}
