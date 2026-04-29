<?php
namespace app\common\model;

use app\common\utils\UrlUtils;

class ConfigModel extends CommonModel
{
    protected $name = 'config';

    public $type_list = ['text','image','select'];

    const SITE_STATUS_ON = 1;//站点开启
    const SITE_STATUS_OFF = 2;//站点关闭

    const SHOW_ON=1;//显示
    const SHOW_OFF=-1;//隐藏

    protected $schema=[
        'id'=>'int',
        'key'=>'string',
        'type'=>'string',
        'name'=>'string',
        'content'=>'string',
        'placeholder'=>'string',
        'params'=>'string',
        'sort'=>'int',
        'show'=>'int',
    ];
    public function getContentByKey($key){
        $req= $this->where('key','=',$key)->find();
        if($req){
            if($req['type'] == 'image'){
                return UrlUtils::editorAbsoluteSrc($req['content']);
            }
            return $req['content'];
        }
        return '';
    }
}

