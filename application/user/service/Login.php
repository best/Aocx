<?php
/**
 * Created by XThundering.
 * Author: XThundering
 * Date: 2018/2/10
 * Time: 11:37
 */

namespace app\user\service;

use aes\AES;
use app\common\model\User as UserModel;
use app\common\model\UserLoginInfo as UserLoginInfoModel;
use app\common\model\UserTicket as UserTicketModel;
use app\common\service\Session as SessionService;
use app\common\service\Ticket as TicketService;
use think\facade\Cookie;
use think\facade\Session;

class Login
{
    protected $message = [
        'status' => 0,
        'message' => ''
    ];

    public function goCheck($data)
    {
        $userID = $this->userInfoCheck($data['username'], $data['password']);
        if (!$userID) {
            return $this->message;
        }

        UserLoginInfoModel::updateUserLoginInfo($userID);
        $this->storageTicket($data['checkbox'], $userID);

        return $this->message;
    }

    private function userInfoCheck($username = '', $oPassword = '')
    {
        $user = UserModel::getUserByLoginInfo($username);
        if (!$user) {
            $this->message['message'] = '用户名错误';
            return false;
        }

        $result = $this->userPassCheck($user, $oPassword);
        if (!$result) {
            $this->message['message'] = '密码错误';
            return false;
        }

        if ($user['status'] === 0) {
            $this->message['status'] = -1;
            $this->message['message'] = '邮件未验证！';
            return false;
        }

        $this->message['status'] = 1;
        return $user['userid'];
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

    private function storageTicket($checkbox = '', $userID = '')
    {
        $ticketService = new TicketService();
        $ticketInfo = $ticketService->generateTicket($userID);

        $userTicketInfo = UserTicketModel::get($userID);
        $userTicket = new UserTicketModel();
        if (!$userTicketInfo) {
            $userTicket->save($ticketInfo);
        } else {
            $userTicket->isUpdate(true)
                ->save($ticketInfo);
        }
        if ($checkbox) {
            Cookie::set('ticket', $ticketInfo['ticket'], ['expire' => 259200]);
        }
        Session::set('ticket', $ticketInfo['ticket']);
        //缓存必要session信息方便其他地方读取
        (new SessionService())->initSession();
    }
}