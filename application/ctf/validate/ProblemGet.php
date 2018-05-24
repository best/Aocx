<?php
/**
 * Created by XThundering.
 * Author: XThundering
 * Date: 2018/5/12
 * Time: 上午10:20
 */

namespace app\ctf\validate;

class ProblemGet extends BaseValidate
{
    protected $rule = [
        'contest_nickname' => 'require|ContestNicknameValidate',
        'category' => 'require|CategoryValidate',
        'pID' => 'require|ProblemValidate'
    ];
}