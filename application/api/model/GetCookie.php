<?php
/**
 * Created by PhpStorm.
 * User: CHUNLAI
 * Date: 2018/10/13
 * Time: 9:29
 */

namespace app\api\model;


class GetCookie
{
    public static function getCookie($url = "172.16.2.39/jsxsd/")
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        // 获取头部信息
        curl_setopt($ch, CURLOPT_HEADER, 1);
        // 返回原生的（Raw）输出
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        curl_setopt($ch,CURLOPT_TIMEOUT,10);
        $content = curl_exec($ch);
        curl_close($ch);
//        var_dump(getallheaders());return;
//        var_dump(get_headers("http://172.16.2.39/jsxsd/", 1));return;
//        echo $content;return;
        //TODO:异常以后写
//        try{
//            // 解析HTTP数据流
        list($header, $body) = explode("\r\n\r\n", $content);
//        }catch (Exception $e){
//            throw new ServerException();
//        };
        // 解析COOKIE
        preg_match_all("/(set\-cookie: )([^\r\n]*)(;)/i", $header, $matches);
        // 后面用CURL提交的时候可以直接使用
        //TODO:异常以后写
//        try{
        $cookie = $matches[2][0] . ";" . $matches[2][1] . ";";
//        }catch (Exception $e){
//            throw new NetworkingException();
//        };
        return $cookie;
    }
}