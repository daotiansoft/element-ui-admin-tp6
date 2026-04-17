<?php
//下级
namespace app\super\controller;

use app\common\basics\Auth;
use app\common\service\PermsService;
use app\common\service\RolesService;
use app\common\utils\AjaxUtils;
use app\common\validate\PermsValidate;
use think\App;
use think\response\Json;


class Perms extends Auth
{
    public function items():Json
    {
        $list = PermsService::lists($this->request->get());
        return AjaxUtils::success($list);
    }

    public function types():Json
    {
        $list = RolesService::all();
        return AjaxUtils::success($list);
    }

    public function add(){
        $data = $this->request->param();
        (new PermsValidate())->addCheck($data);
        PermsService::add($data);
        return AjaxUtils::success();
    }

    public function edit(){
        $data = $this->request->param();
        (new PermsValidate())->editCheck($data);
        PermsService::edit($data);
        return AjaxUtils::success();
    }

    public function del(){
        $ids = $this->request->param('ids/a');
        (new PermsValidate())->idsCheck($ids);
        PermsService::del($ids);
        return AjaxUtils::success($ids);
    }
}
