<?php
/**
 * Created by XThundering.
 * Author: XThundering
 * Date: 2018/2/16
 * Time: 20:41
 */

namespace app\user\controller;

use app\common\service\Token as TokenService;
use app\user\service\ResetPass as ResetPassService;
use app\user\validate\ResetPassPost;
use app\user\validate\TokenGet;

class Resetpass extends BaseController
{
    public function index()
    {
        $validate = new TokenGet();
        $result = $validate->goCheck();
        if (!$result) {
            $this->error($validate->getError() . '请重新发送请求！', '/user/findpass', '', 5);
        }

        $data = $validate->getDataByRule(input('get.'));
        $result = (new TokenService())->verifyResetPassToken($data['token']);
        if (!$result) {
            $this->error('验证链接已过期或无效！请重新发送请求！', '/user/findpass', '', 5);
        }

        return view();
    }

    public function postCheck()
    {
        // 先进行提交信息验证，符合进行session验证，随后完成密码重置
        $validate = new ResetPassPost();
        $result = $validate->goCheck();
        if (!$result) {
            return view('common/error', ['message' => $validate->getError()]);
        }

        $resetPass = session('resetPass');
        if (!$resetPass) {
            return view('common/error', ['message' => '验证链接已过期或无效！请重新发送请求！']);
        }

        $data = $validate->getDataByRule(input('post.'));
        $result = (new ResetPassService())->reset($data['password']);
        if (!$result) {
            return view('common/error', ['message' => '验证链接已过期或无效，请重新发送请求！']);
        }

        return view('success');
    }
}