<?php
namespace app\common\validate;

use app\common\basics\Validate;

class RolesValidate extends Validate
{
    protected $rule =   [
        'type'  => 'require|max:10',
        'name'  => 'require'
    ];

    protected $message  =   [
        'type.require' => '角色代码不能为空',
        'name.require' => '角色名称不能为空',
        'type.min'     => '角色代码长度不可大于10个字符'
    ];
    protected $scene = [
        'add'  =>  ['type','name'],
    ];

}