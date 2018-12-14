<?php
/**
 * Created by PhpStorm.
 * User: CHUNLAI
 * Date: 2018/10/21
 * Time: 17:33
 */

namespace app\api\lib\Exception;


class StudentIDException extends BaseException
{
    public $code = 400;
    public $msg = '不存在此学号';
    public $errorCode = 60004;
}