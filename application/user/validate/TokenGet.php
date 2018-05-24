<?php
/**
 * Created by XThundering.
 * Author: XThundering
 * Date: 2018/2/13
 * Time: 22:29
 */

namespace app\user\validate;

class TokenGet extends BaseValidate
{
    protected $rule = [
        'token' => 'require|length:16'
    ];

    protected $message = [
        'token.require' => 'token无效！',
        'token.length' => 'token无效！'
    ];
}