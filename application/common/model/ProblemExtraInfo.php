<?php
/**
 * Created by xthundering.
 * Author: xthundering
 * Date: 2018/2/24
 * Time: 下午8:51
 */

namespace app\common\model;

class ProblemExtraInfo extends BaseModel
{
    protected $pk = 'pid';

    protected $hidden = ['pid', 'create_time', 'update_time', 'delete_time'];
}