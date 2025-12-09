<?php
namespace app\common\validate;

use think\Validate;

class UserValidate extends Validate
{
    protected $rule =   [
        'username'  => 'require',
        'password'  => 'require|min:6'
    ];

    protected $message  =   [
        'username.require' => '账号名称不能为空',
        'password.require' => '密码不能为空',
        'password.min'     => '密码长度不可小于6个字符'
    ];
    protected $scene = [
        'add'  =>  ['username','password'],
        'password'  =>  ['password'],
        'login'  =>  ['username','password'],
    ];

}