<?php
/**
 * Created by XThundering.
 * Author: XThundering
 * Date: 2018/2/8
 * Time: 17:20
 */

namespace app\user\controller;

use app\user\service\Login as LoginService;
use app\user\validate\UserLoginInfo;

class Login extends BaseController
{
    protected $beforeActionList = [
        'authCheck'
    ];

    public function index()
    {
        return view();
    }

    public function postCheck()
    {
        $validate = new UserLoginInfo();
        $result = $validate->goCheck();
        if (!$result) {
            return view('common/error', ['message' => $validate->getError()]);
        }

        $data = $validate->getDataByRule(input('post.'));
        $data['checkbox'] = input('post.checkbox');
        $message = (new LoginService())->goCheck($data);
        if ($message['status'] === 0) {
            return view('common/error', ['message' => '用户名或密码错误']);
        }

        if ($message['status'] === -1) {
            return view('mail');
        }

        return view('success');
    }
}