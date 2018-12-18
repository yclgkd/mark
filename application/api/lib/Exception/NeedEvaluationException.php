<?php
/**
 * Created by PhpStorm.
 * User: CHUNLAI
 * Date: 2018/12/17
 * Time: 23:27
 */

namespace app\api\lib\Exception;


class NeedEvaluationException extends BaseException
{
    public $code = 400;
    public $msg = '请先评教';
    public $errorCode = 40001;
}