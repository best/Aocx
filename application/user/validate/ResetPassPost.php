<?php
/**
 * Created by XThundering.
 * Author: XThundering
 * Date: 2018/2/17
 * Time: 14:18
 */

namespace app\user\validate;

class ResetPassPost extends BaseValidate
{
    protected $rule = [
        'password' => 'require|min:8|max:20|alphaDash'
    ];

    protected $message = [
        'password.require' => '请填写密码！',
        'password.min' => '密码长度最短为8位！',
        'password.max' => '密码长度最长为20位'
    ];
}