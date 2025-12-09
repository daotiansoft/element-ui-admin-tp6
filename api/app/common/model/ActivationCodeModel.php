<?php
namespace app\common\model;

class ActivationCodeModel extends CommonModel
{
    protected $name = 'activation_code';

    public $fields=[
        'id',
        'cate_id',
        'code',
        'time',
        'amount',
        'desc',
        'create_time',
        'active_time',
        'active_uid'
    ];
    public function getItemByCode($code){
        return $this->where('code','=',$code)->field($this->fields)->find();
    }

    public function user(){
        return $this->belongsTo(UserModel::class,'active_uid','id');
    }
    public function cate(){
        return $this->belongsTo(ActivationCateModel::class,'cate_id','id');
    }

}
