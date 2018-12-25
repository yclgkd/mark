<?php
/**
 * Created by PhpStorm.
 * User: CHUNLAI
 * Date: 2018/12/21
 * Time: 18:23
 */

namespace app\api\model;


use app\api\lib\Exception\MissException;

class ExaminationArrangement
{
    public function getExaminationArrangement($studentID, $password, $cookie)
    {
        (new Login())->doLogin($studentID, $password, $cookie);
        $page = $this->getExaminationArrangementPage($cookie);
        $result = $this->dataHandle($page);
        return $result;
    }

    private function getExaminationArrangementPage($cookie)
    {
        $url = "http://172.16.2.39/jsxsd/xsks/xsksap_ls";
        $examinationArrangementPage = (new Login())->loginCurl($url, "", $cookie);
        return $examinationArrangementPage;
    }

    private function dataHandle($page)
    {
        preg_match_all('/<td>([^<>\n]*)<\/td>/', $page, $matches);
        if (empty($matches[0])) {
            throw new MissException(['msg'=>'暂无考试安排', 'errorCode'=>40004]);
        }
        //考试数
        $examNum = count($matches[1]) / 10;
        $result[] = [];
        for ($i = 0; $i < $examNum; $i++) {
                $result[$i] = [
                    'course' => $matches[1][2+$i*10],
                    'time' => $matches[1][6+$i*10],
                    'address' => $matches[1][7+$i*10],
                    'seatNum' => $matches[1][8+$i*10]
                ];
        }
        return $result;
    }
}