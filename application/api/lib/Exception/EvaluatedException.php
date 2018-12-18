<?php
/**
 * Created by PhpStorm.
 * User: CHUNLAI
 * Date: 2018/12/18
 * Time: 13:00
 */

namespace app\api\lib\Exception;


class EvaluatedException extends BaseException
{
    public $code = 400;
    public $msg = '已评教';
    public $errorCode = 40003;
}