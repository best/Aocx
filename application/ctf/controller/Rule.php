<?php
/**
 * Created by xthundering.
 * Author: xthundering
 * Date: 2018/4/9
 * Time: 下午3:34
 */

namespace app\ctf\controller;

use app\common\model\Contest as ContestModel;
use app\ctf\validate\ContestNicknameGet;

class Rule
{
    public function index()
    {
        // TODO 竞赛规则 延后编写
        $validate = new ContestNicknameGet();
        $result = $validate->goCheck();
        if (!$result) {
            return $validate->getError();
        }

        $data = $validate->getDataByRule(input());
        $contest_nickname = $data['contest_nickname'];
        $contest = ContestModel::getContestByNickname($contest_nickname);
        return view('', ['contest' => $contest]);
    }
}