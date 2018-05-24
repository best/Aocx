<?php
/**
 * Created by XThundering.
 * Author: XThundering
 * Date: 2018/5/15
 * Time: 上午9:54
 */

namespace app\ctf\validate;

class FlagPost extends BaseValidate
{
    protected $rule = [
        'contest_nickname' => 'require|ContestNicknameValidate',
        'pid' => 'require',
        'flag' => 'require'
    ];

    protected $message = [
        'contest_nickname.require' => '竞赛信息不存在',
        'contest_nickname.ContestNicknameValidate' => '竞赛信息不存在',
        'pid.require' => '提交信息不完善！',
        'flag.require' => '请填写Flag！'
    ];
}