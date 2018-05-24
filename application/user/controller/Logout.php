<?php
/**
 * Created by XThundering.
 * Author: XThundering
 * Date: 2018/2/14
 * Time: 20:59
 */

namespace app\user\controller;

use think\facade\Cookie;
use think\facade\Session;

class Logout extends BaseController
{
    public function index()
    {
        Session::clear();
        Cookie::clear();
        $this->success('退出登陆成功！', '/user/login');
    }
}