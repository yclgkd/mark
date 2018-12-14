<?php
/**
 * Created by PhpStorm.
 * User: CHUNLAI
 * Date: 2018/10/14
 * Time: 18:24
 */

namespace app\api\model;


class Timetable
{
    /**
     * 将处理的所有数据返回，得到课表
     * @param $semester
     * @param $studentID
     * @param $password
     * @param $cookie
     * @return array
     */
    public function getTimetable($semester, $studentID, $password, $cookie)
    {
        (new Login())->doLogin($studentID, $password, $cookie);
        $result = $this->dataHandle($semester, $cookie);
        return $result;
    }

    /**
     * 遍历处理整个学期的课程数据
     * @param $semester
     * @param $cookie
     * @return array
     */
    public function dataHandle($semester, $cookie)
    {
        $result = [];
        for ($i = 0; $i < 30; $i++) {
            $result[$i] = $this->getOneWeekData($semester, $i + 1, $cookie);
        }
        return $result;
    }

    /**
     * 处理一周的课程数据
     * @param $semester
     * @param $whichWeek
     * @param $cookie
     * @return array
     */
    public function getOneWeekData($semester, $whichWeek, $cookie)
    {
        $gradePage = $this->getTimetablePage($semester, $whichWeek, $cookie);
        //获取表格
        preg_match('/<table id="kbtable" [\w\W]*?>([\d\D]*?)<\/table>/',
            $gradePage, $result);
        preg_match_all('/<div [\w\W]*?[style="" class="kbcontent"]>([\d\D]*?)<\/div>/',
            $result[1], $matches);
        //获取筛选后的课程表
        $courseTable[] = [];
        for ($i = 0, $k = 0; $i < 5; $i++) {
            for ($j = 0; $j < 7; $j++) {
                $courseTable[$i][$j] = $matches[1][$k];
                $k += 2;
            }
        }
        //开始处理筛选后的课程表的数据
        $courseName = [];//课程名
        $courseWeek = [];//上课周数
        $courseAddress = [];//上课地点
        $whichDay = [];//星期几
        $whichCourse = [];//第几课
        for ($i = 0, $m = 0; $i < 5; $i++) {
            for ($j = 0; $j < 7; $j++) {
                if ($courseTable[$i][$j] == "&nbsp;") {
                    continue;
                }
                preg_match('/([^<>]*?)<br\/>/', $courseTable[$i][$j], $matches);
                $courseName[$m] = $matches[1];
                preg_match_all('/<font [\w\W]*?>([^<>]*?)<\/font>/',
                    $courseTable[$i][$j], $matches);
                $courseWeek[$m] = $matches[1][0];
                //如果上课地点为空则''
                if (count($matches[1]) == 2) {
                    $courseAddress[$m] = $matches[1][1];
                } else {
                    $courseAddress[$m] = '';
                }
                $whichDay[$m] = $j + 1;
                $whichCourse[$m] = $i + 1;
                $m++;
            }
        }
        //一周的课表
        $oneWeek = [];
        for ($i = 0; $i < count($courseName); $i++) {
            $oneWeek[$i] = [
                'whichDay' => $whichDay[$i],
                'whichCourse' => $whichCourse[$i],
                'courseName' => $courseName[$i],
                'courseWeek' => $courseWeek[$i],
                'courseAddress' => $courseAddress[$i],
            ];
        }
        return $oneWeek;
    }

    /**
     * 获取一周的页面
     * @param $semester
     * @param $whichWeek
     * @param $cookie
     * @return mixed
     */
    public function getTimetablePage($semester, $whichWeek, $cookie)
    {
        $url = "http://172.16.2.39/jsxsd/xskb/xskb_list.do?zc=" . $whichWeek . "&xnxq01id=" . $semester;
        $gradePage = (new Login())->loginCurl($url, "", $cookie);
        return $gradePage;
    }
}