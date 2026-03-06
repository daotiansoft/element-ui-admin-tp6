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

/**
 * 发起GET请求
 *
 * @param string $url (请求链接)
 * @param array $params (请求参数)
 * @param array $options (其它参数)
 * @return mixed
 * @author zero
 */
function curl_get(string $url, array $params = [], array $options=[]): mixed
{
    if (isset($params)) {
        $url = $url . '?' . http_build_query($params);
    }

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_TIMEOUT, 5000);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

    $headers[] = 'Content-Type: application/json; charset=utf-8';
    $headers = array_merge($headers, $options['headers']??[]);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    $data = curl_exec($curl);
    if (curl_error($curl)) {
        return false;
    } else {
        curl_close($curl);
        try {
            return json_decode($data, true);
        } catch (Exception $e) {
            return $data;
        }
    }
}

/**
 * 发起POST请求
 *
 * @param string $url (请求链接)
 * @param array $data (请求数据)
 * @param array $options (其它参数)
 * @return mixed
 * @author zero
 */
function curl_post(string $url, array $data = [], array $options=[]): mixed
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data, JSON_UNESCAPED_UNICODE));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $headers[] = 'Content-Type: application/json; charset=utf-8';
    $headers = array_merge($headers, $options['headers']??[]);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    $data = curl_exec($curl);
    if (curl_error($curl)) {
        print "Error: " . curl_error($curl);
        return false;
    } else {
        curl_close($curl);
        try {
            return json_decode($data, true);
        } catch (Exception $e) {
            return $data;
        }
    }
}
/**
 * 将字节转换为可读文本
 *
 * @param int $size (大小)
 * @param string $delimiter (分隔符)
 * @param int $precision (小数位数)
 * @return string
 * @author zero
 */
#[Pure]
function format_bytes(int $size, string $delimiter = '', int $precision = 2): string
{
    $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
    for ($i = 0; $size >= 1024 && $i < 6; $i++) {
        $size /= 1024;
    }
    return round($size, $precision) . $delimiter . $units[$i];
}

