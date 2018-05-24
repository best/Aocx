<?php
/**
 * Created by XThundering.
 * Author: XThundering
 * Date: 2018/2/10
 * Time: 17:35
 */

namespace app\user\validate;

class UserRegisterInfo extends BaseValidate
{
    protected $rule = [
        'username' => 'require|min:2|max:12|chsDash',
        'email' => 'require|email',
        'password' => 'require|min:8|max:20|alphaDash',
        'checkbox' => 'require'
    ];

    protected $message = [
        'username.require' => '用户名必须填写',
        'username.min' => '用户名长度必须大于2',
        'username.max' => '用户名长度必须小于12',
        'username.chsDash' => '用户名只能是汉字、字母、数字和下划线_及破折号-',
        'email.require' => '邮箱必须填写',
        'email.email' => '请填写正确的邮箱地址',
        'password.require' => '密码必须填写',
        'password.min' => '密码长度必须大于8',
        'password.max' => '密码长度必须小于20',
        'password.alphaDash' => '密码只能是字母和数字，下划线_及破折号-',
        'checkbox.require' => '请同意用户注册协议'
    ];
}