<?php
/**
 * Created by PhpStorm.
 * User: CHUNLAI
 * Date: 2018/12/21
 * Time: 18:22
 */

namespace app\api\controller\v1;

use app\api\model\CheckParams;
use app\api\model\GetCookie;
use app\api\model\ExaminationArrangement as ExaminationArrangementModel;
use think\Controller;

class ExaminationArrangement extends Controller
{
    public function examinationArrangement($studentID, $password){
        $password = urlencode($password);
        $cookie = GetCookie::getCookie();
        CheckParams::checkParams($studentID, $password);
        $result = (new ExaminationArrangementModel())->getExaminationArrangement($studentID, $password, $cookie);
        return $result;
    }
}