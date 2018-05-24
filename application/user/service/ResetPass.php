<?php
/**
 * Created by XThundering.
 * Author: XThundering
 * Date: 2018/2/16
 * Time: 21:27
 */

namespace app\user\service;

use aes\AES;
use app\common\model\User as UserModel;
use app\common\service\Ticket as TicketService;

class ResetPass
{
    public function reset($oPassword)
    {
        $tokenInfo = json_decode(session('tokenInfo'), true);
        if (!is_array($tokenInfo)) {
            return false;
        }
        $user = new UserModel();
        $user->save([
            'password' => $this->encryptPassword($oPassword, $tokenInfo['userid'])
        ], ['userid' => $tokenInfo['userid']]);
        // 更新ticket已剔除在线用户
        (new TicketService())->updateTicket($tokenInfo['userid']);
        // 清理session
        session('tokenInfo', null);
        session('resetPass', null);
        return true;
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