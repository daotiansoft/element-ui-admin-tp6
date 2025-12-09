<?php
namespace app\common\model;

class ActivationCateModel extends CommonModel
{
    protected $name = 'activation_cate';

    public $fields=[
        'id',
        'name',
        'desc',
        'create_time',
        'update_time'
    ];
}
