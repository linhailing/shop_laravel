<?php
/**
 * Created by PhpStorm.
 * User: henry
 * Date: 2019/4/23
 * Time: 6:19 PM
 */

namespace App\Libs;


class QrWxAvatar{
    /**
     * @param string $path
     * @param string $id
     * 获取微信小程序二维码
     * @return bool|mixed|null
     */
    public static function getWxQr($path, $id){
        //获取参数值
        $token_file = RESOURCEPATHCODE;
        $url="https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=".self::getAccessToken($token_file);
        $data=[
            'page' => $path,
            'scene'=> "id=".$id,
            'width'=>200,
            'auto_color'=>false,
        ];
        $data = json_encode($data);
        $result = Util::curlPostJson($url, $data);
        if (!$result) {
            return false;
        }
         $fileName = Util::getUploadImageName();
         file_put_contents($token_file.$fileName.".jpeg", $result);
        return UPLOADSURLQRCODE.$fileName.".jpeg";
    }
    public static function getAccessToken(){
        // 考虑过期问题，将获取的access_token存储到某个文件中
        $key = "access_token";
        $result = RedisManager::getAccessToken($key);
        if ($result) return $result;
        // 目标URL：
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$GLOBALS['jiamengshuo']['appid']."&secret=".$GLOBALS['jiamengshuo']['appsecret'];
        //向该URL，发送GET请求
        $result = Util::curl($url);
        if (!$result) {
            return false;
        }
        // 存在返回响应结果
        $result_obj = json_decode($result);
        // 写入
        $result = $result_obj->access_token;
        RedisManager::setAccessToken($key, $result, 7100);
        return $result;
    }
}