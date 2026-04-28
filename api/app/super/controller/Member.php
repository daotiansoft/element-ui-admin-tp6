<?php
//下级
namespace app\super\controller;

use app\common\basics\Auth;
use app\common\model\UserModel;
use app\super\service\UserService;
use app\common\utils\AjaxUtils;
use app\super\validate\UserValidate;
use think\App;
use think\response\Json;


class Member extends Auth
{
    public function items():Json
    {
        $list = UserService::lists($this->request->param());
        return AjaxUtils::success($list);
    }

    public function add(){
        $data = $this->request->param();
        if(isset($data['username']) && !empty($data['username'])){
            $userItem = UserService::getItemByUsername($data['username']);
            if($userItem){
                return AjaxUtils::error("账号已存在");
            }
        }
        (new UserValidate())->addCheck($data);

        $data['rand_code'] = get_rand_str(4);
        $data['password']=get_password($data['password'],$data['rand_code']);
        UserService::add($data);
        return AjaxUtils::success();
    }

    public function edit(){
        $data = $this->request->param();
        if(!empty($data['password'])){
            $data['rand_code'] = get_rand_str(4);
            $data['password']=get_password($data['password'],$data['rand_code']);
        }else{
            unset($data['password']);
        }
        (new UserValidate())->goCheck('edit',$data);

        UserService::edit($data);
        return AjaxUtils::success();
    }

    public function del(){
        $ids = $this->request->param('ids/a');
        (new UserValidate())->idsCheck($ids);
        UserService::del($ids);
        return AjaxUtils::success($ids);
    }
}
