<?php
/**
 * Created by XThundering.
 * Author: XThundering
 * Date: 2018/5/16
 * Time: 下午6:26
 */

namespace app\manager\model;

use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;

class Contest extends BaseModel
{
    public function contestExtraInfo()
    {
        return $this->hasOne('ContestExtraInfo', 'cid', 'id')->bind(['organizer', 'place']);
    }

    public static function getContestList()
    {
        try {
            $contests = (new Contest)->with('contestExtraInfo')
                ->select();
        } catch (DataNotFoundException $e) {
            return null;
        } catch (ModelNotFoundException $e) {
            return null;
        } catch (DbException $e) {
            return null;
        }
        if (!$contests) {
            return null;
        }
        return $contests->toArray();
    }

    public static function getContest($cID)
    {
        try {
            $contest = (new Contest)->with('contestExtraInfo')
                ->where('id', $cID)
                ->find();
        } catch (DataNotFoundException $e) {
            return null;
        } catch (ModelNotFoundException $e) {
            return null;
        } catch (DbException $e) {
            return null;
        }
        if (!$contest) {
            return null;
        }
        return $contest->toArray();
    }
}