<?php
/**
 * Created by XThundering.
 * Author: XThundering
 * Date: 2018/5/12
 * Time: 上午10:19
 */

namespace app\ctf\controller;

use app\common\model\Contest as ContestModel;
use app\common\model\ContestCorrect as ContestCorrectModel;
use app\common\model\ContestScore;
use app\common\model\Problem as ProblemModel;
use app\common\service\Ticket as TicketService;
use app\ctf\validate\ProblemGet;

class Problem extends BaseController
{
    public function index()
    {
        // TODO 验证是否在竞赛时间内
        $validate = new ProblemGet();
        $result = $validate->goCheck();
        if (!$result) {
            $this->error('题目信息不存在！');
        }

        $data = $validate->getDataByRule(input());
        $contest_nickname = $data['contest_nickname'];
        $contest = ContestModel::getContestByNickname($contest_nickname);
        $problem = ProblemModel::getCtfProblem($contest['id'], $data['pID']);
        if (!$problem) {
            $this->error('题目不存在于该竞赛中～');
        }

        $userID = (new TicketService())->getUserID();
        $correctInfo = ContestCorrectModel::getInfoByPID($userID, $contest['id'], $problem['id']);
        $score = ContestScore::getScore($userID, $contest['id']);
        if (is_array($correctInfo)) {
            $status = 1;
            return view('', ['contest' => $contest, 'problem' => $problem, 'status' => $status, 'score' => $score]);
        }
        return view('', ['contest' => $contest, 'problem' => $problem, 'score' => $score]);
    }
}