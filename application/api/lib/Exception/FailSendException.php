<?php
/**
 * Created by PhpStorm.
 * User: CHUNLAI
 * Date: 2018/11/4
 * Time: 15:19
 */

namespace app\api\lib\Exception;


class FailSendException extends BaseException
{
    public $code = 400;
    public $msg = 'Message could not be sent.';
    public $errorCode = 50000;
}