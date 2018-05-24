<?php
/**
 * Created by XThundering.
 * Author: XThundering
 * Date: 2018/5/8
 * Time: 下午4:51
 */

namespace app\ctf\controller;

use app\common\model\UserContest;
use app\common\service\Ticket as TicketService;
use think\Controller;

class BaseController extends Controller
{
    public function loginCheck()
    {
        $result = (new TicketService())->getTickVerify();
        if (!$result) {
            $this->error('您还未登陆，请先登陆！', '/user/login', '');
        }
    }

    public function applyCheck($cid)
    {
        $userID = (new TicketService())->getUserID();
        $ret = UserContest::applyValidate($cid, $userID);
        if (!$ret) {
            $this->error('您没有报名本次竞赛，无法进入比赛～', '/ctf');
        }
    }
}