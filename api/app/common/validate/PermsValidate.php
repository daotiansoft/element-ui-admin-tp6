<?php
namespace app\common\validate;

use app\common\basics\Validate;

class PermsValidate extends Validate
{
    protected $rule =   [
        'type'  => 'require',
        'name'  => 'require',
        'action'  => 'require'
    ];

    protected $message  =   [
        'type.require' => '角色代码不能为空',
        'name.require' => '名称不能为空',
        'action.require' => '请求地址不能为空',
    ];
    protected $scene = [
        'add'  =>  ['type','name','action'],
        'edit'  =>  ['type','name','action'],
    ];

}