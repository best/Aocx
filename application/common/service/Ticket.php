<?php
/**
 * Created by XThundering.
 * Author: XThundering
 * Date: 2018/2/9
 * Time: 20:27
 */

namespace app\common\service;

use aes\AES;
use app\common\model\UserExtraInfo as UserExtraInfoModel;
use app\common\model\UserTicket as UserTicketModel;
use app\common\service\Session as SessionService;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;

class Ticket
{
    public function getTickVerify()
    {
        $ticket = session('ticket');
        if (!$ticket) {
            $ticket = cookie('ticket');
            if (!$ticket) {
                return false;
            } else {
                try {
                    $result = (new UserTicketModel())->where('ticket', '=', $ticket)
                        ->where('ip', '=', $_SERVER["REMOTE_ADDR"])
                        ->find();
                } catch (DbException $e) {
                    return false;
                }
                if (!$result) {
                    return false;
                } else {
                    //重新缓存必要信息
                    session('ticket', $ticket);
                    (new SessionService())->initSession();
                    return true;
                }
            }
        }
        return true;
    }

    public function generateTicket($userID)
    {
        $key = AES::generateKey(32);
        $iv = AES::generateKey(16);
        $ticket = AES::encrypt(md5($userID), $key, $iv);
        $ticket = $ticket['ciphertext'];
        $ip = $_SERVER["REMOTE_ADDR"];

        return [
            'userid' => $userID,
            'key' => $key,
            'iv' => $iv,
            'ticket' => $ticket,
            'ip' => $ip
        ];
    }

    public function updateTicket($userID)
    {
        $ticketInfo = $this->generateTicket($userID);
        $userTicket = new UserTicketModel();
        $userTicket->save([
            'key' => $ticketInfo['key'],
            'iv' => $ticketInfo['iv'],
            'ticket' => $ticketInfo['ticket'],
            'ip' => $ticketInfo['ip']
        ], ['userid' => $userID]);
        return true;
    }

    public function getUserExtraInfo()
    {
        try {
            $ticketInfo = (new UserTicketModel())->where('ticket', '=', session('ticket'))->find();
            $userExtraInfo = (new UserExtraInfoModel())->where('userid', '=', $ticketInfo['userid'])->find();
        } catch (DataNotFoundException $e) {
            return null;
        } catch (ModelNotFoundException $e) {
            return null;
        } catch (DbException $e) {
            return null;
        }
        return $userExtraInfo;
    }

    public function getUserID()
    {
        try {
            $ticketInfo = (new UserTicketModel())->where('ticket', '=', session('ticket'))->find();
        } catch (DataNotFoundException $e) {
            return null;
        } catch (ModelNotFoundException $e) {
            return null;
        } catch (DbException $e) {
            return null;
        }
        return $ticketInfo['userid'];
    }
}