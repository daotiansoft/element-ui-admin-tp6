<?php
namespace app\common\controller;

use app\BaseController;
use app\common\model\ConfigModel;
use app\common\model\LoginLogModel;
use app\common\model\TokenModel;
use app\common\model\UserModel;
use jolalau\captcha\facade\CaptchaApi;


class Overt extends BaseController
{
    public function captcha(){
        $data = CaptchaApi::create();
        $data=array(
            'key'=>$data['key'],
            'data'=>str_replace("\r\n","",$data['base64'])
        );
        return $this->success("获取成功",$data);
    }
    public function login(){
        $params=[];
        $params['username']=$this->request->param('username');
        $params['password']=$this->request->param('password');
        $params['captcha']=$this->request->param('captcha');
        $params['captcha_key']=$this->request->param('captcha_key');

        $user_model=new UserModel();
        $login_log_model=new LoginLogModel();
        $token_model=new TokenModel();


        if ( ! CaptchaApi::check($params['captcha'],$params['captcha_key'])) {
            return $this->error("验证码不正确");
        }
        if(empty($params['username']) || empty($params['password'])){
            return $this->error("账号或密码不能为空");
        }
        $user_data=$user_model->where('username','=',$params['username'])->field($user_model->fields)->find();
        if(!$user_data){
            return $this->error("账号或密码不正确");
        }
        if($user_data['password'] != get_password($params['password'],$user_data['rand_code'])){
            $login_log_model->log($user_data['id'],$token_model::DEVICE_API,$login_log_model::STATUS_FAIL,$this->request->ip(),'密码错误');
            return $this->error("账号或密码不正确");
        }
        if($user_data['status'] != $user_model::STATUS_ON){
            $login_log_model->log($user_data['id'],$token_model::DEVICE_API,$login_log_model::STATUS_FAIL,$this->request->ip(),'账号停用');
            return $this->error("您的账号已停用，请联系客服");
        }

        if($user_data['type'] != $user_model::TYPE_ADMIN){
            $config = new ConfigModel();
            $site_status = intval($config->getContentByKey('site_status'));
            if($site_status != $config::SITE_STATUS_ON){
                $site_stop_msg = $config->getContentByKey('site_stop_msg');
                return $this->error($site_stop_msg);
            }
        }

        $token_data=$token_model->login($user_data['id'],$token_model::DEVICE_API);
        if(!$token_data){
            $login_log_model->log($user_data['id'],$token_model::DEVICE_API,$login_log_model::STATUS_FAIL,$this->request->ip(),'TOKEN失败');
            return $this->error("登录失败");
        }
        $ret=[];
        $ret['token']=$token_data['token'];
        $ret['expire_time']=$token_data['expire_time'];

        $login_log_model->log($user_data['id'],$token_model::DEVICE_API,$login_log_model::STATUS_SUCC,$this->request->ip(),'登录成功');
        return $this->success("登录成功",$ret);
    }

    public function init(){
        $config = new ConfigModel();
        $data=[];
        $data['site_name'] = $config->getContentByKey('site_name');
        $data['site_status'] = intval($config->getContentByKey('site_status'));
        $data['site_stop_msg'] = $config->getContentByKey('site_stop_msg');
        return $this->success("获取成功",$data);
    }
}
