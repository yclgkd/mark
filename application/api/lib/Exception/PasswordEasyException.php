<?php
/**
 * Created by PhpStorm.
 * User: CHUNLAI
 * Date: 2018/10/13
 * Time: 16:51
 */

namespace app\api\lib\Exception;


class PasswordEasyException extends BaseException
{
    public $code = 400;
    public $msg = '密码过于简单，请重新设置密码';
    public $errorCode = 60002;
}