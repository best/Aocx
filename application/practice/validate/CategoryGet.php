<?php
/**
 * Created by xthundering.
 * Author: xthundering
 * Date: 2018/2/27
 * Time: 下午2:18
 */

namespace app\practice\validate;

class CategoryGet extends BaseValidate
{
    protected $rule = [
        'category' => 'require|CategoryValidate'
    ];
}