<?php
//下级
namespace app\super\controller;

use app\common\basics\Auth;
use app\common\model\AppListModel;
use app\common\model\UserModel;
use app\common\service\AppListService;
use app\common\service\UserService;
use app\common\utils\AjaxUtils;
use app\super\validate\AppListValidate;
use think\App;
use think\response\Json;


class AppList extends Auth
{
    public function items():Json
    {
        $list = AppListService::lists($this->request->get());
        return AjaxUtils::success($list);
    }

    public function add(){
        $data = $this->request->param();
        if(isset($data['username']) && !empty($data['username'])){
            $userItem = UserService::getItemByUsername($data['username']);
            if(!$userItem){
                return AjaxUtils::error("账号不存在");
            }
            $data['uid'] = $userItem['id'];
        }
        (new AppListValidate())->addCheck($data);
        AppListService::add($data);
        return AjaxUtils::success();
    }

    public function edit(){
        $data = $this->request->param();
        if(isset($data['username']) && !empty($data['username'])){
            $userItem = UserService::getItemByUsername($data['username']);
            if(!$userItem){
                return AjaxUtils::error("账号不存在");
            }
            $data['uid'] = $userItem['id'];
        }
        (new AppListValidate())->editCheck($data);
        AppListService::edit($data);
        return AjaxUtils::success();
    }

    public function del(){
        $ids = $this->request->param('ids/a');
        (new AppListValidate())->idsCheck($ids);
        AppListService::del($ids);
        return AjaxUtils::success($ids);
    }
}
