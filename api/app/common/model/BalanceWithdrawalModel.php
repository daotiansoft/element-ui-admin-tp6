<?php
namespace app\common\model;

class BalanceWithdrawalModel extends CommonModel
{
    protected $name = 'balance_withdrawal';

    const STATUS_ING=1;//审核中
    const STATUS_SUCC=2;//成功
    const STATUS_FAIL=-2;//失败

    public $fields=[
        'id',
        'order_id',
        'user_id',
        'money',
        'pay',
        'rate',
        'account',
        'account_name',
        'status',
        'desc',
        'create_time',
        'auth_time',
    ];
    public function user(){
        return $this->belongsTo(UserModel::class,'user_id','id');
    }

    /**
     *  申请提现 需要回滚
     * @param array $params ['user_id'=>0,'money'=>100,'account'=>'','account_name'=>'']
     * @return bool
     */
    public function add($params = array()){
        $userModel = new UserModel();
        $balanceLog = new BalanceLogModel();

        if(!isset($params['order_id'])){
            $params['order_id'] = create_order_id();
        }
        if(!isset($params['user_id']) || $params['user_id'] <= 0){
            $this->msg='缺少user_id';
            return false;
        }
        if(!isset($params['money']) || $params['money'] <= 0){
            $this->msg='提现金额错误';
            return false;
        }

        if(!isset($params['account']) || empty($params['account'])){
            $this->msg='收款账号错误';
            return false;
        }
        if(!isset($params['account_name']) || empty($params['account_name'])){
            $this->msg='收款账号姓名错误';
            return false;
        }

        if(isset($params['pay'])){
           $params['rate'] = $params['money'] - $params['pay'];
        }else{
            $userItem = $userModel->getItemById($params['user_id']);
            if(!$userItem){
                $this->msg='账号不存在';
                return false;
            }
            $params['rate'] = round($params['money'] * $userItem['rate_balance_withdrawal'],2);
            $params['pay'] = $params['money'] - $params['rate'];
        }

        $params['status'] = self::STATUS_ING;
        $params['create_time'] = time();
        $params['auth_time'] = 0;

        $ret = $userModel->changeBalance($params['user_id'],0-$params['money'],$balanceLog::TYPE_WITHDRAWAL,$params['order_id']);
        if(!$ret){
            $this->msg=$userModel->msg;
            return false;
        }
        $req = $this->insert($params);
        if(!$req){
            $this->msg='提现失败';
            return false;
        }
        return true;
    }

    /*
     * 需要回滚
     * 修改状态
     */
    public function changeStatus($order_id,$status,$desc=''){
        if(empty($order_id)){
            $this->msg='缺少订单号';
            return false;
        }
        if(!in_array($status,[self::STATUS_SUCC,self::STATUS_FAIL])){
            $this->msg='状态错误';
            return false;
        }

        $item = $this->where('order_id','=',$order_id)->lock(true)->field($this->fields)->find();
        if(!$item){
            $this->msg='交易不存在';
            return false;
        }
        if($item['status'] != self::STATUS_ING){
            $this->msg='当前状态不允许操作';
            return false;
        }

        if($status == self::STATUS_FAIL){
            //退还余额
            $userModel = new UserModel();
            $balanceLog = new BalanceLogModel();
            $userModel->changeBalance($item['user_id'],$item['money'],$balanceLog::TYPE_WITHDRAWAL_FAIL,$item['order_id']);
        }
        $this->where('order_id','=',$order_id)->update(['status'=>$status,'desc'=>$desc,'auth_time'=>time()]);
        return true;
    }

}
