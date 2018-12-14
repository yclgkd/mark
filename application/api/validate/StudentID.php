<?php
/**
 * Created by PhpStorm.
 * User: CHUNLAI
 * Date: 2018/10/9
 * Time: 13:41
 */

namespace app\api\validate;


class StudentID extends BaseValidate
{
    protected $rule = [
        'studentID' => 'require|number|length:10'
    ];
    protected  $message = [
        'studentID.require' => '学号不能为空',
        'studentID.number' => '学号必须为数字',
        'studentID.length' => '学号长度为10，请检查后重新输入'
    ];
}