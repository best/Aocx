<?php
/**
 * Created by XThundering.
 * Author: XThundering
 * Date: 2018/2/13
 * Time: 13:03
 */

namespace app\user\validate;

class MailResendInfo extends BaseValidate
{
    protected $rule = [
        'email' => 'require|email'
    ];

    protected $message = [
        'email.require' => '请填写注册邮箱！',
        'email.email' => '邮箱地址格式错误！'
    ];
}