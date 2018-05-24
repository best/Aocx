<?php
/**
 * Created by XThundering.
 * Author: XThundering
 * Date: 2018/2/10
 * Time: 21:28
 */

namespace app\common\service;

use PHPAngular\Angular;
use PHPMailer\PHPMailer\PHPMailer;

class Mail
{
    /**
     * @param $address
     * @param $title
     * @param $message
     * @return bool
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function sendMail($address, $title, $message)
    {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->CharSet = 'UTF-8';
        $mail->SMTPAuth = true;
        $mail->isHTML(true);
        $mail->From = 'admin@mail.ahusec.cn';
        $mail->FromName = 'AHUSEC在线竞赛平台';

        $mail->Host = config('mail.host');
        $mail->Username = config('mail.username');
        $mail->Password = config('mail.password');
        $mail->addReplyTo(config('mail.replyto'));

        $mail->addAddress($address);
        $mail->Subject = $title;
        $mail->Body = $message;

        return $mail->Send();
    }

    /**
     * @param $data
     * @return bool
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function sendRegisterMail($data)
    {
        $title = '验证你的邮件地址';

        $config = [
            'debug' => true, // 是否开启调试, 开启调试会实时生成缓存
            'tpl_cache_path' => '../runtime/cache/', // 模板缓存目录
            'tpl_path' => '../application/common/template/' // 模板根目录
        ];
        $view = new Angular($config);
        $view->assign($data);
        $html = $view->fetch('verifyEmail');

        return $this->sendMail($data['email'], $title, $html);
    }

    /**
     * @param $data
     * @return bool
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function sendFindPassMail($data)
    {
        $title = '找回密码';

        $config = [
            'debug' => true, // 是否开启调试, 开启调试会实时生成缓存
            'tpl_cache_path' => '../runtime/cache/', // 模板缓存目录
            'tpl_path' => '../application/common/template/' // 模板根目录
        ];
        $view = new Angular($config);
        $view->assign($data);
        $html = $view->fetch('findPass');

        return $this->sendMail($data['email'], $title, $html);
    }
}