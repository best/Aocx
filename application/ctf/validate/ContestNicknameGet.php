<?php
/**
 * Created by xthundering.
 * Author: xthundering
 * Date: 2018/4/8
 * Time: 下午6:35
 */

namespace app\ctf\validate;

class ContestNicknameGet extends BaseValidate
{
    protected $rule = [
        'contest_nickname' => 'require|ContestNicknameValidate'
    ];

    protected $message = [
        'contest_nickname.require' => '竞赛信息不存在',
        'contest_nickname.ContestNicknameValidate' => '竞赛信息不存在',
    ];
}