<?php
//下级
namespace app\super\controller;

use app\BaseController;
use app\common\model\ActivationCateModel;
use app\common\model\ActivationCodeModel;
use think\App;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;


class ActivationCate extends BaseController
{

    //验证登录
    protected $middleware = [\app\middleware\LoginAuth::class];

    public function items(){
        $page = intval($this->request->param('page'));
        $limit = $this->request->param('pagesize',20,'intval');
        if($page <= 0){
            $page = 1;
        }
        $time=$this->request->param('time/a');

        $keyword = trim($this->request->param('keyword'));

        $where=[];
        if(!empty($keyword)){
            $where[]=['name|desc','like',"%$keyword%"];
        }
        if(is_array($time) && count($time) == 2){
            $where[]=['create_time','between',[$time[0]/1000,$time[1]/1000]];
        }

        $activation_cate = new ActivationCateModel();

        $data=$activation_cate
            ->where($where)->limit(($page - 1)*$limit,$limit)->field($activation_cate->fields)->order('id desc')->select();
        $count = $activation_cate
            ->where($where)->count();
        $items=[];
        foreach($data as $item){
            $_item=[];
            $_item['id']=$item['id'];
            $_item['name']=$item['name'];
            $_item['desc']=$item['desc'];
            $_item['create_time']=decode_time($item['create_time']);
            $_item['update_time']=decode_time($item['update_time']);
            $items[]=$_item;
        }
        return $this->success("操作成功",array('items'=>$items,'count'=>$count));
    }

    public function add(){
        $name=trim($this->request->param('name'));
        $desc=trim($this->request->param('desc'));

        if(empty($name)){
            return $this->error("请输入名称");
        }
        $activation_cate = new ActivationCateModel();

        $req=$activation_cate->where('name','=',$name)->find();
        if($req){
            return $this->error("名称已存在");
        }

        $item=[];
        $item['name']=$name;
        $item['desc']=$desc;
        $item['create_time']=time();
        $item['update_time']=time();

        $req=$activation_cate->insert($item);
        if($req){
            return $this->success("操作成功");
        }else{
            return $this->error("生成失败");
        }
    }
    public function edit(){
        $id=intval($this->request->param('id'));
        $name=trim($this->request->param('name'));
        $desc=trim($this->request->param('desc'));

        if(empty($name)){
            return $this->error("请输入名称");
        }
        if($id <= 0){
            return $this->error("参数错误");
        }
        $activation_cate = new ActivationCateModel();

        $where=[];
        $where[]=['id','<>',$id];
        $where[]=['name','=',$name];

        $req=$activation_cate->where($where)->find();
        if($req){
            return $this->error("名称已存在");
        }

        $item=[];
        $item['name']=$name;
        $item['desc']=$desc;
        $item['update_time']=time();

        $req=$activation_cate->where('id','=',$id)->update($item);
        if($req){
            return $this->success("操作成功");
        }else{
            return $this->error("生成失败");
        }
    }

    public function del(){
        $ids=$this->request->param('ids/a');

        if(count($ids) <= 0){
            return $this->error("至少删除一个");
        }
        $activation_cate = new ActivationCateModel();
        $req=$activation_cate->where('id','in',$ids)->delete();
        if($req){
            return $this->success("操作成功");
        }else{
            return $this->error("操作失败");
        }
    }

    public function getlist(){
        $activation_cate = new ActivationCateModel();

        $items=$activation_cate->order('id desc')->field('id,name')->select();
        return $this->success("获取成功",$items);
    }



}
