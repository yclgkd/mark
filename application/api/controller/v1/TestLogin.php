<?php
/**
 * Created by PhpStorm.
 * User: CHUNLAI
 * Date: 2018/10/17
 * Time: 18:22
 */

namespace app\api\controller\v1;


use app\api\model\Action;
use think\Controller;
use think\Db;

class TestLogin extends Controller
{
    public function testLogin()
    {

        $url = "https://api.heifuture.com/api/v1/login?studentID=1520163452&password=9bGdGuL9";
        $result = Action::curl_get($url);
        $result = get_object_vars($result);//将对象转化成数组
        $studentID = "1520163452";
        $password = "9bGdGuL";
        //是否登录成功
        if (array_key_exists('studentID', $result)) {
            $check1 = Db::table('student')
                ->field($studentID)
                ->find();
            //是否为首次登录，如果是null，插入数据
            if ($check1 == null) {
                Db::table('student')
                    ->insert(['name' => $result['name'], 'studentID' => $result['studentID'],
                            'school' => $result['school'], 'profession' => $result['profession'],
                            'class' => $result['class'], 'password' => $password
                    ]);
            } else {
                Db::table('student')
                    ->where('studentID', '=', $studentID)
                    ->setField('password', $password);
            }
        }
        return $result;
    }
}