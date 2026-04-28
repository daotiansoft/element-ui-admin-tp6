<?php
namespace app\common\model;

class EditorModel extends CommonModel
{
    protected $name = 'editor';

    protected $schema=[
        'id'=>'int',
        'key'=>'string',
        'name'=>'string',
        'content'=>'string',
    ];
    public function getContentByKey($key){
        $req= $this->where('key','=',$key)->find();
        if($req){
            return $req['content'];
        }
        return '';
    }
}
