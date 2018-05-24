<?php
/**
 * Created by XThundering.
 * Author: XThundering
 * Date: 2018/2/12
 * Time: 21:00
 */

namespace app\user\service;

use aes\AES;
use app\common\model\DistributeUid as DistributeUidModel;
use app\common\model\User as UserModel;
use app\common\model\UserLoginInfo as UserLoginInfoModel;
use app\common\service\Mail as MailService;
use app\common\service\Token as TokenService;

class Register
{
    protected $user = [
        'userid' => 0,
        'username' => '',
        'email' => '',
        'password' => ''
    ];

    protected $message = [
        'status' => false,
        'message' => ''
    ];

    /**
     * @param $data
     * @return array
     * @throws \PHPMailer\PHPMailer\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function goCheck($data)
    {
        $result = $this->verifyUserInfo($data);
        if (!$result) {
            $this->message['message'] = '用户名或邮箱已被注册！';
            return $this->message;
        }

        $this->prepareRegister($data);
        $result = UserModel::create($this->user);
        if (!$result) {
            $this->message['message'] = '用户创建失败，请稍后重试！';
            return $this->message;
        }

        UserLoginInfoModel::create([
            'userid' => $this->user['userid'],
            'login_times' => 0
        ]);

        $token = new TokenService();
        $this->user['link'] = config('app.app_host') . '/verify?token=';
        $this->user['link'] .= $token->generateToken('register', $this->user['userid']);
        $mail = new MailService;
        $result = $mail->sendRegisterMail($this->user);
        if (!$result) {
            $this->message['message'] = '邮件发送失败，请登陆后重新发送邮件！';
            return $this->message;
        }

        $this->message['status'] = true;
        return $this->message;
    }

    /**
     * @param $data
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function verifyUserInfo($data)
    {
        $usernameStatus = UserModel::getStatusByUsername($data['username']);
        $emailStatus = UserModel::getStatusByUserEmail($data['email']);
        if ($usernameStatus || $emailStatus) {
            return false;
        }
        return true;
    }

    /**
     * @param $data
     * @throws \think\exception\DbException
     */
    private function prepareRegister($data)
    {
        $this->user['userid'] = $this->distributeUserID();
        $this->user['username'] = $data['username'];
        $this->user['password'] = $this->encryptPassword($data['password'], $this->user['userid']);
        $this->user['email'] = $data['email'];
    }

    /**
     * @return mixed
     * @throws \think\exception\DbException
     */
    private function distributeUserID()
    {
        $oUID = rand(100000, 999999);
        $UID = '';
        while (!$UID) {
            $UID = DistributeUidModel::get($oUID);
        }
        $UID->delete();
        return $UID['id'];
    }

    private function encryptPassword($oPassword, $userID)
    {
        $key = md5($userID);
        $iv = substr(md5($oPassword), 0, 16);
        $oPassword .= 'aocx_password_salt';
        $aes = new AES();
        $sPassword = $aes::encrypt($oPassword, $key, $iv);
        $sPassword = $sPassword['ciphertext'];

        return $sPassword;
    }
}