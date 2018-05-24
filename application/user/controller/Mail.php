<?php
/**
 * Created by XThundering.
 * Author: XThundering
 * Date: 2018/2/11
 * Time: 20:26
 */

namespace app\user\controller;

use app\common\model\User;
use app\common\model\UserToken;
use app\user\service\MailResend as MailResendService;
use app\user\validate\MailResendInfo;
use app\user\validate\TokenGet;
use think\exception\DbException;

class Mail extends BaseController
{
    protected $beforeActionList = [
        'authCheck'
    ];

    public function resend()
    {
        return view();
    }

    public function resendCheck()
    {
        $validate = new MailResendInfo();
        $result = $validate->goCheck();
        if (!$result) {
            return view('common/error', ['message' => $validate->getError()]);
        }

        $data = $validate->getDataByRule(input('post.'));
        $message = (new MailResendService())->goSend($data['email']);
        if ($message['status'] === false) {
            return view('common/error', ['message' => $message['message']]);
        }

        return view('success', $message['message']);
    }

    public function registerValidate()
    {
        $validate = new TokenGet();
        $result = $validate->goCheck();
        if (!$result) {
            return view('registerError');
        }

        $data = $validate->getDataByRule(input('get.'));
        $result = $this->tokenValidate($data['token']);
        if (!$result) {
            return view('registerError');
        }

        return view('registerSuccess');
    }


    /**
     * @param $token
     * @return bool
     * @throws DbException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private function tokenValidate($token)
    {
        // token有效 or 失效 就两种状态
        // ！！！
        // 以下流程废除 不需要这么复杂 问题要简化思考
        // 先获取token信息（包含delete）                否：token无效 失败跳转
        // 根据token.userid判断用户是否已经验证过邮件了   是：你已经成功验证过注册邮箱了 成功跳转  ！！！此步移后
        // 此时状态：token存在，用户未验证 需要检验有效期
        // 如果create_time在三天之内->去完成验证         是：注册邮箱验证成功   ！！！此步提前
        // 否：token已经过期，请访问登陆页面登陆后重新发送验证邮件
        $tokenInfo = UserToken::getRegisterTokenStatus($token);
        if ($tokenInfo === null) {
            return false;
        }

        $mailStatus = User::getMailStatusByUserID($tokenInfo['userid']);
        if (!$mailStatus == 0) {
            return false;
        }

        try {
            UserToken::destroy($tokenInfo['id']);
            $user = (new User)->where('userid', $tokenInfo['userid'])
                ->find();
            $user->status = 1;
            $user->save();
        } catch (DbException $e) {
            return false;
        }

        return true;
    }
}