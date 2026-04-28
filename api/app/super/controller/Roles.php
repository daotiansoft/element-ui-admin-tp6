<?php
//下级
namespace app\super\controller;

use app\common\basics\Auth;
use app\super\service\RolesService;
use app\common\utils\AjaxUtils;
use app\super\validate\RolesValidate;
use think\App;
use think\response\Json;


class Roles extends Auth
{
    public function items():Json
    {
        $list = RolesService::lists($this->request->param());
        return AjaxUtils::success($list);
    }

    public function all():Json
    {
        $list = RolesService::all();
        return AjaxUtils::success($list);
    }

    public function add(){
        $data = $this->request->param();
        (new RolesValidate())->addCheck($data);
        RolesService::add($data);
        return AjaxUtils::success();
    }

    public function edit(){
        $data = $this->request->param();
        (new RolesValidate())->editCheck($data);
        RolesService::edit($data);
        return AjaxUtils::success();
    }

    public function del(){
        $ids = $this->request->param('ids/a');
        (new RolesValidate())->idsCheck($ids);
        RolesService::del($ids);
        return AjaxUtils::success($ids);
    }
}
