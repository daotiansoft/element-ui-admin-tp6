<?php
namespace app\common\model;

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
}
