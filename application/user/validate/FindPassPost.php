<?php
/**
 * Created by XThundering.
 * Author: XThundering
 * Date: 2018/2/15
 * Time: 10:59
 */

namespace app\user\validate;

class FindPassPost extends BaseValidate
{
    protected $rule = [
        'username' => 'require|min:2|max:12|chsDash',
        'email' => 'require|email'
    ];

    protected $message = [
        'username.require' => '用户名必须填写',
        'username.min' => '用户名长度必须大于2',
        'username.max' => '用户名长度必须小于12',
        'username.chsDash' => '用户名只能是汉字、字母、数字和下划线_及破折号-',
        'email.require' => '注册邮箱必须填写',
        'email.email' => '请填写正确的邮箱地址'
    ];
}