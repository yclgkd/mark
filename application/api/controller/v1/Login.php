<?php
/**
 * Created by PhpStorm.
 * User: CHUNLAI
 * Date: 2018/10/9
 * Time: 8:15
 */

namespace app\api\controller\v1;


use app\api\model\CheckParams;
use app\api\model\GetCookie;
use app\api\model\Login as LoginModel;
use think\Controller;

/**
 * Class Login 登录类
 * @package app\api\controller\v1
 */
class Login extends Controller
{
    public function login($studentID, $password)
    {
        $password = urlencode($password);
        $cookie = GetCookie::getCookie();
        CheckParams::checkParams($studentID, $password);
        $result = (new LoginModel())->doLogin($studentID, $password, $cookie);
        return $result;
    }
}