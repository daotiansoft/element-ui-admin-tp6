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

namespace app\common\service;


use app\common\exception\RequestException;
use app\common\model\AppListModel;

class AppListService
{
    public static function check($params = []){
        ksort($params);
        $appid = isset($params['appid']) ? $params['appid'] : '';
        $sign = isset($params['sign']) ? $params['sign'] : '';
        $time = isset($params['time']) ? intval($params['time']) : 0;

        if(empty($appid) || empty($sign) || $time < time() - 60){
            throw new RequestException('参数错误');
        }

        $appModel = new AppListModel();
        $appItem = $appModel->where('appid','=',$appid)->field($appModel->fields)->find();
        if(!$appItem || $appItem['status'] != AppListModel::STATUS_ON){
            throw new RequestException('appid不存在');
        }

        $sign_str = [];
        foreach($params as $key=>$v){
            if($key == 'sign'){
                continue;
            }
            $sign_str[] = $key . '=' .$v;
        }
        $sign_str = implode('&',$sign_str);

        if($sign != md5($sign_str . '&secret=' .$appItem['secret'])){
            throw new RequestException('sign错误',-1,['sign_str'=>$sign_str . '&secret=****']);
        }
        return $appItem;
    }
}