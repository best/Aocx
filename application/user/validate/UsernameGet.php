<?php
/**
 * Created by XThundering.
 * Author: XThundering
 * Date: 2018/2/10
 * Time: 12:39
 */

namespace app\user\validate;


class UsernameGet extends BaseValidate
{
    protected $rule = [
        'username' => 'require|min:2|max:12|chsDash'
    ];

    protected $message = [
        'username.require' => 'Username must require',
        'username.min' => 'Username length must > 2',
        'username.max' => 'Username length must < 12',
        'username.chsDash' => 'Username must be Chinese character, English character , num , \'_\' or \'-\''
    ];
}