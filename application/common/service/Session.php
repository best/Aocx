<?php
/**
 * Created by XThundering.
 * Author: XThundering
 * Date: 2018/2/18
 * Time: 16:08
 */

namespace app\common\service;

use app\common\model\User as UserModel;
use app\common\model\UserExtraInfo as UserExtraInfoModel;
use app\common\model\UserTicket as UserTicketModel;
use app\common\service\Ticket as TicketService;

class Session
{
    public function initSession()
    {
        // 需要保存到session中的有username,avatar
        $result = (new TicketService())->getTickVerify();
        if (!$result) {
            return;
        }
        $ticket = session('ticket');
        $ticketInfo = UserTicketModel::getByTicket($ticket);
        $userInfo = UserModel::getByUserid($ticketInfo['userid']);
        $userExtraInfo = UserExtraInfoModel::getByUserid($ticketInfo['userid']);
        // username
        if (!session('username')) {
            session('username', $userInfo['username']);
        }
        // avatar
        if ((!session('avatar')) && $userExtraInfo['avatar']) {
            session('avatar', $userExtraInfo['avatar']);
        }
        return;
    }
}