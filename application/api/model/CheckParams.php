<?php
/**
 * Created by PhpStorm.
 * User: CHUNLAI
 * Date: 2018/10/14
 * Time: 18:56
 */

namespace app\api\model;


use app\api\validate\StudentID;
use app\api\validate\Password;

class CheckParams
{
    public static function checkParams($studentID, $password)
    {
        //校验学号
        $data = ['studentID' => $studentID];
        (new StudentID())->gocheck1($data);
        //校验密码
        $data = ['password' => $password];
        (new Password())->goCheck($data);
        return true;
    }
}