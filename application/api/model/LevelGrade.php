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
        $result = $this->dataHandle($studentID, $password, $cookie);
        return $result;
    }

    private function dataHandle($studentID, $password, $cookie)
    {
        $levelGradePage = $this->getLevelGradePage($studentID, $password, $cookie);
        preg_match_all('/(<td>|<td align="left">)([^<>\n]+)/', $levelGradePage, $isNull);
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

    private function getLevelGradePage($studentID, $password, $cookie)
    {
        (new Login())->doLogin($studentID, $password, $cookie);
        $url = "http://172.16.2.39/jsxsd/kscj/djkscj_list";
        $levelGradePage = (new Login())->loginCurl($url, "", $cookie);
        return $levelGradePage;
    }
}