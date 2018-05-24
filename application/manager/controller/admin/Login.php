<?php
/**
 * Created by XThundering.
 * Author: XThundering
 * Date: 2018/5/15
 * Time: 下午10:24
 */

namespace app\manager\controller\admin;

use app\manager\controller\BaseController;
use think\facade\Request;
use think\facade\Session;

class Login extends BaseController
{
    public function index()
    {
        if (Session::has('manager')) {
            $this->success('您已登陆～', '/manager');
        }

        if (Request::isPost()) {
            $username = Request::param('username');
            $password = Request::param('password');
            if ($username == config('manager.username') && $password == config('manager.password')) {
                Session::set('manager', 'true');
                $this->success('登陆成功～', '/manager');
            } else {
                $this->error('登陆失败！');
            }
        } else {
            return view();
        }
    }
}