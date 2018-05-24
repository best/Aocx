<?php
/**
 * Created by XThundering.
 * Author: XThundering
 * Date: 2018/5/15
 * Time: 下午10:26
 */

namespace app\manager\controller;

use think\Controller;
use think\facade\Session;

class BaseController extends Controller
{
    public function loginCheck()
    {
        if (!Session::has('manager')) {
            $this->error('请先登陆管理后台～', '/manager/admin/login');
        }
    }
}