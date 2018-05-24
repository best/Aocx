<?php
/**
 * Created by XThundering.
 * Author: XThundering
 * Date: 2018/5/15
 * Time: 下午10:24
 */

namespace app\manager\controller\admin;

use app\manager\controller\BaseController;
use think\facade\Session;

class Logout extends BaseController
{
    public function index()
    {
        Session::delete('manager');
        $this->success('退出登陆成功！～', '/manager/admin/login');
    }
}