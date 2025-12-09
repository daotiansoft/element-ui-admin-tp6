<?php
namespace app\common\model;

class EditorModel extends CommonModel
{
    protected $name = 'editor';

    public $fields=[
        'id',
        'key',
        'name',
        'content'
    ];
    public function getContentByKey($key){
        $req= $this->where('key','=',$key)->field($this->fields)->find();
        if($req){
            return $req['content'];
        }
        return '';
    }
}
