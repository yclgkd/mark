<?php
/**
 * Created by PhpStorm.
 * User: CHUNLAI
 * Date: 2018/11/4
 * Time: 15:33
 */

namespace app\api\model;


use app\api\lib\Exception\FailSendException;
use PHPMailer\PHPMailer\PHPMailer;

class Mail
{
    /**
     * @param $recipients  收件人
     * @param $subject 主题
     * @param $content 内容
     * @return int 1：邮件发送成功
     * @throws FailSendException
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function sendEmail($recipients, $subject, $content)
    {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->CharSet = "utf8";
        //SMTP服务器地址
        $mail->Host = "smtp.exmail.qq.com";
        //是否使用身份验证
        $mail->SMTPAuth = true;
        //发信者邮箱
        $mail->Username = "kk@heifuture.com";
        $mail->Password = "9bGdGuL9";
        $mail->SMTPSecure = "ssl";
        $mail->Port = 465;
        //邮件正文是否为html编码
        $mail->isHTML(true);
        //设置发件人信息
        $mail->setFrom("kk@heifuture.com", "江西理工大学树人网工作室");
        $mail->addAddress($recipients, 'jxuster');
        //设置回复人信息
        $mail->addReplyTo("chunlai0928@foxmail.com", "Reply");
        // 邮件标题
        $mail->Subject = $subject;
        //邮件内容
        $mail->Body = $content;
        if (!$mail->send()) {
            //邮件发送失败
            throw new FailSendException(['msg'=>$mail->ErrorInfo]);
        } else {
            return 1;
        }
    }
}