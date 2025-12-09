<?php
namespace app\common\model;

class ArticleCateModel extends CommonModel
{
    protected $name = 'article_cate';

    const STATUS_ON = 1;//正常
    const STATUS_OFF = -1;//隐藏
    public $fields=[
        'id',
        'name',
        'desc',
        'status',
        'create_time',
        'update_time'
    ];

    public function insert_data($data){
        if(!isset($data['create_time'])){
            $data['create_time'] = time();
        }
        if(!isset($data['update_time'])){
            $data['update_time'] = time();
        }
        if(!isset($data['status'])){
            $data['status'] = self::STATUS_ON;
        }
        if(!isset($data['name']) || empty($data['name'])){
            return '名称不能为空';
        }
        $where=[];
        $where[]=['name','=',$data['name']];

        $req=$this->where($where)->find();
        if($req){
            return "名称已存在";
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
        if(!isset($data['status'])){
            $data['status'] = self::STATUS_ON;
        }
        if(!isset($data['name']) || empty($data['name'])){
            return '名称不能为空';
        }
        if(!isset($data['id']) || empty($data['id'])){
            return '参数错误';
        }
        $where=[];
        $where[]=['id','<>',$data['id']];
        $where[]=['name','=',$data['name']];

        $req=$this->where($where)->find();
        if($req){
            return "名称已存在";
        }

        $req = $this->where('id','=',$data['id'])->update($data);
        if($req){
            return true;
        }
        return false;
    }
}
