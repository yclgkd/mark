<?php
/**
 * Created by PhpStorm.
 * User: CHUNLAI
 * Date: 2018/10/14
 * Time: 15:08
 */

namespace app\api\lib\Exception;


class MissException extends BaseException
{
    public $code = 400;
    public $msg = 'global:your required resource are not found';
    public $errorCode = 10001;
}