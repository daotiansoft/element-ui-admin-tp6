<?php
namespace app\super\validate;


use app\common\basics\Validate;

class UserValidate extends Validate
{
    protected $rule =   [
        'type'  => 'require',
        'username'  => 'require',
        'password'  => 'require|min:6'
    ];

    protected $message  =   [
        'type.require' => '账号角色不能为空',
        'username.require' => '账号名称不能为空',
        'password.require' => '密码不能为空',
        'password.min'     => '密码长度不可小于6个字符'
    ];
    protected $scene = [
        'add'  =>  ['type','username','password'],
        'edit'  =>  ['type','username'],
        'password'  =>  ['password'],
        'login'  =>  ['username','password'],
    ];

}