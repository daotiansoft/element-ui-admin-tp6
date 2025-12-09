<?php
// 应用公共文件
/**
 * 密码加密
 * @param $password
 * @return string
 */
function get_password($password,$rand_code){
    $password=md5($rand_code.md5($password));
    return $password;
}

/**
 * 时间戳格式化
 * @param $time
 * @param string $format
 * @return false|string
 */
function decode_time($time,$format='Y-m-d H:i:s'){
    if($time<=0){
        return '-';
    }
    return date($format,$time);
}

/**
 * 生成随机字符串
 * @param $length
 * @return string
 */
function get_rand_str($length){
    //字符组合
    $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $len = strlen($str)-1;
    $randstr = '';
    for ($i=0;$i<$length;$i++) {
        $num=mt_rand(0,$len);
        $randstr .= $str[$num];
    }
    return $randstr;
}

/**
 * 将html中的域名编码替换
 * @param $content
 */
function html_domain_encode($content,$url=''){
    if(empty($url)){
        $url = request()->domain();
    }
    return str_replace($url,'[domain]',$content);
}
/**
 * 将html中的域名解码替换
 * @param $content
 */
function html_domain_decode($content,$url=''){
    if(empty($url)){
        $url = request()->domain();
    }
    return str_replace('[domain]',$url,$content);
}

/**
 * 生成订单号
 * @return string
 */
function create_order_id(){
    $osn = date('dHis').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    $osn=strtoupper(get_rand_str(2)).$osn;
    return $osn;
}