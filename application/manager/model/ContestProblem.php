<?php
/**
 * Created by XThundering.
 * Author: XThundering
 * Date: 2018/5/16
 * Time: 下午8:57
 */

namespace app\manager\model;

use think\exception\DbException;

class ContestProblem extends BaseModel
{
    protected $pk = 'pid';
    protected $updateTime = false;
    protected $createTime = false;
    protected $deleteTime = false;

    public static function getContestID($pID)
    {
        try {
            $info = self::get($pID);
        } catch (DbException $e) {
            return null;
        }
        if ($info) {
            return $info['cid'];
        } else {
            return null;
        }
    }
}