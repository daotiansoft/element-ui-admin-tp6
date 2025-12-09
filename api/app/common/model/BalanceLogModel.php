<?php
namespace app\common\model;

class BalanceLogModel extends CommonModel
{
    protected $name = 'balance_log';

    //状态
    const TYPE_IN='in';//收入
    const TYPE_OUT='out';//支出
    const TYPE_RECHARGE='recharge';//充值
    const TYPE_WITHDRAWAL='withdrawal';//提现
    const TYPE_WITHDRAWAL_FAIL='withdrawal_fail';//提现失败

    public $fields=[
        'id',
        'type',
        'user_id',
        'money',
        'balance',
        'desc',
        'create_time'
    ];
    public function user(){
        return $this->belongsTo(UserModel::class,'user_id','id');
    }
}
