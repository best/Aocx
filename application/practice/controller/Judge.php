<?php
/**
 * Created by xthundering.
 * Author: xthundering
 * Date: 2018/2/25
 * Time: 下午10:53
 */

namespace app\practice\controller;

use app\practice\service\Judge as JudgeService;
use app\practice\validate\FlagPost;

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
        $judge = (new JudgeService())->goCheck($data['category'], $data['pid'], $data['flag']);
        if (!$judge['status']) {
            return view('judgeFalse', ['title' => $judge['title'], 'message' => $judge['message']]);
        }
        return view('judgeSuccess');
    }
}