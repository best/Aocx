<?php
/**
 * Created by XThundering.
 * Author: XThundering
 * Date: 2018/2/8
 * Time: 17:29
 */

namespace app\user\controller;

use app\common\model\UserContest as UserContestModel;
use app\common\service\Ticket as TicketService;
use app\user\service\CancelContest as CancelContestService;
use app\user\validate\ContestIDGet;

class Ctf extends BaseController
{
    protected $beforeActionList = [
        'loginCheck'
    ];

    /**
     * @return \think\response\View
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $userID = (new TicketService())->getUserID();
        $list = UserContestModel::getContestListByUserID($userID);
        return view('', ['contest_list' => $list]);
    }

    public function cancelContest()
    {
        $validate = new ContestIDGet();
        $result = $validate->goCheck();
        if (!$result) {
            $this->error($validate->getError(), '/user/ctf');
        }

        $data = $validate->getDataByRule(input());
        $userID = (new TicketService())->getUserID();
        $message = (new CancelContestService())->go($userID, $data['id']);
        if (!$message['status']) {
            $this->error($message['message'], '/user/ctf');
        }

        $this->success('竞赛取消报名成功！', '/user/ctf');
    }
}