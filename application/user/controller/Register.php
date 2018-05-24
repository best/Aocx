<?php
/**
 * Created by XThundering.
 * Author: XThundering
 * Date: 2018/2/8
 * Time: 17:21
 */

namespace app\user\controller;

use app\common\model\User as UserModel;
use app\user\service\Register as RegisterService;
use app\user\validate\UserEmailGet;
use app\user\validate\UsernameGet;
use app\user\validate\UserPasswordGet;
use app\user\validate\UserRegisterInfo;

class Register extends BaseController
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
        $validate = new UserRegisterInfo();
        $result = $validate->goCheck();
        if (!$result) {
            return view('common/error', ['message' => $validate->getError()]);
        }

        $data = $validate->getDataByRule(input('post.'));
        $message = (new RegisterService())->goCheck($data);
        if (!$message['status']) {
            return view('common/error', ['message' => $message['message']]);
        }

        return view('success');
    }

    /**
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getUsername()
    {
        $validate = new UsernameGet();
        $result = $validate->goCheck();
        if (!$result) {
            Header("HTTP/1.1 400 " . $validate->getError());
            return json($validate->getError(), 400);
        }

        $data = $validate->getDataByRule(input('get.'));
        $result = UserModel::getStatusByUsername($data['username']);
        if ($result) {
            Header("HTTP/1.1 409 Username Exists");
            return json('用户名:' . $data['username'] . '已经存在', 409);
        }

        return json('success', 200);
    }

    /**
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getEmail()
    {
        $validate = new UserEmailGet();
        $result = $validate->goCheck();
        if (!$result) {
            Header("HTTP/1.1 400 " . $validate->getError());
            return json($validate->getError(), 400);
        }

        $data = $validate->getDataByRule(input('get.'));
        $result = UserModel::getStatusByUserEmail($data['email']);
        if ($result) {
            Header("HTTP/1.1 409 Email : " . $data['email'] . " Exists");
            return json('邮箱:' . $data['email'] . '已经存在', 409);
        }

        return json('success', 200);
    }

    public function getPassword()
    {
        $validate = new UserPasswordGet();
        $result = $validate->goCheck();
        if (!$result) {
            Header("HTTP/1.1 400 " . $validate->getError());
            return json($validate->getError(), 400);
        }

        return json('success', 200);
    }
}