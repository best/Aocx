<?php
/**
 * Created by XThundering.
 * Author: XThundering
 * Date: 2018/2/9
 * Time: 0:15
 */

namespace app\user\validate;

class UserLoginInfo extends BaseValidate
{
    protected $rule = [
        'username' => 'require|min:2|max:45',
        'password' => 'require'
    ];

    protected $message = [
        'username.require' => '注册邮箱/用户名/UserID必须填写',
        'username.min' => '注册邮箱/用户名/UserID长度必须大于2',
        'username.max' => '注册邮箱/用户名/UserID长度必须小于45',
        'password' => '密码必须填写'
    ];
}