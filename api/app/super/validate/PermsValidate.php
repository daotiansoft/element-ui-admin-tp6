<?php
namespace app\super\validate;

use app\common\basics\Validate;

class PermsValidate extends Validate
{
    protected $rule =   [
        'type'  => 'require',
        'name'  => 'require',
        'module'  => 'require',
        'controller'  => 'require',
        'action'  => 'require'
    ];

    protected $message  =   [
        'type.require' => '角色代码不能为空',
        'name.require' => '名称不能为空',
        'module.require' => '模块不能为空',
        'controller.require' => '控制器不能为空',
        'action.require' => '方法名不能为空',
    ];
    protected $scene = [
        'add'  =>  ['type','name','module','controller','action'],
        'edit'  =>  ['type','name','module','controller','action'],
    ];

}