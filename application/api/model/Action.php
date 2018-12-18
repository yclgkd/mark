<?php
/**
 * Created by PhpStorm.
 * User: CHUNLAI
 * Date: 2018/10/17
 * Time: 18:22
 */

namespace app\api\model;


class Action
{
    public static function curl_get($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)
         AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100 Safari/537.36');
        //不自动输出数据，要echo才行
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //参数为0表示不带头文件，为1表示带头文件
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json; charset=utf-8'
        ));
        $data = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($data);
        return $data;
    }

    public static function curl_post($url, $post)
    {

        $ch = curl_init();
        //设置提交的url
        curl_setopt($ch, CURLOPT_URL, $url);
//        //设置头文件的信息作为数据流输出
//        curl_setopt($ch, CURLOPT_HEADER, 0);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json; charset=utf-8'
        ));
        //设置post方式提交
        curl_setopt($ch, CURLOPT_POST, 1);
        //post提交数据
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        //执行命令
        $data = curl_exec($ch);
        //关闭URL请求
        curl_close($ch);
        //获得数据并返回
        return $data;
    }
    //开启curl post请求
    public static function get_http_array($url,$post_data) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);   //没有这个会自动输出，不用print_r();也会在后面多个1
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $output = curl_exec($ch);
        echo $output;
        die;
    }
}