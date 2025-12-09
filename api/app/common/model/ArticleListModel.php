<?php
namespace app\common\model;

class ArticleListModel extends CommonModel
{
    protected $name = 'article_list';

    public $fields=[
        'id',
        'cate_id',
        'title',
        'desc',
        'content',
        'create_time',
        'update_time'
    ];

    public function cate(){
        return $this->belongsTo(ArticleCateModel::class,'cate_id','id');
    }

    public function insert_data($data){
        if(!isset($data['create_time'])){
            $data['create_time'] = time();
        }
        if(!isset($data['update_time'])){
            $data['update_time'] = time();
        }
        if(!isset($data['title']) || empty($data['title'])){
            return '标题不能为空';
        }
        $req = $this->insert($data);
        if($req){
            return true;
        }
        return false;
    }

    public function update_data($data){
        if(!isset($data['update_time'])){
            $data['update_time'] = time();
        }
        if(!isset($data['title']) || empty($data['title'])){
            return '标题不能为空';
        }
        if(!isset($data['id']) || empty($data['id'])){
            return '参数错误';
        }

        $req = $this->where('id','=',$data['id'])->update($data);
        if($req){
            return true;
        }
        return false;
    }
}
