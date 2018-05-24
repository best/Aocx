<?php
/**
 * Created by XThundering.
 * Author: XThundering
 * Date: 2018/5/12
 * Time: 上午10:13
 */

namespace app\ctf\controller;

use app\common\model\Contest as ContestModel;
use app\common\model\ContestNotice;
use app\ctf\validate\ContestNicknameGet;

class Notice extends BaseController
{
    protected $beforeActionList = [
        'loginCheck'
    ];

    public function index()
    {
        $validate = new ContestNicknameGet();
        $result = $validate->goCheck();
        if (!$result) {
            return $validate->getError();
        }

        $data = $validate->getDataByRule(input());
        $contest_nickname = $data['contest_nickname'];
        $contest = ContestModel::getContestByNickname($contest_nickname);
        // 验证用户是否报名
        $this->applyCheck($contest['id']);

        $notice_list = ContestNotice::getListByCID($contest['id']);
        return view('', ['contest' => $contest, 'notice_list' => $notice_list]);
    }
}