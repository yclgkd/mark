<?php
/**
 * Created by PhpStorm.
 * User: CHUNLAI
 * Date: 2018/10/9
 * Time: 13:50
 */

namespace app\api\validate;


class Password extends BaseValidate
{
    protected $rule = [
        'password' => 'require|length:6,28'
    ];
    protected $message = [
        'password.require' => '密码不能为空',
        'password.length' => '密码至少6位',
    ];
}