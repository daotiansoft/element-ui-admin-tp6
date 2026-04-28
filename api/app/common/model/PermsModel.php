<?php
namespace app\common\model;

use think\model\relation\HasOne;

class PermsModel extends CommonModel
{
    protected $name = 'perms';

    const STATUS_ON = 1;
    const STATUS_OFF = -1;

    protected $schema = [
        'id'=>'int',
        'name'=>'string',
        'type'=>'string',
        'module'=>'string',
        'controller'=>'string',
        'action'=>'string',
        'status'=>'int',
    ];

    public function role(): HasOne
    {
        return $this->hasOne(RolesModel::class, 'type', 'type');
    }
}
