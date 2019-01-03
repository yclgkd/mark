<?php
/**
 * Created by PhpStorm.
 * User: CHUNLAI
 * Date: 2018/10/13
 * Time: 19:21
 */

namespace app\api\controller\v1;

use app\api\model\CheckParams;
use app\api\model\GetCookie;
use app\api\model\Grade as GradeModel;
use think\Controller;

/**
 * Class Grade 查询在校成绩
 * @package app\api\controller\v1
 */
class Grade extends Controller
{
    public function grade($semester, $studentID, $password)
    {
        $password = urlencode($password);
        $cookie = GetCookie::getCookie();
        CheckParams::checkParams($studentID, $password);
        $result = (new GradeModel())->getGrade($semester, $studentID, $password, $cookie);
        return $result;
    }
}