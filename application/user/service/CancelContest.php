<?php
/**
 * Created by xthundering.
 * Author: xthundering
 * Date: 2018/4/2
 * Time: 下午7:21
 */

namespace app\user\service;

use app\common\model\Contest as ContestModel;
use app\common\model\UserContest as UserContestModel;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;

class CancelContest
{
    private $message = [
        'status' => false,
        'message' => '取消报名失败！'
    ];

    public function go($userID, $cID)
    {
        // 实现竞赛取消报名的功能
        // 首先对竞赛状态进行检测 检测竞赛是否处于报名状态 否 当前竞赛不在报名时间内！
        $ret = $this->statusValidate($cID);
        if (!$ret) {
            $this->message['message'] = '当前竞赛不在报名时间内！';
            return $this->message;
        }
        // 其次对用户报名竞赛信息进行检测 不存在 否 该竞赛未报名
        $ret = $this->applyValidate($userID, $cID);
        if (!$ret) {
            $this->message['message'] = '未参与报名该竞赛！';
            return $this->message;
        }
        // 删除竞赛信息 返回成功信息
        UserContestModel::cancelApply($userID, $cID);
        $this->message['status'] = true;
        $this->message['message'] = '取消报名成功！';
        return $this->message;
    }

    private function statusValidate($cID)
    {
        try {
            $contest = (new ContestModel())->where('id', $cID)->find();
        } catch (DataNotFoundException $e) {
            return false;
        } catch (ModelNotFoundException $e) {
            return false;
        } catch (DbException $e) {
            return false;
        }

        $contest = $contest->toArray();
        $contest_status = $contest['status'];
        if ($contest_status != '正在报名') {
            return false;
        } else {
            return true;
        }
    }

    private function applyValidate($userID, $cID)
    {
        $ret = UserContestModel::applyValidate($cID, $userID);
        if (!$ret) {
            return false;
        } else {
            return true;
        }
    }
}