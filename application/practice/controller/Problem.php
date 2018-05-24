<?php
/**
 * Created by xthundering.
 * Author: xthundering
 * Date: 2018/2/24
 * Time: 下午8:08
 */

namespace app\practice\controller;

use app\common\model\PracticeCorrect as PracticeCorrectModel;
use app\common\model\Problem as ProblemModel;
use app\common\service\Ticket as TicketService;
use app\practice\validate\ProblemGet;

class Problem extends BaseController
{
    public function index()
    {
        $validate = new ProblemGet();
        $result = $validate->goCheck();
        if (!$result) {
            $this->error('题目信息不存在！');
        }
        $data = $validate->getDataByRule(input());
        $problem = ProblemModel::getProblem($data['category'], $data['pID'], 'practice');
        if (!is_array($problem)) {
            $this->error('题目信息不存在！');
        }
        $rank = PracticeCorrectModel::getTopByPID($problem['id'], 5);
        // 获取当前题目答题状态，如果Get Flag了，设置标记量 $status = 1
        $correctInfo = PracticeCorrectModel::getInfoByPID((new TicketService())->getUserID(), $problem['id']);
        if (is_array($correctInfo)) {
            $status = 1;
            return view('', ['problem' => $problem, 'rank' => $rank, 'status' => $status]);
        }
        return view('', ['problem' => $problem, 'rank' => $rank]);
    }
}