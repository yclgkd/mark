<?php
/**
 * Created by PhpStorm.
 * User: CHUNLAI
 * Date: 2018/10/13
 * Time: 17:42
 */

namespace app\api\model;


use app\api\lib\Exception\MissException;
use app\api\lib\Exception\MissGradeException;

class Grade
{
    public function getGrade($semester, $studentID, $password, $cookie)
    {
        $result = $this->dataHandle($semester, $studentID, $password, $cookie);
        return $result;
    }


    /**
     * 对成绩数据进行数据处理
     * @param $semester
     * @param $studentID
     * @param $password
     * @param $cookie
     * @return array
     * @throws MissException
     */
    private function dataHandle($semester, $studentID, $password, $cookie)
    {
        $gradePage = $this->getGradePage($semester, $studentID, $password, $cookie);
        preg_match('/<td colspan="11">([^<>\n]+)/', $gradePage, $isNull);
        if (!empty($isNull)){
            if ($isNull[1] == '未查询到数据'){
                throw new MissException();
            }
        }
        //获取课程名，并保存到$courseName中
        preg_match_all('/<td align="left">([^<>\n]+)/',
            $gradePage, $matchesName);
        //获取成绩
        preg_match_all('/700,500\)">([^<>\n]+)/',
            $gradePage, $matchesGrade);
        //获取学分、绩点
        preg_match_all('/<td>([^<>\n]+)/',
            $gradePage, $matchesOther);
        $courseNum = count($matchesName[1]) / 2;
        $result[] = [];
        preg_match('/^(-?\d+)(\.\d+)?/', $matchesOther[1][4], $check);
        //如果绩点那一项为空
        if (empty($check)) {
            for ($i = 0, $j = 1; $i < $courseNum; $i++) {
                $result[$i] = [
                    'courseName' => $matchesName[1][$j],
                    'credit' => $matchesOther[1][2 + $i * 7],
                    'coursePoint' => '',
                    'courseGrade' => $matchesGrade[1][$i]
                ];
                $j += 2;
            }
        }else{
            for ($i = 0, $j = 1; $i < $courseNum; $i++) {
                $result[$i] = [
                    'courseName' => $matchesName[1][$j],
                    'credit' => $matchesOther[1][2 + $i * 8],
                    'coursePoint' => $matchesOther[1][4 + $i * 8],
                    'courseGrade' => $matchesGrade[1][$i]
                ];
                $j += 2;
            }
        }
        return $result;
    }

    public function getGradePage($semester, $studentID, $password, $cookie)
    {
        (new Login())->doLogin($studentID, $password, $cookie);
        $url = "http://172.16.2.39/jsxsd/kscj/cjcx_list?kksj=" . $semester . "&kcxz=&kcmc=&xsfs=all";
        $gradePage = (new Login())->loginCurl($url, "", $cookie);
        return $gradePage;
    }
}