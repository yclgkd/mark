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
use app\api\lib\Exception\NeedEvaluationException;

class Grade
{
    public function getGrade($semester, $studentID, $password, $cookie)
    {
        (new Login())->doLogin($studentID, $password, $cookie);
        $gradePage = $this->getGradePage($semester, $cookie);
        $result = $this->dataHandle($gradePage);
        return $result;
    }


    /**
     * @param $gradePage 成绩页面
     * @return array 成绩
     * @throws MissException 成绩不存在
     * @throws NeedEvaluationException 未评教
     */
    private function dataHandle($gradePage)
    {

        preg_match_all('/请评教/', $gradePage, $isNeedEvaluate);
        if (!empty($isNeedEvaluate[0])) {
            throw new NeedEvaluationException();
        }
        preg_match('/未查询到数据/', $gradePage, $isNull);
        if (!empty($isNull[0])) {
            throw new MissException(['code'=>'400', 'msg'=>'请求的成绩不存在', 'errorCode'=>'40000']);
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
        } else {
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

    //获取成绩页面
    public function getGradePage($semester, $cookie)
    {
        $url = "http://172.16.2.39/jsxsd/kscj/cjcx_list?kksj=" . $semester . "&kcxz=&kcmc=&xsfs=all";
        $gradePage = (new Login())->loginCurl($url, "", $cookie);
        return $gradePage;
    }
}