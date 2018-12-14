<?php
/**
 * Created by PhpStorm.
 * User: CHUNLAI
 * Date: 2018/10/14
 * Time: 18:24
 */

namespace app\api\controller\v1;


use app\api\model\CheckParams;
use app\api\model\GetCookie;
use app\api\model\Timetable as TimetableModel;

class Timetable
{
    public function timetable($semester, $studentID, $password)
    {
        $cookie = GetCookie::getCookie();
        CheckParams::checkParams($studentID, $password);
        $result = (new TimetableModel())->getTimetable($semester, $studentID, $password, $cookie);
        return $result;
    }
}