<?php
/**
 * Created by XThundering.
 * Author: XThundering
 * Date: 2018/2/10
 * Time: 17:25
 */

namespace app\user\validate;

class UserPasswordGet extends BaseValidate
{
    protected $rule = [
        'password' => 'require|min:8|max:20|alphaDash'
    ];

    protected $message = [
        'password.require' => 'Password must require',
        'password.min' => 'Password length must > 8',
        'password.max' => 'Password length must < 20',
        'password.alphaDash' => 'Password must be English character , num , \'_\' or \'-\''
    ];
}