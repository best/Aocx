<?php
/**
 * Created by XThundering.
 * Author: XThundering
 * Date: 2018/5/15
 * Time: 上午9:52
 */

namespace app\ctf\controller;

use app\common\model\Contest as ContestModel;
use app\ctf\service\Judge as JudgeService;
use app\ctf\validate\FlagPost;

class Judge
{
    public function index()
    {
        if (!session('ticket')) {
            return view('error', ['message' => '提交Flag请先登陆！']);
        }

        $validate = new FlagPost();
        $result = $validate->goCheck();
        if (!$result) {
            return view('error', ['message' => $validate->getError()]);
        }

        $data = $validate->getDataByRule(input('post.'));
        $contest_nickname = $data['contest_nickname'];
        $contest = ContestModel::getContestByNickname($contest_nickname);
        $judge = (new JudgeService())->goCheck($data['pid'], $contest['id'], $data['flag']);
        if (!$judge['status']) {
            return view('judgeFalse', ['title' => $judge['title'], 'message' => $judge['message']]);
        }

        return view('judgeSuccess');
    }
}