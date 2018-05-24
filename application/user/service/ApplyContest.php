<?php
/**
 * Created by xthundering.
 * Author: xthundering
 * Date: 2018/3/19
 * Time: 下午4:15
 */

namespace app\user\service;

use app\common\model\Contest;
use app\common\model\UserContest;
use app\common\model\UserExtraInfo;
use app\common\service\Ticket as TicketService;

class ApplyContest
{
    private $message = [
        'status' => false,
        'message' => '报名失败！请重试～'
    ];

    // 竞赛报名处理方法
    public function go($cID = null)
    {
        $userID = (new TicketService())->getUserID();
        // 检测用户是否已经填写了姓名和学号 否 返回错误
        if (!$this->userValidate($userID)) {
            $this->message['message'] = '请先完善个人姓名和学号再报名～';
            return $this->message;
        }
        // 检测用户是否已经报名了 是 返回错误
        if (!$this->statusValidate($cID, $userID)) {
            $this->message['message'] = '你已经报名过了，请不要重复报名！';
            return $this->message;
        }
        // 检测比赛是否在报名时间内 否 返回错误
        if (!$this->timeValidate($cID)) {
            $this->message['message'] = '当前不在报名时间内！';
            return $this->message;
        }
        // 实现报名 返回正确消息
        $this->apply($cID, $userID);
        $this->message['status'] = true;
        $this->message['message'] = '报名成功！';
        return $this->message;
    }

    private function userValidate($userID)
    {
        if (!(new UserExtraInfo())->applyValidate($userID)) {
            return false;
        } else {
            return true;
        }
    }

    private function statusValidate($cID, $userID)
    {
        if (UserContest::applyValidate($cID, $userID)) {
            return false;
        } else {
            return true;
        }
    }

    private function timeValidate($cID)
    {
        if (!Contest::applyTimeValidate($cID)) {
            return false;
        } else {
            return true;
        }
    }

    private function apply($cID, $userID)
    {
        UserContest::create([
            'userid' => $userID,
            'cid' => $cID
        ]);
    }
}