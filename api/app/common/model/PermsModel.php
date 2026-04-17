<?php
namespace app\common\model;

use think\model\relation\HasOne;

class PermsModel extends CommonModel
{
    protected $name = 'perms';

    public $fields=[
        'id',
        'name',
        'type',
        'action',
        'status'
    ];

    public function role(): HasOne
    {
        return $this->hasOne(RolesModel::class, 'type', 'type');
    }
}
