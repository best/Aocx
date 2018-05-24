<?php
/**
 * Created by XThundering.
 * Author: XThundering
 * Date: 2018/2/18
 * Time: 19:46
 */

namespace app\user\controller;

use app\common\model\User as UserModel;
use app\common\service\Session as SessionService;
use app\common\service\Ticket as TicketService;
use app\user\service\UpdatePass as UpdatePassService;
use app\user\validate\AccountUpdatePwdPost;
use app\user\validate\UsernameGet;

class Account extends BaseController
{
    protected $beforeActionList = [
        'loginCheck'
    ];

    public function index()
    {
        return view();
    }

    public function postUpdatePwd()
    {
        $validate = new AccountUpdatePwdPost();
        $result = $validate->goCheck();
        if (!$result) {
            return view('common/error', ['message' => $validate->getError()]);
        }
        $data = $validate->getDataByRule(input('post.'));
        $info = (new UpdatePassService())->goUpdate($data['oPassword'], $data['nPassword']);
        if (!$info['status']) {
            return view('common/error', ['message' => $info['message']]);
        }
        return view('success', ['message' => $info['message']]);
    }

    /**
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function postUpdateUsername()
    {
        $validate = new UsernameGet();
        $result = $validate->goCheck();
        if (!$result) {
            $this->error($validate->getError());
        }
        $data = $validate->getDataByRule(input('post.'));
        $result = UserModel::getStatusByUsername($data['username']);
        if ($result) {
            $this->error('该用户名已经存在！');
        }
        //更新用户名 删除session.username 更新session
        $user = new UserModel();
        $user->save([
            'username' => $data['username']
        ], ['userid' => (new TicketService())->getUserID()]);
        session('username', null);
        (new SessionService())->initSession();
        $this->success('用户名更改成功！', '/user/account');
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
}