<?php
/**
 * Created by PhpStorm.
 * User: CHUNLAI
 * Date: 2018/10/9
 * Time: 8:36
 */

namespace app\api\lib\exception;


use think\Exception;

class BaseException extends Exception
{
    //HTTP状态码
    public $code = 400;
    //错误信息
    public $msg = 'Parameter Error';
    //自定义状态码
    public $errorCode = 10000;

    public function __construct($params = [])
    {
        if (!is_array($params)){
            return;
//            throw new Exception('参数必须是数组');
        }
        if (array_key_exists('code', $params)){
            $this->code = $params['code'];
        }
        if (array_key_exists('msg', $params)){
            $this->msg = $params['msg'];
        }
        if (array_key_exists('errorCode', $params)){
            $this->errorCode = $params['errorCode'];
        }
    }
}