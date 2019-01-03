<?php
/**
 * Created by PhpStorm.
 * User: CHUNLAI
 * Date: 2018/10/17
 * Time: 1:34
 */

namespace app\api\model;


use app\api\lib\Exception\OldPasswordException;
use app\api\lib\Exception\ServerException;
use think\Exception;

class ChangePassword
{
    /**
     * 修改密码函数将在Login类抛出密码过于简单异常，可以触发
     * @param $studentID
     * @param $oldPassword
     * @param $newPassword
     * @param $cookie
     * @return array
     * @throws OldPasswordException
     * @throws ServerException
     */
    public function changePassword($studentID, $oldPassword, $newPassword, $cookie)
    {
        $this->firstLogin($studentID, $oldPassword, $cookie);
        $result = $this->doChangePassword($oldPassword, $newPassword, $cookie);
        return $result;
    }


    /**
     * 首次登录
     * @param $studentID
     * @param $password
     * @param $cookie
     * @return bool
     * @throws OldPasswordException
     */
    public function firstLogin($studentID, $password, $cookie)
    {
        $url = "http://172.16.2.39/jsxsd/xk/LoginToXk";
        $encoded = $this->getEncoded($studentID, $password);
        $post = [
            'userAccount' => $studentID,
            'userPassword' => $password,
            'encoded' => $encoded
        ];
        //将数组转换成字符串
        $post = http_build_query($post);
        $result = $this->loginCurl($url, $post, $cookie);
        try {
            preg_match_all('/<font color="blue"><b>([^<>\n]+)/', $result, $msg);
            preg_match_all('/登录/', $result, $condition);
            //注意或逻辑运算两边位置不可调！用于判断原密码是否正确
            if (empty($condition[0]) || $msg[1][0] == "密码过于简单，请重新设置密码") {
                return true;
            }
        } catch (Exception $e) {
            //原密码错误
            throw new OldPasswordException();
        }
    }


    /**
     * 首次登录后修改密码
     * @param $oldPassword
     * @param $newPassword
     * @param $cookie
     * @return array
     * @throws ServerException
     */
    protected function doChangePassword($oldPassword, $newPassword, $cookie)
    {
        $url = "http://172.16.2.39/jsxsd/grsz/grsz_xgmm_beg.do";
        $post = [
            'id' => '',
            'oldpassword' => $oldPassword,
            'password1' => $newPassword,
            'password2' => $newPassword,
            'button1' => '%E4%BF%9D+%E5%AD%98',
            'upt' => 1
        ];
        $referer = 'http://172.16.2.39/jsxsd/grsz/grsz_xgmm_beg.do';
        $result = $this->loginCurl($url, $post, $cookie, $referer);
        if (empty($result)) {
            throw new ServerException();
        } else {
            $msg = [
                'code' => 200,
                'msg' => '密码修改成功'
            ];
            return $msg;
        }

    }


    public function loginCurl($url, $post, $cookie, $referer = 'http://172.16.2.39/jsxsd/default2.jsp')
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)
         AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100 Safari/537.36');
        curl_setopt($ch, CURLOPT_HEADER, 1);
        //不自动输出数据，要echo才行
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //重要，抓取跳转后数据
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_COOKIE, $cookie);
        //重要，302跳转需要referer，可以在Request Headers找到
        curl_setopt($ch, CURLOPT_REFERER, $referer);
        //TRUE 时会发送 POST 请求，类型为：application/x-www-form-urlencoded，是 HTML 表单提交时最常见的一种
        curl_setopt($ch, CURLOPT_POST, 1);
        //post提交数据
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }


    public function getEncoded($str1, $str2)
    {
        $enCoded = $this->encodeInp($str1) . "%%%" . $this->encodeInp($str2);
        return $enCoded;
    }


    /**
     * 加密函数
     * @param $plaintext
     * @return string
     */
    private function encodeInp($plaintext)
    {
        $keyStr = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
        $output = "";
        $i = 0;
        while ($i < strlen($plaintext)) {
            $chr1 = intval($this->charCodeAt(substr($plaintext, $i++, 1)));
            $chr2 = intval($this->charCodeAt(substr($plaintext, $i++, 1)));
            $chr3 = intval($this->charCodeAt(substr($plaintext, $i++, 1)));
            $enc1 = $chr1 >> 2;
            $enc2 = (($chr1 & 3) << 4) | ($chr2 >> 4);
            $enc3 = (($chr2 & 15) << 2) | ($chr3 >> 6);
            $enc4 = $chr3 & 63;
            if ($chr2 == -1 && $chr3 == -1) {
                $chr2 = 0;
                $chr3 = 0;
                $enc2 = (($chr1 & 3) << 4) | ($chr2 >> 4);
                $enc3 = $enc4 = 64;
            }
            if ($chr2 !== -1 && $chr3 == -1) {
                $chr3 = 0;
                $enc3 = (($chr2 & 15) << 2) | ($chr3 >> 6);
                $enc4 = 64;
            }
            $output = $output . substr($keyStr, $enc1, 1) . substr($keyStr, $enc2, 1)
                . substr($keyStr, $enc3, 1) . substr($keyStr, $enc4, 1);
            $chr1 = "";
            $chr2 = "";
            $chr3 = "";
            $enc1 = "";
            $enc2 = "";
            $enc3 = "";
            $enc4 = "";
        }
        return $output;
    }


    /**
     * 相当于js中的charCodeAt
     * @param $str
     * @return int|string
     */
    private function charCodeAt($str)
    {
        if ($str == "") {
            return -1;
        }
        $result = array();
        for ($i = 0, $l = mb_strlen($str, "utf-8"); $i < $l; ++$i) {
            $result[] = $this->changeCode(mb_substr($str, $i, 1, "utf-8"));
        }
        return join(",", $result);
    }

    private function changeCode($str)
    {
        $encode = mb_detect_encoding($str, array("ASCII", 'UTF-8', "GB2312", "GBK", 'BIG5'));
        if ($encode !== 'UTF-8') {
            $str = iconv($encode, 'UTF-8', $str);
        }
        if (strlen($str) == 1) {
            return ord($str);
        }
        $str = mb_convert_encoding($str, "UCS-4BE", "UTF-8");
        $tmp = unpack("N", $str);
        return $tmp[1];
    }
}