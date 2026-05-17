<?php
//下级
namespace app\super\controller;

use app\common\basics\Auth;
use app\super\service\FormFieldService;
use app\common\utils\AjaxUtils;
use app\super\validate\FormFieldValidate;
use think\App;
use think\response\Json;


class FormField extends Auth
{
    public function items():Json
    {
        $list = FormFieldService::lists($this->request->param());
        return AjaxUtils::success($list);
    }

    public function add(){
        $data = $this->request->param();
        (new FormFieldValidate())->addCheck($data);
        FormFieldService::add($data);
        return AjaxUtils::success();
    }

    public function edit(){
        $data = $this->request->param();
        (new FormFieldValidate())->editCheck($data);
        FormFieldService::edit($data);
        return AjaxUtils::success();
    }

    public function del(){
        $ids = $this->request->param('ids/a');
        (new FormFieldValidate())->idsCheck($ids);
        FormFieldService::del($ids);
        return AjaxUtils::success($ids);
    }
}
