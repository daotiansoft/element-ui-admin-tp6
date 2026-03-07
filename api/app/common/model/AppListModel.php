<?php
namespace app\common\model;

class AppListModel extends CommonModel
{
    protected $name = 'app_list';

    const STATUS_ON = 1;
    const STATUS_OFF = -1;

    public $fields=[
        'id',
        'appid',
        'secret',
        'status',
        'remark',
        'create_time',
        'update_time'
    ];
}
