<?php
/**
 * Created by PhpStorm.
 * User: CHUNLAI
 * Date: 2018/12/18
 * Time: 12:54
 */

namespace app\api\lib\Exception;


class EvaluationFailException extends BaseException
{
    public $code = 400;
    public $msg = '评教失败';
    public $errorCode = 40002;
}