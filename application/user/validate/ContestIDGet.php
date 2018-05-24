<?php
/**
 * Created by xthundering.
 * Author: xthundering
 * Date: 2018/3/19
 * Time: 下午4:02
 */

namespace app\user\validate;

class ContestIDGet extends BaseValidate
{
    protected $rule = [
        'id' => 'require|integer'
    ];

    protected $message = [
        'id.require' => '参数错误',
        'id.integer' => '参数错误'
    ];
}