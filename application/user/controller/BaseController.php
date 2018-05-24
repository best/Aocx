<?php
/**
 * Created by XThundering.
 * Author: XThundering
 * Date: 2018/2/14
 * Time: 20:26
 */

namespace app\user\controller;

use app\common\service\Ticket as TicketService;
use think\Controller;

class BaseController extends Controller
{
    public function authCheck()
    {
        $result = (new TicketService())->getTickVerify();
        if ($result) {
            $this->success('您已成功登陆！', '/', '', 1);
        }
    }

    public function loginCheck()
    {
        $result = (new TicketService())->getTickVerify();
        if (!$result) {
            $this->error('您还未登陆，请先登陆！', '/user/login', '');
        }
    }
}