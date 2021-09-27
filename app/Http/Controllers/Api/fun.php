<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
/**
 * 生成签名
 * @param $data 待签名数据
 * @param $appsecret 应用Appsecret
 * @return string
 */
$api = "https://sapi.ztpay.org/api/v2";
$appid = "ztpayrb6yk9auf9xzz";
$appsecret = "osxZBtLWs07NvNYFztk4eeWRV6UsPMVW";
function getSign($data,$appsecret) {
    $signPars = "";
    ksort($data);
    foreach($data as $k => $v) {
        if("sign" != $k && "" != $v && $v!="0") {
            $signPars .= $k . "=" . $v . "&";
        }
    }
    $signPars .= "key=" . $appsecret;
    return strtoupper(md5($signPars));
}


/**
 * 返回json数据
 */
function json($state=0,$message='',$data=array(),$time=''){
    header('Content-Type:application/json; charset=utf-8');
    exit(json_encode(array('code'=>$state,'message'=>$message,'data'=>$data,'time'=>$time)));
}

/**
 * 打印数据
 * @param $data
 */

function p($data){
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}



/**
 * 使用curl发送
 * @param string $url
 * @param string $param
 * @return bool|mixed
 */
function request_post($url = '', $param = '') {
    if (empty($url) || empty($param)) {
        return false;
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSLVERSION, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
    $data = curl_exec($ch);
    curl_close($ch);

    return json_decode($data,true);
}

/**
 * 是否ajax请求
 * @return bool
 */
function is_ajax(){
    // php判断是否为ajax请求
    if (isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == 'xmlhttprequest'){
        return true;
    } else {
        return false;
    }
}