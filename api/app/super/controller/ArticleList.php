<?php
//下级
namespace app\super\controller;

use app\BaseController;
use app\common\model\ActivationCateModel;
use app\common\model\ActivationCodeModel;
use app\common\model\ArticleCateModel;
use app\common\model\ArticleListModel;
use think\App;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;


class ArticleList extends BaseController
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
        $cate_id = intval($this->request->param('cate_id'));

        $where=[];
        if(!empty($keyword)){
            $where[]=['article_list_model.title|article_list_model.desc','like',"%$keyword%"];
        }
        if(is_array($time) && count($time) == 2){
            $where[]=['article_list_model.create_time','between',[$time[0]/1000,$time[1]/1000]];
        }
        if($cate_id > 0){
            $where[]=['article_list_model.cate_id','=',$cate_id];
        }
        $model = new ArticleListModel();

        $fields = [];
        foreach($model->fields as $f){
            $fields[]='article_list_model.'.$f;
        }

        $data=$model
            ->withJoin(['cate'],'LEFT')
            ->where($where)->limit(($page - 1)*$limit,$limit)->field($fields)->order('article_list_model.id desc')->select();
        $count = $model
            ->withJoin(['cate'],'LEFT')
            ->where($where)->count();
        $items=[];
        foreach($data as $item){
            $_item=[];
            $_item['id']=$item['id'];
            $_item['cate_id']=isset($item->cate['id'])?$item->cate['id']:0;
            $_item['cate_name']=isset($item->cate['name'])?$item->cate['name']:'未分类';
            $_item['title']=$item['title'];
            $_item['desc']=$item['desc'];
            $_item['content']=html_domain_decode($item['content']);
            $_item['create_time']=decode_time($item['create_time']);
            $_item['update_time']=decode_time($item['update_time']);
            $items[]=$_item;
        }
        return $this->success("操作成功",array('items'=>$items,'count'=>$count));
    }

    public function add(){
        $data=[];
        $data['cate_id']=intval($this->request->param('cate_id'));
        $data['title']=trim($this->request->param('title'));
        $data['desc']=trim($this->request->param('desc'));
        $data['content']=trim($this->request->param('content'));
        $data['content'] = html_domain_encode($data['content']);

        $model = new ArticleListModel();



        $req=$model->insert_data($data);
        if($req === true){
            return $this->success("操作成功");
        }
        if($req !== false){
            return $this->error($req);
        }
        return $this->error("操作失败");
    }
    public function edit(){
        $data=[];
        $data['id']=intval($this->request->param('id'));
        $data['cate_id']=intval($this->request->param('cate_id'));
        $data['title']=trim($this->request->param('title'));
        $data['desc']=trim($this->request->param('desc'));
        $data['content']=trim($this->request->param('content'));
        $data['content'] = html_domain_encode($data['content']);

        $model = new ArticleListModel();

        $req=$model->update_data($data);
        if($req === true){
            return $this->success("操作成功");
        }
        if($req !== false){
            return $this->error($req);
        }
        return $this->error("操作失败");
    }

    public function del(){
        $ids=$this->request->param('ids/a');

        if(count($ids) <= 0){
            return $this->error("至少删除一个");
        }
        $model = new ArticleListModel();
        $req=$model->where('id','in',$ids)->delete();
        if($req){
            return $this->success("操作成功");
        }else{
            return $this->error("操作失败");
        }
    }

    public function cates(){
        $article_cate = new ArticleCateModel();
        $data = $article_cate->field('id,name')->select();
        return $this->success("获取成功",$data);
    }

}
