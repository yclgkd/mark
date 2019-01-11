<?php
/**
 * Created by PhpStorm.
 * User: CHUNLAI
 * Date: 2018/12/17
 * Time: 21:17
 */

namespace app\api\model;


use app\api\lib\Exception\EvaluatedException;
use app\api\lib\Exception\EvaluationFailException;
use app\api\lib\Exception\MissException;

class TeachingEvaluation
{
    //进行评教
    public function doTeachingEvaluation($studentID, $password, $cookie, $auto, $evaluationContent)
    {
        (new Login())->doLogin($studentID, $password, $cookie);
        $result = $this->batchEvaluation($cookie, $auto, $evaluationContent);
        return $result;
    }

    //获取评教信息
    public function evaluationMsg($studentID, $password, $cookie)
    {
        (new Login())->doLogin($studentID, $password, $cookie);
        $result = $this->getEvaluationMsg($cookie);
        return $result;
    }

    //获取详细评教信息的处理
    private function getEvaluationMsg($cookie)
    {
        ////进入学生评价页面
        $url = "http://172.16.2.39/jsxsd/xspj/xspj_find.do";
        $teachingEvaluationPage = (new Login())->loginCurl($url, "", $cookie);
        //获取这学期需要评教的url
        preg_match_all('/<a.href="([^<>\n]+)".title/',
            $teachingEvaluationPage, $matches);
        if (empty($matches[0])) {
            throw new MissException(['msg' => '暂时无需评教', 'errorCode' => 40005]);
        }
        $url = "http://172.16.2.39" . $matches[1][0];
        //进入某个学期的教务评价页面
        $oneTeachingEvaluation = (new Login())->loginCurl($url, "", $cookie);
        preg_match_all('/<td>([^<>\n]*)<\/td>/', $oneTeachingEvaluation, $matches);
        //用来保存详细的信息
        $msg[] = [];
        for ($i = 0, $j = 0; $i < count($matches[1]) / 8; $i++) {
            $msg[$i] = [
                'id' => $matches[1][$i * 8],
                'subject' => $matches[1][2 + $i * 8],
                'teacher' => $matches[1][3 + $i * 8],
                'score' => $matches[1][5 + $i * 8],
                'evaluated' => $matches[1][6 + $i * 8], //是否评教
                'submit' => $matches[1][7 + $i * 8],     //是否提交
                'result' => ''  //评教结果
            ];
            //如果已经评教则查看结果，否则结果为空
            if ($msg[$i]['submit'] == '是') {
                preg_match_all('/<a.href="([^<>\n]+)">查看/',
                    $oneTeachingEvaluation, $resultUrl);
                $url = "http://172.16.2.39" . $resultUrl[1][$j++];
                //进入单个老师的评教页面
                $oneTeacherPage = (new Login())->loginCurl($url, "", $cookie);
                //匹配单个老师页面的ABCDE的结果
                preg_match_all('/checked="checked">([^<>\n]+)/',
                    $oneTeacherPage, $matchesResult);
                $oneTeacherResult = '';
                //对评教结果进行进一步处理
                for ($k = 0; $k < count($matchesResult[1]); $k++) {
                    $oneTeacherResult .= substr($matchesResult[1][$k], 1, 1);
                }
                $msg[$i]['result'] = $oneTeacherResult;
            }
        }
        return $msg;
    }

    /**
     * @param $cookie
     * @param $auto 是否自动评教
     * @param $evaluationContent 所有老师的评教信息
     * @return string
     * @throws EvaluatedException 已经评教
     * @throws EvaluationFailException 评教失败
     * @throws MissException 暂无需评教
     */
    private function batchEvaluation($cookie, $auto, $evaluationContent)
    {
        ////进入学生评价页面
        $url = "http://172.16.2.39/jsxsd/xspj/xspj_find.do";
        $teachingEvaluationPage = (new Login())->loginCurl($url, "", $cookie);
        //获取这学期需要评教的url
        preg_match_all('/<a.href="([^<>\n]+)".title/',
            $teachingEvaluationPage, $matches);
        if (empty($matches[0])) {
            throw new MissException(['msg' => '暂时无需评教', 'errorCode' => 40005]);
        }
        $url = "http://172.16.2.39" . $matches[1][0];
        //进入某个学期的教务评价页面
        $oneTeachingEvaluation = (new Login())->loginCurl($url, "", $cookie);
        //获取所需要评价老师的url
        preg_match_all('/<a.href="([^<>\n]+)">评价/',
            $oneTeachingEvaluation, $matches);
        if (empty($matches[1])) {
            throw new EvaluatedException();
        }
        if ($auto == 1) {
            for ($i = 0; $i < count($matches[1]); $i++) {
                $this->doOneTeachingEvaluation($matches[1][$i], $cookie);
            }
        } elseif ($auto == 0) {
            $content = [];
            //count($matches[1])是还需要评教的老师的总数
            for ($i = 0; $i < count($matches[1]); $i++) {
                //content中存放的是对每个老师的评教等级,6个字符为一组
                $content[$i] = substr($evaluationContent, $i * 6, 6);
                $this->doOneTeachingEvaluation($matches[1][$i], $cookie, $content[$i]);
            }
        }
        return "评教完成";
    }

    //对单个老师进行评价
    private function doOneTeachingEvaluation($url, $cookie, $content = '')
    {
        //自动评教的值AAAAAB
        $option = [
            '3BDB446C22734745AFFE9DF8AC722654',
            'BC0B807090BD4163977B56CE2157FEBF',
            'C313744F32C546548964DD5A2F48E575',
            '4C8C79FF13C74919A0414D0BA6BA6139',
            'B4575D7DA61E4B8E8F2B3A3E99403B69',
            '48D5096B044D4A6782BB77DF449CBDD5'
        ];
        if ($content !== '') {
            //存放ABCDE选项,并上面的数组置空
            $option = [];
            $length = strlen($content);
            for ($i = 0; $i < $length; $i++) {
                $option[$i] = substr($content, $i, 1);
            }
            //每个选项所对应的参数
            $evaluationOption = [
                [
                    'A' => '3BDB446C22734745AFFE9DF8AC722654',
                    'B' => '812774352B0549128025DA8562079B96',
                    'C' => '976AA592E1BE4850BE20BDEB0FC5BB96',
                    'D' => '0A30245095074435A1496514CAFE29B0',
                    'E' => '532F754404E849CE991CC742B94A01D8'
                ],
                [
                    'A' => 'BC0B807090BD4163977B56CE2157FEBF',
                    'B' => 'B4024F3797164CE496B246B7BF6F7183',
                    'C' => 'E7E3ECA4A9284FD39095D8F19EBC926A',
                    'D' => '1D9B4682AAB343BE93173FBF6B10CC16',
                    'E' => '02485DED68B6493D8E8E7824CDAB2F1D'
                ],
                [
                    'A' => 'C313744F32C546548964DD5A2F48E575',
                    'B' => '9610D10FA1C44E3D94561AA9555EBB98',
                    'C' => '5874E170C6F9421A874BC29A9EEF1E56',
                    'D' => 'D7D96BEB09AA4542973213683EB1B8A9',
                    'E' => '5A99BC309BFB4521BED2AFD87A0F5B24'
                ],
                [
                    'A' => '4C8C79FF13C74919A0414D0BA6BA6139',
                    'B' => '8B2BB56C177B4491A170F5C1D4BAAFCE',
                    'C' => '8190407039E8452E865FF08A745C2220',
                    'D' => '684D131D45724AB08A80C0D6861B85D9',
                    'E' => '58EC3F8972A9476C90CD8962886A8610'
                ],
                [
                    'A' => 'B4575D7DA61E4B8E8F2B3A3E99403B69',
                    'B' => '3CC022AC40E649F8A98EE18855987C58',
                    'C' => '184043CF6F794C6896AE616E1CD9D2EE',
                    'D' => '1FBAB4251AC44E4CA8A0A0F56CCB5652',
                    'E' => '024FC3355FD84C1F860065C739C0E053'
                ],
                [
                    'A' => '85E38F1470FB46B9B54DB4E5D509100B',
                    'B' => '48D5096B044D4A6782BB77DF449CBDD5',
                    'C' => '9D477A87A1E9480FA37B20B388E323FF',
                    'D' => '2CD50FB635474CC5BECC7B3E0BF40EC5',
                    'E' => 'CEEF7F71F76644F18B6E1C184EA69A42'
                ]
            ];
            //将option数组中存放的ABCDE，转换成对应的参数
            for ($i = 0; $i < $length; $i++) {
                $option[$i] = $evaluationOption[$i][$option[$i]];
            }
        }
        $url = "http://172.16.2.39" . $url;
        $oneTeacherPage = (new Login())->loginCurl($url, "", $cookie);
        //pj09id
        preg_match('/<input type="hidden" name="pj09id" value="([^<>\n]+)"/',
            $oneTeacherPage, $matches);
        $pj09id = $matches[1];
        //pj01id
        preg_match('/<input type="hidden" name="pj01id" value="([^<>\n]+)"/',
            $oneTeacherPage, $matches);
        $pj01id = $matches[1];
        //pj0502id
        preg_match('/<input type="hidden" name="pj0502id" value="([^<>\n]+)"/',
            $oneTeacherPage, $matches);
        $pj0502id = $matches[1];
        //jx0404id
        preg_match('/<input type="hidden" name="jx0404id" value="([^<>\n]+)"/',
            $oneTeacherPage, $matches);
        $jx0404id = $matches[1];
        //xnxq01id
        preg_match('/<input type="hidden" name="xnxq01id" value="([^<>\n]+)"/',
            $oneTeacherPage, $matches);
        $xnxq01id = $matches[1];
        //jx02id
        preg_match('/<input type="hidden" name="jx02id" value="([^<>\n]+)"/',
            $oneTeacherPage, $matches);
        $jx02id = $matches[1];
        //pj02id
        preg_match('/<input type="hidden" name="pj02id" value="([^<>\n]+)"/',
            $oneTeacherPage, $matches);
        $pj02id = $matches[1];
        //issubmit为0时保存，1时提交
        $post =
            'issubmit=' . '1' . '&' .
            'pj09id=' . $pj09id . '&' .
            'pj01id=' . $pj01id . '&' .
            'pj0502id=' . $pj0502id . '&' .
            'jg0101id=' . '' . '&' .
            'jx0404id=' . $jx0404id . '&' .
            'xsflid=' . '' . '&' .
            'xnxq01id=' . $xnxq01id . '&' .
            'jx02id=' . $jx02id . '&' .
            'pj02id=' . $pj02id . '&' .
            'pj06xh=' . '2' . '&' .
            'pj0601id_2=' . $option[0] . '&' .//这个参数必须，下面5个可有可无，后面同理
            'pj0601fz_2_3BDB446C22734745AFFE9DF8AC722654=' . '14.88' . '&' .
            'pj0601fz_2_812774352B0549128025DA8562079B96=' . '13.28' . '&' .
            'pj0601fz_2_976AA592E1BE4850BE20BDEB0FC5BB96=' . '11.68' . '&' .
            'pj0601fz_2_0A30245095074435A1496514CAFE29B0=' . '10.08' . '&' .
            'pj0601fz_2_532F754404E849CE991CC742B94A01D8=' . '8.48' . '&' .
            'pj06xh=' . '3' . '&' .
            'pj0601id_3=' . $option[1] . '&' .
            'pj0601fz_3_BC0B807090BD4163977B56CE2157FEBF=' . '15.81' . '&' .
            'pj0601fz_3_B4024F3797164CE496B246B7BF6F7183=' . '14.11' . '&' .
            'pj0601fz_3_E7E3ECA4A9284FD39095D8F19EBC926A=' . '12.41' . '&' .
            'pj0601fz_3_1D9B4682AAB343BE93173FBF6B10CC16=' . '10.71' . '&' .
            'pj0601fz_3_02485DED68B6493D8E8E7824CDAB2F1D=' . '9.01' . '&' .
            'pj06xh=' . '4' . '&' .
            'pj0601id_4=' . $option[2] . '&' .
            'pj0601fz_4_C313744F32C546548964DD5A2F48E575=' . '15.81' . '&' .
            'pj0601fz_4_9610D10FA1C44E3D94561AA9555EBB98=' . '14.11' . '&' .
            'pj0601fz_4_5874E170C6F9421A874BC29A9EEF1E56=' . '12.41' . '&' .
            'pj0601fz_4_D7D96BEB09AA4542973213683EB1B8A9=' . '10.71' . '&' .
            'pj0601fz_4_5A99BC309BFB4521BED2AFD87A0F5B24=' . '9.01' . '&' .
            'pj06xh=' . '5' . '&' .
            'pj0601id_5=' . $option[3] . '&' .
            'pj0601fz_5_4C8C79FF13C74919A0414D0BA6BA6139=' . '15.81' . '&' .
            'pj0601fz_5_8B2BB56C177B4491A170F5C1D4BAAFCE=' . '14.11' . '&' .
            'pj0601fz_5_8190407039E8452E865FF08A745C2220=' . '12.41' . '&' .
            'pj0601fz_5_684D131D45724AB08A80C0D6861B85D9=' . '10.71' . '&' .
            'pj0601fz_5_58EC3F8972A9476C90CD8962886A8610=' . '9.01' . '&' .
            'pj06xh=' . '1' . '&' .
            'pj0601id_1=' . $option[4] . '&' .
            'pj0601fz_1_B4575D7DA61E4B8E8F2B3A3E99403B69=' . '15.81' . '&' .
            'pj0601fz_1_3CC022AC40E649F8A98EE18855987C58=' . '14.11' . '&' .
            'pj0601fz_1_184043CF6F794C6896AE616E1CD9D2EE=' . '12.41' . '&' .
            'pj0601fz_1_1FBAB4251AC44E4CA8A0A0F56CCB5652=' . '10.71' . '&' .
            'pj0601fz_1_024FC3355FD84C1F860065C739C0E053=' . '9.01' . '&' .
            'pj06xh=' . '6' . '&' .
            'pj0601fz_6_85E38F1470FB46B9B54DB4E5D509100B=' . '14.88' . '&' .
            'pj0601id_6=' . $option[5] . '&' .
            'pj0601fz_6_48D5096B044D4A6782BB77DF449CBDD5=' . '13.28' . '&' .
            'pj0601fz_6_9D477A87A1E9480FA37B20B388E323FF=' . '11.68' . '&' .
            'pj0601fz_6_2CD50FB635474CC5BECC7B3E0BF40EC5=' . '10.08' . '&' .
            'pj0601fz_6_CEEF7F71F76644F18B6E1C184EA69A42=' . '8.48' . '&' .
            'isxtjg=' . '1';
        $url2 = 'http://172.16.2.39/jsxsd/xspj/xspj_save.do';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url2);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)
         AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100 Safari/537.36');
        curl_setopt($ch, CURLOPT_HEADER, 1);
        //不自动输出数据，要echo才行
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //重要，抓取跳转后数据
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_COOKIE, $cookie);
        //重要，302跳转需要referer，可以在Request Headers找到
        curl_setopt($ch, CURLOPT_REFERER, $url);
        //TRUE 时会发送 POST 请求，类型为：application/x-www-form-urlencoded，是 HTML 表单提交时最常见的一种
        curl_setopt($ch, CURLOPT_POST, 1);
        //post提交数据
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $result = curl_exec($ch);
        curl_close($ch);
        //保存失败即评教失败
        preg_match('/保存失败/',
            $result, $matches);
        if (!empty($matches)) {
            throw new EvaluationFailException();
        }
    }
}