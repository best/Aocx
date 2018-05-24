<?php
/**
 * Created by xthundering.
 * Author: xthundering
 * Date: 2018/2/23
 * Time: 下午2:43
 */

namespace app\user\service;

use aes\AES;
use app\common\model\User as UserModel;
use app\common\service\Ticket as TicketService;

class UpdatePass
{
    protected $info = [
        'status' => false,
        'message' => '验证失败！'
    ];

    public function goUpdate($oPassword, $nPassword)
    {
        $userID = (new TicketService())->getUserID();
        if (!$userID) {
            return $this->info;
        }
        // 验证原密码
        $userInfo = UserModel::getByUserid($userID);
        $result = $this->userPassCheck($userInfo, $oPassword);
        if (!$result) {
            $this->info['message'] = '原密码错误！';
            return $this->info;
        }
        // 更新现密码
        $user = new UserModel();
        $user->save([
            'password' => $this->encryptPassword($nPassword, $userID)
        ], ['userid' => $userID]);
        // 返回成功结果，此处不对用户进行踢出重新登录
        $this->info['status'] = true;
        $this->info['message'] = '密码更新成功！';
        return $this->info;
    }

    private function userPassCheck($user, $oPassword)
    {
        $sPassword = $user['password'];
        $key = md5($user['userid']);
        $iv = substr(md5($oPassword), 0, 16);
        $oPassword .= 'aocx_password_salt';
        $aes = new AES();
        $wPassword = $aes::encrypt($oPassword, $key, $iv);
        if ($sPassword === $wPassword['ciphertext']) {
            return true;
        } else {
            return false;
        }
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