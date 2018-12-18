<?php
/**
 * Created by PhpStorm.
 * User: CHUNLAI
 * Date: 2018/12/14
 * Time: 14:30
 */

namespace app\api\model;


use app\api\lib\Exception\MissException;

class LevelGrade
{
    public function getLevelGrade($studentID, $password, $cookie)
    {
        (new Login())->doLogin($studentID, $password, $cookie);
        $page = $this->getLevelGradePage($cookie);
        $result = $this->dataHandle($page);
        return $result;
    }

    private function dataHandle($page)
    {
        preg_match_all('/(<td>|<td align="left">)([^<>\n]+)/', $page, $isNull);
        if (!empty($isNull)) {
            if ($isNull[1] == '未查询到数据') {
                throw new MissException();
            }
        }
        //原始数据
        $originalData = $isNull[2];
        //总共参加的等级考试数
        $courseNum = count($originalData) / 4;
        //处理等级考试成绩
        $result[] = [];
        for ($i = 0; $i < $courseNum; $i++) {
            for ($j = 0; $j < 3; $j++) {
                $result[$i][$j] = $originalData[$i * 4 + $j + 1];
            }
        }
        return $result;
    }

    //登录等级考试的页面
    private function getLevelGradePage($cookie)
    {
        $url = "http://172.16.2.39/jsxsd/kscj/djkscj_list";
        $levelGradePage = (new Login())->loginCurl($url, "", $cookie);
        return $levelGradePage;
    }
}