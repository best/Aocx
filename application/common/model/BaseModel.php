<?php
/**
 * Created by XThundering.
 * Author: XThundering
 * Date: 2018/2/10
 * Time: 10:07
 */

namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;

class BaseModel extends Model
{
    protected $autoWriteTimestamp = 'datetime';

    use SoftDelete;

    protected $hidden = ['delete_time'];
}