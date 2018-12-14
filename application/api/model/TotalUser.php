<?php
/**
 * Created by PhpStorm.
 * User: CHUNLAI
 * Date: 2018/10/20
 * Time: 0:04
 */

namespace app\api\model;


class TotalUser
{
    public function getTotalUser()
    {
        $access_token = $this->getToken();
        $url = "https://api.weixin.qq.com/datacube/getweanalysisappiddailysummarytrend?access_token=".$access_token;
        $post = [
            'begin_date' => "20181020",
            "end_date" => "20181020"
        ];
        $post = json_encode($post);
        $result = Action::curl_post($url, $post);
        $result = json_decode($result);
        return $result;
    }

    /**
     * 获取令牌
     * @return mixed
     */
    public function getToken()
    {
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx434e547886c520a9&secret=362a4ecfcd4b53e2eaf76bca3f3529b8";
        $result = Action::curl_get($url);
        $result = get_object_vars($result);//将对象转化成数组
        $access_token = $result['access_token'];
        return $access_token;
    }
}