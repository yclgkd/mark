<?php
/**
 * Created by PhpStorm.
 * User: CHUNLAI
 * Date: 2018/10/17
 * Time: 1:32
 */

namespace app\api\controller\v1;


use app\api\lib\Exception\StudentIDException;
use app\api\model\ResetPassword as ResetPasswordModel;
use think\Controller;

class ResetPassword extends Controller
{
    public function resetPassword($studentID, $IDCard)
    {
        $result = ResetPasswordModel::doResetPassword($studentID, $IDCard);
        if (empty($result)){
            throw new StudentIDException();
        }
        return $result;
    }
}