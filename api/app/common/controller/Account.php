<?php
namespace app\common\controller;

use app\BaseController;
use app\common\model\ActivationCodeModel;
use app\common\model\EditorModel;
use app\common\model\TokenModel;
use app\common\model\UploadModel;
use app\common\model\UserModel;
use think\App;


class Account extends BaseController
{

    //验证登录
    protected $middleware = [\app\middleware\LoginAuth::class];

    public function info(){
        $userinfo=array();
        $userinfo['uid']=$this->request->userData['id'];
        $userinfo['username']=$this->request->userData['username'];
        $userinfo['avatar']='https://wpimg.wallstcn.com/f778738c-e4f8-4870-b634-56703b4acafe.gif?imageView2/1/w/80/h/80';
        $userinfo['roles'] = [$this->request->userData['type']];
        $userinfo['balance']=$this->request->userData['balance'];
        return $this->success("SUCCESS",$userinfo);
    }

    public function logout(){
        $token_model=new TokenModel();
        $token_model->logout($this->request->userData['id'],$token_model::DEVICE_API);
        return $this->success("退出成功");
    }

    /**
     * @api {GET} /super/user/password 修改密码
     * @apiVersion 1.0.0
     * @apiName password
     * @apiGroup user
     *
     * @apiParam {String} password_old 旧密码
     * @apiParam {String} password_new 新密码
     * @apiParam {String} google_key 谷歌验证码
     *
     * @apiHeader {String} token TOKEN
     *
     * @apiSuccess {Int} code 状态码
     * @apiSuccess {String} msg 提示内容
     * @apiSuccess {String} data 返回数据
     * @apiSuccessExample  {json} success-example
    {
    "code": 1,
    "msg": "修改成功"
    }
     */
    public function password(){
        $password_old=$this->request->param('password_old');
        $password_new=$this->request->param('password_new');

        if(empty($password_old)){
            return $this->error("请输入原密码");
        }
        if(strlen($password_new) < 6){
            return $this->error("新密码长度不可小于6位");
        }
        if($password_old == $password_new){
            return $this->error("原密码与新密码不可相同");
        }

        $user_model=new UserModel();

        if(get_password($password_old,$this->request->userData['rand_code']) != $this->request->userData['password']){
            return $this->error("原密码不正确");
        }

        $data=[];
        $data['rand_code'] = get_rand_str(4);
        $data['password'] = get_password($password_new,$data['rand_code']);
        $req=$user_model->where('id','=',$this->request->userData['id'])->update($data);
        if($req){
            return $this->success("修改成功");
        }else{
            return $this->error("修改失败");
        }
    }

    public function notice(){

        $notice=new EditorModel();
        $data=$notice->getContentByKey('notice');
        return $this->success("获取成功",$data);
    }

    //获取上传地址
    public function uploadinfo(){
        $url=url('upload',[],'',true)->build();
        $data=[];
        $data['action'] = $url;
        $data['headers'] = array(
            'Api-Token'=>$this->request->header('Api-Token')
        );
        return $this->success("获取成功",$data);
    }

    //上传图片
    public function upload(){
        try {
            validate(
                [
                'image'=>['fileSize'=>1024*1024*4,'fileExt'=>'jpg,png,gif']
                ]
            )->check(request()->file());

            $image = request()->file('image');
            if($image){
                $savename = \think\facade\Filesystem::disk('public')->putFile( 'topic', $image);
                $savename = \think\facade\Filesystem::getDiskConfig('public','url').'/'.str_replace("\\","/",$savename);

                $data = [];
                $data['name'] = $image->getFilename();
                $data['size'] = $image->getSize();
                $data['mime'] = $image->getMime();
                $data['type'] = 'image';
                $data['time'] = time();
                $data['path'] = $savename;
                $uploadModel = new UploadModel();
                $uploadModel->insert($data);

                return $this->success("上传成功",request()->domain().$savename);
            }
        } catch (\think\exception\ValidateException $e) {
            return $this->error($e->getMessage());
        }
    }

    public function active(){
        $code=$this->request->param('code');
        if(empty($code)){
            return $this->error("请输入激活码");
        }
        $uid=$this->request->userData['id'];
        $vip_time= $this->request->userData['vip_time'];

        $activation_code = new ActivationCodeModel();
        $activation_code->startTrans();
        $item = $activation_code->where('code','=',$code)->field($activation_code->fields)->find();
        if(!$item || $item['active_uid'] > 0){
            $activation_code->rollback();
            return $this->error("激活码不存在");
        }

        $activation_code->where('code','=',$code)->update(array('active_uid'=>$uid,'active_time'=>time()));
        $activation_code->commit();

        $member=new UserModel();

        $activation_data=$uid=$this->request->userData['activation_data'];
        if(empty($activation_data)){
            $activation_data=array();
        }else{
            $activation_data=json_decode($activation_data,true);
        }
        if(!isset($activation_data[$item['cate_id']])){
            $activation_data[$item['cate_id']]=array();
        }
        if(!isset($activation_data[$item['cate_id']]['expire_time'])){
            $activation_data[$item['cate_id']]['expire_time'] = 0;
        }
        if(!isset($activation_data[$item['cate_id']]['amount'])){
            $activation_data[$item['cate_id']]['amount'] = 0;
        }

        if($item['time'] > 0){
            //充值时间
            if($activation_data[$item['cate_id']]['expire_time'] > time()){
                $activation_data[$item['cate_id']]['expire_time'] +=$item['time'] * 60 * 60;
            }else{
                $activation_data[$item['cate_id']]['expire_time'] =time() + $item['time'] * 60 * 60;
            }
        }
        if($item['amount'] > 0){
            //充值次数
            $activation_data[$item['cate_id']]['amount'] +=$item['amount'];
        }

        $data=[];
        $data['update_time']=time();
        $data['activation_data']=json_encode($activation_data);
        $req=$member->where('id','=',$uid)->update($data);
        if($req){
            return $this->success("激活成功");
        }else{
            return $this->error("激活失败");
        }
    }
}
