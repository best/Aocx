<?php
/**
 * Created by XThundering.
 * Author: XThundering
 * Date: 2018/2/13
 * Time: 15:49
 */

namespace app\user\service;

use app\common\model\User as UserModel;
use app\common\model\UserToken as UserTokenModel;
use app\common\service\Mail as MailService;
use app\common\service\Token as TokenService;

class MailResend
{
    protected $userInfo = [
        'username' => '',
        'link' => '',
        'email' => ''
    ];

    protected $successMessage = [
        'title' => '发送成功！',
        'text' => '请前往邮箱查看邮件!',
        'link' => '/user/mail/resend'
    ];

    protected $errorMessage = '邮件地址不存在';

    protected $message = [
        'status' => false,
        'message' => ''
    ];

    /**
     * @param $email
     * @return array
     * @throws \PHPMailer\PHPMailer\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function goSend($email)
    {
        $status = UserModel::getMailStatusByUserEmail($email);
        if ($status === false) {

            $this->message['message'] = $this->errorMessage;
            return $this->message;
        }

        if ($status === 1) {
            $this->successMessage['title'] = '此邮件地址已成功验证！';
            $this->successMessage['text'] = '请前往登陆页面直接登陆！';
            $this->successMessage['link'] = '/user/login';

            $this->message['status'] = true;
            $this->message['message'] = $this->successMessage;
            return $this->message;
        }

        if ($status === 0) {
            // 一天最多发送三封验证邮件
            $user = UserModel::getUserInfoByEmail($email);
            $count = UserTokenModel::getCountByUserID($user['userid']);
            if ($count >= 3) {
                $this->errorMessage = '一天最多重发三封账号验证邮件！';

                $this->message['message'] = $this->errorMessage;
                return $this->message;
            }
            // 生成token 发送邮件
            $token = new TokenService();
            $this->userInfo['email'] = $email;
            $this->userInfo['username'] = $user['username'];
            $this->userInfo['link'] = config('app.app_host') . '/verify?token=';
            $this->userInfo['link'] .= $token->generateToken('register', $user['userid']);
            $mail = new MailService;
            $result = $mail->sendRegisterMail($this->userInfo);
            if (!$result) {
                $this->errorMessage = '邮件发送失败，请稍后重试！';

                $this->message['message'] = $this->errorMessage;
                return $this->message;
            }

            $this->message['status'] = true;
            $this->message['message'] = $this->successMessage;
            return $this->message;
        }

        $this->message['message'] = $this->errorMessage;
        return $this->message;
    }
}