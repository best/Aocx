<?php
/**
 * Created by xthundering.
 * Author: xthundering
 * Date: 2018/4/8
 * Time: 下午6:31
 */

namespace app\ctf\controller;

use app\common\model\Contest as ContestModel;
use app\ctf\validate\ContestNicknameGet;

class Contest extends BaseController
{
    public function index()
    {
        $validate = new ContestNicknameGet();
        $result = $validate->goCheck();
        if (!$result) {
            $this->error($validate->getError());
        }

        $data = $validate->getDataByRule(input());
        $contest_nickname = $data['contest_nickname'];
        $contest = ContestModel::getContestByNickname($contest_nickname);
        return $this->go($contest);
    }

    private function go($contest)
    {
        // 报名结束前显示报名界面，报名结束后-竞赛开始前显示倒计时界面，竞赛开始后显示竞赛题目页面,竞赛结束后显示分数页面 <- 需要再考虑优化
        $now = date('Y-m-d H:i:S');
        if ($now < $contest['enroll_start_time']) {
            $this->error('竞赛报名尚未开始～');
        } elseif ($now >= $contest['enroll_start_time'] && $now <= $contest['enroll_end_time']) {
            $this->success('竞赛正在报名阶段，即将跳转到报名页面～', '/user/apply');
        } elseif ($now >= $contest['start_time'] && $now <= $contest['end_time']) {
            return (new Process())->index($contest);
        } elseif ($now > $contest['enroll_end_time'] && $now < $contest['start_time']) {
            // 倒计时
            return (new Waiting())->index($contest);
        } else {
            return (new Rank())->index();
        }
    }
}