<?php
/**
 * Created by xthundering.
 * Author: xthundering
 * Date: 2018/2/25
 * Time: 下午10:55
 */

namespace app\practice\validate;

class FlagPost extends BaseValidate
{
    protected $rule = [
        'category' => 'require',
        'pid' => 'require',
        'flag' => 'require'
    ];

    protected $message = [
        'category.require' => '提交信息不完善！',
        'pid.require' => '提交信息不完善！',
        'flag.require' => '请填写Flag！'
    ];
}