<?php
/**
 * Created by PhpStorm.
 * User: CHUNLAI
 * Date: 2018/10/17
 * Time: 1:34
 */

namespace app\api\controller\v1;

use app\api\model\ChangePassword as ChangePasswordModel;
use app\api\model\GetCookie;
use app\api\validate\NewPassword;
use think\Controller;

/**
 * Class ChangePassword 改变密码
 * @package app\api\controller\v1
 */
class ChangePassword extends Controller
{
    public function changePassword($studentID, $oldPassword, $newPassword)
    {
        //校验新密码
//        $data = ['password' => $newPassword];
//        (new NewPassword())->goCheck1($data);
        $oldPassword = urlencode($oldPassword);
        $newPassword = urlencode($newPassword);
        $cookie = GetCookie::getCookie();
        $result = (new ChangePasswordModel())->changePassword($studentID, $oldPassword, $newPassword, $cookie);
        return $result;
    }
}