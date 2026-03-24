<?php
namespace app\openapi\controller;

use app\BaseController;
use app\common\http\server\Websocket;
use think\App;


class Index extends BaseController
{

    public function index(){
        return 'element-ui-admin-tp6';
    }
    public function test(){
        $message = [];
        $message['type'] = Websocket::TEXT_TYPE_SEND_MESSAGE;
        $message['uid'] = 1;
        $message['message'] = [
            'time'=>time()
        ];
        //$res = Websocket::sendData($message);
        $res = Websocket::getOnlineUser();
        return $res;
    }
}
