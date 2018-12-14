<?php
/**
 * Created by PhpStorm.
 * User: CHUNLAI
 * Date: 2018/10/9
 * Time: 15:56
 */

namespace app\api\lib\exception;


class ParameterException extends BaseException
{
    public $code = 400;
    public $msg = 'Parameter Error';
    public $errorCode = 10000;
}