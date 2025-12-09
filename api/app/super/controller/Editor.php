<?php
//下级
namespace app\super\controller;

use app\BaseController;
use app\common\model\EditorModel;
use app\common\model\UserModel;
use think\App;
use think\exception\ValidateException;


class Editor extends BaseController
{

    //验证登录
    protected $middleware = [\app\middleware\LoginAuth::class];

    public function items(){
        $model = new EditorModel();
        $data = $model->field($model->fields)->select();
        $items = [];
        foreach($data as $item){
            $item['content'] = html_domain_decode($item['content']);
            $items[]=$item;
        }
        return $this->success("获取成功",$items);
    }

    public function save(){
        $params = $this->request->param();

        $model = new EditorModel();

        foreach($params as $item){
            if(isset($item['key']) && !empty($item['key'])){
                $item['content'] = html_domain_encode($item['content']);
                $model->where('key','=',$item['key'])->update(array('content'=>$item['content']));
            }
        }
        return $this->success("保存成功");
    }



}
