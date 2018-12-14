<?php
/**
 * Created by PhpStorm.
 * User: CHUNLAI
 * Date: 2018/10/17
 * Time: 2:06
 */

namespace app\api\validate;


use app\api\lib\exception\BaseException;

class IDCard extends BaseException
{
    protected $rule = [
        'IDCard' => ['/(^\d(15)$)|((^\d{18}$))|(^\d{17}(\d|X|x)$)/']
    ];
    protected  $message = [
        'IDCard' => '非法身份证号'
    ];
}