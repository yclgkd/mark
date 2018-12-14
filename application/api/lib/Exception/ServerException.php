<?php
/**
 * Created by PhpStorm.
 * User: CHUNLAI
 * Date: 2018/10/12
 * Time: 19:39
 */

namespace app\api\lib\Exception;


class ServerException extends BaseException
{
    public $code = 400;
    public $msg = '服务器异常，请稍后再试';
    public $errorCode = 10005;
}