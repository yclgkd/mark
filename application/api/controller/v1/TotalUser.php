<?php
/**
 * Created by PhpStorm.
 * User: CHUNLAI
 * Date: 2018/10/20
 * Time: 0:03
 */

namespace app\api\controller\v1;

use app\api\model\TotalUser as TotalUserModel;
use think\Controller;

class TotalUser extends Controller
{
    /**
     * 获取用户访问小程序数据概况
     * @return mixed
     */
    public function totalUser()
    {
        $result = (new TotalUserModel())->getTotalUser();
        return $result;
    }
}