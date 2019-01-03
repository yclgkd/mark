<?php
/**
 * Created by PhpStorm.
 * User: CHUNLAI
 * Date: 2018/12/14
 * Time: 14:31
 */

namespace app\api\controller\v1;

use app\api\model\CheckParams;
use app\api\model\GetCookie;
use app\api\model\LevelGrade as LevelGradeModel;
use think\Controller;

/**
 * Class LevelGrade 查询等级考试成绩
 * @package app\api\controller\v1
 */
class LevelGrade extends Controller
{
    public function levelGrade($studentID, $password)
    {
        $password = urlencode($password);
        $cookie = GetCookie::getCookie();
        CheckParams::checkParams($studentID, $password);
        $result = (new LevelGradeModel())->getLevelGrade($studentID, $password, $cookie);
        return $result;
    }
}