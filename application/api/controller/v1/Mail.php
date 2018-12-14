<?php
/**
 * Created by PhpStorm.
 * User: CHUNLAI
 * Date: 2018/11/3
 * Time: 20:32
 */

namespace app\api\controller\v1;


use app\api\lib\Exception\FailSendException;
use app\api\model\Mail as MailModel;
use think\Controller;

class Mail extends Controller
{
    public function email($recipients, $content){
        $mail = new MailModel();
        //发给申请者，申请消息已收到
        $result1 = $mail->sendEmail($recipients,"[申请已提交]", "<table style=\"width: 99.8%; height: 95%;\"><tbody><tr><td id=\"QQMAILSTATIONERY\" style=\"background:url(https://rescdn.qqmail.com/bizmail/zh_CN/htmledition/images/xinzhi/bg/a_04.jpg) repeat-x #cdede2; min-height:550px; padding: 100px 55px 200px \">亲爱的同学，你好：<br>&nbsp; &nbsp; &nbsp;&nbsp;<br>&nbsp; &nbsp; &nbsp; 我们已收到你的加入申请，我们将在一周内给予答复。如果还有其他疑问，请及时联系我们。树小萌QQ：2261344991，微信公众号：江西理工大学树人网。<br><br>谢谢！<br><br><div style=\"text-align: right;\"><span style=\"font-size: 14px;\">——</span><span style=\"font-size: 14px;\">&nbsp;</span>江西理工大学树人网工作室</div><br><br></td></tr></tbody></table>");
        //发给管理员，有新的申请
        $result2 = $mail->sendEmail('chunlai0928@foxmail.com', '新的申请提醒', $content);
        if ($result1==1 && $result2==1){
            return "发送成功";
        }else{
            throw new FailSendException();
        }
    }
}