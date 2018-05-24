<?php
/**
 * Created by xthundering.
 * Author: xthundering
 * Date: 2018/2/24
 * Time: 下午8:17
 */

namespace app\practice\validate;

class ProblemGet extends BaseValidate
{
    protected $rule = [
        'category' => 'require|CategoryValidate',
        'pID' => 'require|>=:10000'
    ];
}