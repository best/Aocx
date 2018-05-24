<?php
/**
 * Created by XThundering.
 * Author: XThundering
 * Date: 2018/5/15
 * Time: 上午9:55
 */

namespace app\ctf\service;

use app\common\model\Contest as ContestModel;
use app\common\model\ContestCorrect as ContestCorrectModel;
use app\common\model\ContestPost as ContestPostModel;
use app\common\model\ContestProblem as ContestProblemModel;
use app\common\model\ContestScore as ContestScoreModel;
use app\common\model\Problem as ProblemModel;
use app\common\model\ProblemAnswer as ProblemAnswerModel;
use app\common\service\Ticket as TicketService;

class Judge
{
    protected $message = [
        'status' => false,
        'title' => '',
        'message' => ''
    ];

    public function goCheck($pID, $cID, $flag)
    {
        // 评分系统
        // 校验 是否登陆 否 返回false
        $userID = (new TicketService())->getUserID();
        if ($userID == null) {
            $this->message['title'] = '身份验证失败！';
            $this->message['message'] = '提交Flag之前请先登陆！';
            return $this->message;
        }

        // 校验 是否是否在比赛时间段内 否 返回false
        $competition_status = ContestModel::competitionTimeValidate($cID);
        if (!$competition_status) {
            $this->message['title'] = '答题失败！';
            $this->message['message'] = '当前不在竞赛时间！';
            return $this->message;
        }

        // 校验cid-pid是否正确 否 返回false
        $contest_problem_status = ContestProblemModel::flagValidate($cID, $pID);
        if (!$contest_problem_status) {
            $this->message['title'] = '答题失败！';
            $this->message['message'] = '题目不在当前竞赛中！';
            return $this->message;
        }

        // 校验 该题是否回答过了 是 返回false
        $correctInfo = ContestCorrectModel::getInfoByPID($userID, $cID, $pID);
        if (is_array($correctInfo)) {
            $this->message['title'] = '该题你已经GetFlag过了！';
            $this->message['message'] = '请不要重复答题哦～';
            return $this->message;
        }

        // 添加 contest_post 记录
        $contest_post = new ContestPostModel();
        $contest_post->save([
            'userid' => $userID,
            'pid' => $pID,
            'cid' => $cID,
            'answer' => $flag
        ]);

        // 校验 Flag是否正确 否 返回false
        $info = ProblemAnswerModel::checkFlag($pID, $flag);
        if (!$info) {
            $this->message['title'] = 'Flag不正确！';
            $this->message['message'] = '请仔细思考一下再提交吧～';
            return $this->message;
        }

        // 添加 contest_correct 记录
        $problemInfo = ProblemModel::getCtfProblem($cID, $pID);
        $contest_correct = new ContestCorrectModel;
        $contest_correct->save([
            'userid' => $userID,
            'pid' => $pID,
            'cid' => $cID,
            'score' => $problemInfo['score']
        ]);

        // 新增或更新 contest_score 数据表
        ContestScoreModel::updateScore($userID, $cID, $problemInfo['score']);
        $this->message['status'] = true;
        return $this->message;
    }
}