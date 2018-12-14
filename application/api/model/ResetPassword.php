<?php
/**
 * Created by PhpStorm.
 * User: CHUNLAI
 * Date: 2018/10/17
 * Time: 1:33
 */

namespace app\api\model;


class ResetPassword
{
    /**
     * 重置密码，返回重置成功或失败的json结构体
     * @param $studentID
     * @param $IDCard
     * @return mixed
     */
    public static function doResetPassword($studentID, $IDCard)
    {
        $url = 'http://172.16.2.39/Logon.do?method=resetPasswd';
        //拼接url
        $url = $url.'&account='.$studentID.'&accounttype=2&sfzjh='.$IDCard;
        $result = Action::curl_get($url);
        return $result;
    }
}