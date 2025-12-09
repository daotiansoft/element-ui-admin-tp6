<?php
namespace app\common\model;

class LoginLogModel extends CommonModel
{
    const STATUS_SUCC=1;
    const STATUS_FAIL=2;

    protected $name = 'login_log';

    public $fields=[
        'id',
        'uid',
        'device',
        'time',
        'status',
        'desc',
        'ip'
    ];

    public function log($uid,$device,$status,$ip,$desc=''){
        return $this->insert(array('uid'=>$uid,'device'=>$device,'time'=>time(),'status'=>$status,'desc'=>$desc,'ip'=>$ip));
    }

}