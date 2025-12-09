<?php
declare (strict_types = 1);

namespace app\middleware;

use app\common\model\TokenModel;
use app\common\model\UserModel;

class LoginAuth
{
    /**
     * 处理请求 验证授
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {
        $token=$request->header('Api-Token');
        if(empty($token)){
            return $this->ret_json(-200,"请先登录");
        }
        $token_model=new TokenModel();
        $token_data=$token_model->getItemByToken($token);
        if(!$token_data || $token_data['expire_time'] < time()){
            return $this->ret_json(-200,"登录失效，请重新登录");
        }

        $user_model = new UserModel();
        $user_data=$user_model->getItemById($token_data['uid']);
        if(!$user_data){
            return $this->ret_json(-1,"账号不存在");
        }
        if($user_data['status'] != $user_model::STATUS_ON){
            return $this->ret_json(-1,"账号已停用")->send();
        }
        $request->userData=$user_data;

        if(!$this->check_role($request)){
            return $this->ret_json(-1,"无权限",trim(strtolower(App('http')->getName()).'/'.strtolower($request->controller()).'/'.$request->action()));
        }

        return $next($request);
    }

    protected function ret_json($code=0,$msg='',$data=[]){
        $ret=[];
        $ret['code']=$code;
        $ret['msg']=$msg;
        $ret['data']=$data;
        return json($ret);
    }

    /**
     * 判断权限
     * @param $request
     * @return bool
     */
    public function check_role($request){
        $user_model = new UserModel();
        $user_data = $request->userData;
        $rolename = trim(strtolower(App('http')->getName()).'/'.strtolower($request->controller()).'/'.$request->action());

        //通用权限
        $roles_public = [
            'common/user/captcha'=>true,//true为所有账号可用
            'common/user/login'=>true,
            'common/user/init'=>true,

            'common/account/info'=>true,
            'common/account/logout'=>true,
            'common/account/password'=>true,
            'common/account/notice'=>true,
            'common/account/active'=>true,
        ];
        //管理员权限
        $roles_super=[
            //上传
            'common/account/uploadinfo'=>[$user_model::TYPE_ADMIN],
            'common/account/upload'=>[$user_model::TYPE_ADMIN],

            //配置
            'super/config/items'=>[$user_model::TYPE_ADMIN],
            'super/config/save'=>[$user_model::TYPE_ADMIN],
            'super/editor/items'=>[$user_model::TYPE_ADMIN],
            'super/editor/save'=>[$user_model::TYPE_ADMIN],

            //账号管理
            'super/member/items'=>[$user_model::TYPE_ADMIN],
            'super/member/add'=>[$user_model::TYPE_ADMIN],
            'super/member/edit'=>[$user_model::TYPE_ADMIN],
            'super/member/del'=>[$user_model::TYPE_ADMIN],
            'super/member/balanceIn'=>[$user_model::TYPE_ADMIN],
            'super/member/balanceOut'=>[$user_model::TYPE_ADMIN],

            //余额记录
            'super/balancelog/items'=>[$user_model::TYPE_ADMIN],
            'super/balancelog/del'=>[$user_model::TYPE_ADMIN],

            //激活码分类
            'super/activationcate/items'=>[$user_model::TYPE_ADMIN],
            'super/activationcate/add'=>[$user_model::TYPE_ADMIN],
            'super/activationcate/edit'=>[$user_model::TYPE_ADMIN],
            'super/activationcate/del'=>[$user_model::TYPE_ADMIN],
            'super/activationcate/getlist'=>[$user_model::TYPE_ADMIN],

            //激活码
            'super/activationcode/items'=>[$user_model::TYPE_ADMIN],
            'super/activationcode/add'=>[$user_model::TYPE_ADMIN],
            'super/activationcode/del'=>[$user_model::TYPE_ADMIN],
            'super/activationcode/export'=>[$user_model::TYPE_ADMIN],

            //文章分类
            'super/articlecate/items'=>[$user_model::TYPE_ADMIN],
            'super/articlecate/add'=>[$user_model::TYPE_ADMIN],
            'super/articlecate/edit'=>[$user_model::TYPE_ADMIN],
            'super/articlecate/del'=>[$user_model::TYPE_ADMIN],

            //文章列表
            'super/articlelist/items'=>[$user_model::TYPE_ADMIN],
            'super/articlelist/add'=>[$user_model::TYPE_ADMIN],
            'super/articlelist/edit'=>[$user_model::TYPE_ADMIN],
            'super/articlelist/del'=>[$user_model::TYPE_ADMIN],
            'super/articlelist/cates'=>[$user_model::TYPE_ADMIN],

            //提现
            'super/balancewithdrawal/items'=>[$user_model::TYPE_ADMIN],
            'super/balancewithdrawal/add'=>[$user_model::TYPE_ADMIN],
            'super/balancewithdrawal/auth'=>[$user_model::TYPE_ADMIN],
        ];
        //用户权限
        $roles_user=[
            //余额变动记录
            'user/balancelog/items'=>[$user_model::TYPE_USER],

            //提现
            'user/balancewithdrawal/items'=>[$user_model::TYPE_USER],
            'user/balancewithdrawal/add'=>[$user_model::TYPE_USER],

        ];
        $roles = array_merge($roles_public,$roles_super,$roles_user);
        if(isset($roles[$rolename])){
            if($roles[$rolename] === true){
                return true;
            }
            if(in_array($user_data['type'],$roles[$rolename])){
                return true;
            }
        }


        return false;
    }
}
