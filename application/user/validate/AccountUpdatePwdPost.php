<?php
/**
 * Created by xthundering.
 * Author: xthundering
 * Date: 2018/2/23
 * Time: 下午2:29
 */

namespace app\user\validate;

class AccountUpdatePwdPost extends BaseValidate
{
    protected $rule = [
        'oPassword' => 'require|min:8|max:20|alphaDash',
        'nPassword' => 'require|min:8|max:20|alphaDash'
    ];

    protected $message = [
        'oPassword.require' => '原密码必须填写',
        'oPassword.min' => '原密码长度错误！',
        'oPassword.max' => '原密码长度错误！',
        'oPassword.alphaDash' => '密码必须由英文字符，数字，下划线\'_\'或横线\'-\'构成',
        'nPassword.require' => '新密码必须填写！',
        'nPassword.min' => '新密码长度最小为八位！',
        'nPassword.max' => '新密码长度最长为20位',
        'nPassword.alphaDash' => '密码必须由英文字符，数字，下划线\'_\'或横线\'-\'构成'
    ];
}