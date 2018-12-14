<?php
/**
 * Created by PhpStorm.
 * User: CHUNLAI
 * Date: 2018/10/23
 * Time: 9:34
 */

namespace app\api\validate;


class NewPassword extends BaseValidate
{
    protected $rule = [
        'letterAndNum' => ['/([0-9]+[a-zA-Z]+|[a-zA-Z]+[0-9]+)[0-9a-zA-Z]*/'],
        'length' => 'between:8,16'
    ];
    protected  $message = [
        'letterAndNum' => '密码中必须包含字母和数字',
        'length' => '密码必须在8到16位之间'
    ];
}