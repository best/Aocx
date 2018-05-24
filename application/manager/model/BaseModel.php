<?php
/**
 * Created by XThundering.
 * Author: XThundering
 * Date: 2018/5/16
 * Time: 上午11:18
 */

namespace app\manager\model;

use think\Model;
use think\model\concern\SoftDelete;

class BaseModel extends Model
{
    protected $autoWriteTimestamp = 'datetime';

    use SoftDelete;

    protected $hidden = ['delete_time'];
}