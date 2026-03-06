<?php
// +----------------------------------------------------------------------
// | WaitAdmin快速开发后台管理系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习程序代码,建议反馈是我们前进的动力
// | 程序完全开源可支持商用,允许去除界面版权信息
// | gitee:   https://gitee.com/wafts/waitadmin-php
// | github:  https://github.com/topwait/waitadmin-php
// | 官方网站: https://www.waitadmin.cn
// | WaitAdmin团队版权所有并拥有最终解释权
// +----------------------------------------------------------------------
// | Author: WaitAdmin Team <2474369941@qq.com>
// +----------------------------------------------------------------------
declare (strict_types = 1);

namespace app\common\basics;

use app\BaseController;
use app\common\exception\NotAuthException;
use app\common\model\UserModel;
use app\common\service\LoginService;
use think\App;

/**
 * 后台基类
 */
abstract class Auth extends BaseController
{
    /**
     * 账号信息
     */
    protected $userItem = [];

    /**
     * 不校验登录的方法
     * @var array
     */
    protected $notNeedLogin = [];

    /**
     * 不校验权限的方法
     * @var array
     */
    protected $notNeedPower = [];

    /**
     * 构造方法
     *
     * Backend constructor.
     * @param App $app
     * @throws NotAuthException
     */
    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->checkLogin();

        $this->checkPower();
    }

    /**
     * 初始方法
     *
     * @return void
     * @throws NotAuthException
     * @author zero
     */
    protected function initialize(): void
    {

    }

    /**
     * 验证登录
     *
     * @return bool
     * @throws NotAuthException
     * @author zero
     */
    protected function checkLogin(): bool
    {

        if (in_array(request()->action(), $this->notNeedLogin)) {
            //设置全局账号信息
            return true;
        } else {
            $token = request()->header('Api-Token');
            if(empty($token)){
                throw new NotAuthException('请登录后再操作!');
            }
            $userData = LoginService::checkAuthorization($token);
            if ($userData) {
                $userModel = new UserModel();
                $this->userItem = $userModel->getItemById($userData->uid);
                return true;
            } else {
                throw new NotAuthException('请登录后再操作!');
            }
        }
    }

    /**
     * 验证权限
     *
     * @return bool
     * @throws NotAuthException
     * @author zero
     */
    protected function checkPower(): bool
    {
        $prefixName = config('project.backend_entrance');

        $requestUrl = lcfirst(str_replace($prefixName, '', request()->baseUrl()));
        $requestUrl = ltrim($requestUrl, '/');
        $requestUrl = ($requestUrl == 'index' || !$requestUrl) ? 'index/index' : $requestUrl;

        if (in_array(request()->action(), $this->notNeedLogin) ||
            in_array(request()->action(), $this->notNeedPower) ||
            $requestUrl === 'index/index' ||
            $this->userItem['id'] === 1)
        {
            return true;
        }


    }
}