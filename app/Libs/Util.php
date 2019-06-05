<?php
/**
 * Created by PhpStorm.
 * User: henry
 * Date: 2019/4/12
 * Time: 10:39 AM
 */

namespace App\Libs;


use App\Libs\SMS\VerifySms;
use Illuminate\Support\Str;

class Util{
    //转意数据
    public static function saddslashes($string) {
        if (is_array($string)) {
            foreach ($string as $key=>$val) {
                $string[$key] = Util::saddslashes($val);
            }
        } else {
            $string = addslashes($string);
        }
        return $string;
    }
    //图片名称
    public static function getUploadImageName(){
        $time = time();
        $str = $time . self::randOrderID6();
        return md5($str);
    }
    //curl
    public static function curl($url, $timeout = 0, $referrer = '') {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($referrer) curl_setopt($ch, CURLOPT_REFERER, $referrer);

        if ($timeout > 0) {
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        }

        if (strlen($url) > 5 && strtolower(substr($url, 0, 5)) == "https") {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }

        $reponse = curl_exec($ch);
        curl_close($ch);
        return $reponse;
    }
    public static function curlPost($url, $data, $ssl=true) {
        //curl完成
        $curl = curl_init();
        //设置curl选项
        curl_setopt($curl, CURLOPT_URL, $url);//URL
        $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:38.0) Gecko/20100101 Firefox/38.0 FirePHP/0.7.4';
        curl_setopt($curl, CURLOPT_USERAGENT, $user_agent);//user_agent，请求代理信息
        curl_setopt($curl, CURLOPT_AUTOREFERER, true);//referer头，请求来源
        curl_setopt($curl, CURLOPT_TIMEOUT, 0);//设置超时时间
        //SSL相关
        if ($ssl) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);//禁用后cURL将终止从服务端进行验证
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);//检查服务器SSL证书中是否存在一个公用名(common name)。
        }
        // 处理post相关选项
        curl_setopt($curl, CURLOPT_POST, true);// 是否为POST请求
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);// 处理请求数据
        // 处理响应结果
        curl_setopt($curl, CURLOPT_HEADER, false);//是否处理响应头
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);//curl_exec()是否返回响应结果

        // 发出请求
        $response = curl_exec($curl);
        if (false === $response) {
            echo '<br>', curl_error($curl), '<br>';
            return false;
        }
        curl_close($curl);
        return $response;
    }
    public static function curlPostJson($url, $data, $timeout = 0) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        curl_setopt($ch, CURLOPT_URL, $url);
        if ($timeout > 0) {
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        }
        if (strlen($url) > 5 && strtolower(substr($url, 0, 5)) == "https") {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
        $reponse = curl_exec($ch);
        curl_close($ch);
        return $reponse;
    }
    //生成密码
    public static function password($user_password, $user_salt) {
        return md5(md5($user_password).strval($user_salt));
    }

    //生成sessionKey
    public static function makePkey($uid, $timestamp, $tel) {
        $pkey = array('u' => $uid, 'time' => $timestamp, 'phone' => $tel);
        $pkey = Util::up_encode($pkey);
        return $pkey;
    }

    //加密
    public static function up_encode($str) {
        if (is_array($str) || is_object($str)) $str = json_encode($str);
        $cr = new Aes(Crypt3DesKey);
        $str = $cr->encrypt($str);
        $str = str_replace('+', '-', $str);
        $str = str_replace('/', '.', $str);
        $str = str_replace('=', '!', $str);
        return $str;
    }

    //解密
    public static function up_decode($str) {
        if (!$str) return $str;
        $str = urldecode($str);
        $str = trim($str);
        $cr = new Aes (Crypt3DesKey);
        $str = str_replace('-', '+', $str);
        $str = str_replace('.', '/', $str);
        $str = str_replace('!', '=', $str);
        $str = $cr->decrypt($str);
        $couldbeA = json_decode($str, true);
        if (is_array($couldbeA)) return $couldbeA;
        return false;
    }
    //jsonp
    public static function jsonp($data, $callback = 'callback') {
        @header('Content-Type: application/json');
        @header("Expires:-1");
        @header("Cache-Control:no-cache");
        @header("Pragma:no-cache");
        if (isset($_REQUEST[$callback])) {
            header("Access-Control-Allow-Origin:*");
            echo $_REQUEST[$callback].'('.json_encode($data, JSON_UNESCAPED_UNICODE).')';
        } else echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
    //获取服务号
    public static function getAccessToken(){
        $key = 'get_jiamengship_server_access_token';
        $access_token = '';
        if (RedisManager::getAccessToken($key)){
            return RedisManager::getAccessToken($key);
        }
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$GLOBALS['jiamengshuo_server']['appid'].'&secret='.$GLOBALS['jiamengshuo_server']['appsecret'];
        $result = self::curl($url);
        if (!empty($result)) $result = json_decode($result);
        if (!empty($result) && !empty($result->access_token)) $access_token = $result->access_token;
        if (!empty($access_token)) {
            RedisManager::setAccessToken($key, $access_token, 7000);
        }
        return $access_token;
    }
    //获取小程序
    public static function getWXAccessToken(){
        $key = 'get_jiamengship_wx_access_token';
        $access_token = '';
        if (RedisManager::getAccessToken($key)){
            return RedisManager::getAccessToken($key);
        }
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$GLOBALS['jiamengshuo']['appid'].'&secret='.$GLOBALS['jiamengshuo']['appsecret'];
        $result = self::curl($url);
        if (!empty($result)) $result = json_decode($result);
        if (!empty($result) && !empty($result->access_token)) $access_token = $result->access_token;
        if (!empty($access_token)) {
            RedisManager::setAccessToken($key, $access_token, 7000);
        }
        return $access_token;
    }
    public static function randOrderID() {
        return date('YmdHis').rand(10000,99999);
    }
    public static function randOrderID6() {
        return date('YmdHis').rand(100000,999990);
    }
    //转换手机号码
    public static function formatPhone($phone) {
        return substr($phone, 0, 3).'****'.substr($phone, 7);
    }
    //根据时间判断几分钟前，几天前
    public static function tranTime($time)
    {
        $time = TIMESTAMP - $time;
        if ($time < 60) $str = '刚刚';
        elseif ($time < 60 * 60){$min = floor($time/60);$str = $min.'分钟前';}
        elseif ($time < 60 * 60 * 24) {$h = floor($time/(60*60));$str = $h.'小时前 ';}
        elseif ($time < 60 * 60 * 24 * 7) {$d = floor($time/(60*60*24));$str = $d.'天前';}
        else {$str = '7天之前';}
        return $str;
    }
    //字符串截取
    public static function mbSubstr($str, $start = 0, $length, $charset = "utf-8", $suffix = true){
        $str = strip_tags(trim($str));
        if(function_exists("mb_substr")){
            if($suffix)
                return mb_substr($str, $start, $length, $charset) . "...";
            else
                return mb_substr($str, $start, $length, $charset);
        }
        elseif(function_exists('iconv_substr')){
            if($suffix)
                return iconv_substr($str, $start, $length, $charset) . "...";
            else
                return iconv_substr($str, $start, $length, $charset);
        }
        $re['utf-8']  = "/[x01-x7f]|[xc2-xdf][x80-xbf]|[xe0-xef][x80-xbf]{2}|[xf0-xff][x80-xbf]{3}/";
        $re['gb2312'] = "/[x01-x7f]|[xb0-xf7][xa0-xfe]/";
        $re['gbk']    = "/[x01-x7f]|[x81-xfe][x40-xfe]/";
        $re['big5']   = "/[x01-x7f]|[x81-xfe]([x40-x7e]|xa1-xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $slice = join("", array_slice($match[0], $start, $length));
        if ($suffix) return $slice . "…";
        return $slice;
    }
    //加盐
    public static function getSalt(){
        return substr(uniqid(rand()), -6);
    }
    //验证手机号码
    public static function check_mobile($mobile){
        if (!preg_match('/^0?(13[0-9]|15[0-9]|18[0-9]|14[0-9]|17[0-9]|19[0-9]|16[6])[0-9]{8}$/', $mobile)) return false;
        return true;
    }
    //日志
    public static function writeLog($key, $data){
        $ymd = date('Ymd');
        $date = date('Y-m-d H:i:s');
        $file = storage_path('logs') . "/{$ymd}_{$key}.log";
        $ref = @$_SERVER['HTTP_REFERER'];
        $url = @$_SERVER['REQUEST_URI'];
        if (!is_string($data)) $data = json_encode($data, JSON_UNESCAPED_UNICODE);
        $params = array('POST' => $_POST);
        $params = empty($_POST) ? '' : json_encode($params, JSON_UNESCAPED_UNICODE);
        $content = "date: {$date}\n";
        if (!empty($ref)) $content .= "referer: {$ref}\n";
        if (!empty($url)) $content .= "request: {$url}\n";
        if (!empty($params)) $content .= "params: {$params}\n";
        if (!empty($data)) $content .= "content: {$data}\n";
        $content .= "\n";
        error_log($content, 3 , $file);
    }
    //写简单日志
    public static function SmallLog($key, $content) {
        $ymd = date("Ymd");
        $date = date('Y-m-d H:i:s');
        $file = storage_path('logs') . "/{$ymd}_{$key}.log";
        if (!is_string($content)) $content = json_encode($content, JSON_UNESCAPED_UNICODE);
        $content = "date:{$date}\tcontent:{$content}\n";
        error_log($content, 3, $file);
    }
    //发送手机验证码
    public static function sendSMS($tel){
        $code = mt_rand(100000,999999);
        $smsApi = new VerifySms();
        $result = $smsApi->send($tel, $code);
        $flag = true;
        $msg = '';
        if(!is_null(json_decode($result))){
            $output=json_decode($result,true);
            if(isset($output['return_code'])  && $output['return_code']=='00000'){$flag = true;}
            else{$flag = false;$msg = isset($output['errorMsg']) ? $output['errorMsg'] : '短信发送失败';}
        }else{$flag = false;$msg = $result;}
        $data = [];
        $data['tel'] = $tel;
        $data['code'] = $code;
        $data['addtime'] = time();
        $data['state'] = $flag ? 1 : 0;
        $data['msg'] = $msg;
        //(new SendSMS())->insertOrUpdateByTel($tel,$data);
        return $flag;
    }
    public static function checkEmail($email){
        $preg_email='/^[a-zA-Z0-9]+([-_.][a-zA-Z0-9]+)*@([a-zA-Z0-9]+[-.])+([a-z]{2,5})$/ims';
        if(preg_match($preg_email, $email)) return true;
        else return false;
    }
    public static function getUnionId($code, $from='jms'){
        if (!empty($from) && $from == 'jms'){
            $appid = $GLOBALS['jiamengshuo']['appid'];
            $appsecret = $GLOBALS['jiamengshuo']['appsecret'];
        }else if (!empty($from) && $from == 'fx'){
            $appid = $GLOBALS['fanxiang']['appid'];
            $appsecret = $GLOBALS['fanxiang']['appsecret'];
        }

        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid='.$appid.'&secret='.$appsecret.'&js_code='.$code.'&grant_type=authorization_code';
        $result = self::curl($url);
        if (empty($result)) return [];
        $result = json_decode($result, true);
        if (empty($result)) return [];
        if (isset($result['openid']) && isset($result['session_key'])){
            return ['openid'=>$result['openid'],'session_key'=>$result['session_key']];
        }
        return [];
    }
    //laravel uuid
    public static function getUUID(){
        return (string) Str::uuid();
    }
    public static function getLoginToken($ip, $userInfo){
        $str = $ip.$userInfo->uuid.$userInfo->password;
        return md5($str)."#".$userInfo->id;
    }
    // 获取本周所有日期
    public static function get_week($time = '', $format='Ymd'){
        $time = $time != '' ? $time : time();
        //获取当前周几
        $week = date('w', $time);
        $date = [];
        for ($i=1; $i<=7; $i++){
            $date[$i] = date($format ,strtotime( '+' . $i-$week .' days', $time));
        }
        return $date;
    }

    //获取最近七天所有日期
    public static function getLateOfDay($time='', $format='Ymd'){
        $time = $time != '' ? $time : time();    //组合数据
        $date = [];
        for ($i=1; $i<=7; $i++){
            $date[$i] = date($format ,strtotime( '+' . $i-7 .' days', $time));
        }
        return $date;
    }
}
