<?php
namespace app\common\model;

class ConfigModel extends CommonModel
{
    protected $name = 'config';

    public $type_list = ['text','image','select'];

    const SITE_STATUS_ON = 1;//站点开启
    const SITE_STATUS_OFF = 2;//站点关闭

    const SHOW_ON=1;//显示
    const SHOW_OFF=-1;//隐藏

    public $fields=[
        'id',
        'key',
        'type',
        'name',
        'content',
        'placeholder',
        'params',
        'sort',
        'show'
    ];
    public function getContentByKey($key,$cache_time = 60){
        $req= $this->where('key','=',$key)->field($this->fields)->cache($cache_time)->find();
        if($req){
            return $req['content'];
        }
        return '';
    }
}

