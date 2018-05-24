<?php
/**
 * Created by xthundering.
 * Author: xthundering
 * Date: 2018/2/26
 * Time: 下午1:58
 */

namespace app\common\model;

class PracticePost extends BaseModel
{
    protected $updateTime = false;

    public static function countPostTimes($userID, $pID)
    {
        $count = (new PracticePost)->where('userid', $userID)
            ->where('pid', $pID)
            ->count();
        return $count;
    }
}