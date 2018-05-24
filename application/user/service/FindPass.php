<?php
/**
 * Created by XThundering.
 * Author: XThundering
 * Date: 2018/2/15
 * Time: 11:17
 */

namespace app\user\service;

use app\common\model\User as UserModel;
use app\common\model\UserToken as UserTokenModel;
use app\common\service\Mail as MailService;
use app\common\service\Token as TokenService;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;

class FindPass
{
    protected $message = [
        'status' => false,
        'message' => '验证失败，请检查输入'
    ];

    /**
     * @param $username
     * @param $email
     * @return array
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function send($username, $email)
    {
        // 验证用户信息 根据 where username status=1 or 0 find允许用户在注册之后还未验证邮箱地址的时候找回密码 否：用户名不存在
        // 验证邮箱地址 比对邮箱地址 否：验证邮箱地址错误
        // 验证token发送次数 根据userid where 日期在当天 count < 3 否：当天重发邮件次数已达上限
        // 都符合发送邮件 否：发送失败，稍后重试
        // 成功页面
        try {
            $user = (new UserModel)->where('username', '=', $username)
                ->where('status', 'in', [0, 1])
                ->find();
        } catch (DataNotFoundException $e) {
            return $this->message;
        } catch (ModelNotFoundException $e) {
            return $this->message;
        } catch (DbException $e) {
            return $this->message;
        }
        if ($user === null) {
            $this->message['message'] = '用户不存在！';
            return $this->message;
        }

        if (!($user['email'] === $email)) {
            $this->message['message'] = '验证注册邮箱地址错误！';
            return $this->message;
        }

        $count = UserTokenModel::getCountByUserID($user['userid']);
        if ($count === false || $count >= 3) {
            $this->message['message'] = '当天发送验证邮件次数已达上限！';
            return $this->message;
        }

        $token = new TokenService();
        $data['username'] = $user['username'];
        $data['email'] = $email;
        $data['link'] = config('app.app_host') . '/passwordReset?token=';
        $data['link'] .= $token->generateToken('findpass', $user['userid']);

        $mail = new MailService;
        $result = $mail->sendFindPassMail($data);
        if (!$result) {
            $this->message['message'] = '邮件发送失败，请稍后重试！';
            return $this->message;
        }

        $this->message['status'] = true;
        return $this->message;
    }
}