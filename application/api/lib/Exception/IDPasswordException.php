<?php
/**
 * Created by PhpStorm.
 * User: CHUNLAI
 * Date: 2018/10/13
 * Time: 16:57
 */

namespace app\api\lib\Exception;


class IDPasswordException extends BaseException
{
    public $code = 400;
    public $msg = '用户名或密码错误，首次登录密码为身份证后6位';
    public $errorCode = 60001;
}