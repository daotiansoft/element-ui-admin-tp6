<?php
namespace app\common\model;

use think\model\relation\HasOne;

class RolesModel extends CommonModel
{
    protected $name = 'roles';

    const STATUS_ON = 1;
    const STATUS_OFF = -1;

    protected $schema = [
        'id'=>'int',
        'type'=>'string',
        'name'=>'string',
        'status'=>'int',
        'remark'=>'string',
        'create_time'=>'int',
        'update_time'=>'int'
    ];
}
