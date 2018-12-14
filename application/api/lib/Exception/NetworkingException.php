<?php
/**
 * Created by PhpStorm.
 * User: CHUNLAI
 * Date: 2018/10/12
 * Time: 19:20
 */

namespace app\api\lib\Exception;


class NetworkingException extends BaseException
{
    public $code = 400;
    public $msg = '网络未连接，请检查网络状态';
    public $errorCode = 10004;
}