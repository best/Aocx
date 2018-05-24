<?php
/**
 * Created by xthundering.
 * Author: xthundering
 * Date: 2018/2/25
 * Time: 下午11:24
 */

namespace app\practice\service;

use app\common\model\PracticeCorrect as PracticeCorrectModel;
use app\common\model\PracticePost as PracticePostModel;
use app\common\model\PracticeScore as PracticeScoreModel;
use app\common\model\Problem as ProblemModel;
use app\common\model\ProblemAnswer as ProblemAnswerModel;
use app\common\model\ProblemExtraInfo as ProblemExtraInfoModel;
use app\common\service\Ticket as TicketService;

class Judge
{
    protected $message = [
        'status' => false,
        'title' => '',
        'message' => ''
    ];

    public function goCheck($category, $pID, $flag)
    {
        // 评分系统
        // 校验 是否登陆 否 返回false
        $userID = (new TicketService())->getUserID();
        if ($userID == null) {
            $this->message['title'] = '身份验证失败！';
            $this->message['message'] = '提交Flag之前请先登陆！';
            return $this->message;
        }
        // 检测 category pID 是否正确 否 返回false
        $problemInfo = ProblemModel::getProblem($category, $pID, 'practice');
        if ($problemInfo == null) {
            $this->message['title'] = '题目信息不存在！';
            $this->message['message'] = '请刷新页面再次尝试提交！';
            return $this->message;
        }
        // 校验 该题是否回答过了 是 返回false -- 此处需采用 status + message 形式区分错误类型
        $correctInfo = PracticeCorrectModel::getInfoByPID($userID, $pID);
        if (is_array($correctInfo)) {
            $this->message['title'] = '该题你已经GetFlag过了！';
            $this->message['message'] = '请不要重复答题哦～';
            return $this->message;
        }
        // 检查 该题是否有提交记录 否 更新 problem_extra_info 表中 participants 记录 和post_times一起更新
        $postTimes = PracticePostModel::countPostTimes($userID, $pID);
        // 添加 user_practice_post 记录
        $user_practice_post = new PracticePostModel;
        $user_practice_post->save([
            'userid' => $userID,
            'pid' => $pID,
            'answer' => $flag
        ]);
        // 更新 problem_extra_info 表中 post_times 记录
        if (!$postTimes) {
            (new ProblemExtraInfoModel)->where('pid', $pID)->inc('post_times')->inc('participants')->update();
        } else {
            (new ProblemExtraInfoModel)->where('pid', $pID)->inc('post_times')->update();
        }
        // 校验 Flag是否正确 否 返回false
        $info = ProblemAnswerModel::checkFlag($pID, $flag);
        if (!$info) {
            $this->message['title'] = 'Flag不正确！';
            $this->message['message'] = '请仔细思考一下再提交吧～';
            return $this->message;
        }
        // 添加 user_practice_correct 记录
        $user_practice_correct = new PracticeCorrectModel;
        $user_practice_correct->save([
            'userid' => $userID,
            'pid' => $pID,
            'score' => $problemInfo['score']
        ]);
        // 新增或更新 user_practice_score 数据表
        PracticeScoreModel::updateScore($userID, $problemInfo['score']);
        // 更新 problem_extra_info 表中 correct_times 记录
        (new ProblemExtraInfoModel)->where('pid', $pID)->inc('correct_times')->update();
        $this->message['status'] = true;
        return $this->message;
    }
}