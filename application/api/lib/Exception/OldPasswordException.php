<?php
/**
 * Created by PhpStorm.
 * User: CHUNLAI
 * Date: 2018/10/21
 * Time: 16:26
 */

namespace app\api\lib\Exception;


class OldPasswordException extends BaseException
{
    public $code = 400;
    public $msg = '原密码错误';
    public $errorCode = 60003;
}