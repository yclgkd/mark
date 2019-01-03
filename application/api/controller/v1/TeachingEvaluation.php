<?php
/**
 * Created by PhpStorm.
 * User: CHUNLAI
 * Date: 2018/12/17
 * Time: 21:16
 */

namespace app\api\controller\v1;

use app\api\model\CheckParams;
use app\api\model\GetCookie;
use app\api\model\TeachingEvaluation as TeachingEvaluationModel;
use think\Controller;

class TeachingEvaluation extends Controller
{
    public function teachingEvaluation($studentID, $password)
    {
        $password = urlencode($password);
        $cookie = GetCookie::getCookie();
        CheckParams::checkParams($studentID, $password);
        $result = (new TeachingEvaluationModel())->doTeachingEvaluation($studentID, $password, $cookie);
        return $result;
    }
}