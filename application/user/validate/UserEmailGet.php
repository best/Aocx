<?php
/**
 * Created by XThundering.
 * Author: XThundering
 * Date: 2018/2/10
 * Time: 17:06
 */

namespace app\user\validate;

class UserEmailGet extends BaseValidate
{
    protected $rule = [
        'email' => 'require|email'
    ];

    protected $message = [
        'email.require' => 'Email must require',
        'email.email' => 'Wrong Email Address'
    ];
}