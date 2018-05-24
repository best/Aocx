<?php
/**
 * Created by XThundering.
 * Author: XThundering
 * Date: 2018/5/15
 * Time: 下午3:44
 */

namespace app\common\model;

use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;

class ContestCorrect extends BaseModel
{
    protected $updateTime = false;

    public static function getInfoByPID($userID, $cID, $pID)
    {
        try {
            $info = (new ContestCorrect)->where('userid', $userID)
                ->where('cid', $cID)
                ->where('pid', $pID)
                ->find();
        } catch (DataNotFoundException $e) {
            return null;
        } catch (ModelNotFoundException $e) {
            return null;
        } catch (DbException $e) {
            return null;
        }
        if (!$info) {
            return null;
        }
        return $info->toArray();
    }
}