<?php
/**
 * Created by XThundering.
 * Author: XThundering
 * Date: 2018/5/16
 * Time: 上午11:18
 */

namespace app\manager\model;

use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;

class Problem extends BaseModel
{
    public function problemExtraInfo()
    {
        return $this->hasOne('ProblemExtraInfo', 'pid', 'id')->bind('source');
    }

    public function problemAnswer()
    {
        return $this->hasOne('ProblemAnswer', 'pid', 'id')->bind('answer');
    }

    public static function getPracticeProblemList()
    {
        try {
            $problems = (new Problem)->with('problemExtraInfo')
                ->with('problemAnswer')
                ->where('type', '=', 'practice')
                ->select();
        } catch (DataNotFoundException $e) {
            return null;
        } catch (ModelNotFoundException $e) {
            return null;
        } catch (DbException $e) {
            return null;
        }
        if (!$problems) {
            return null;
        }
        return $problems->toArray();
    }

    public static function getPracticeProblem($pID)
    {
        try {
            $problem = (new Problem)->with('problemExtraInfo')
                ->with('problemAnswer')
                ->where('id', '=', $pID)
                ->where('type', '=', 'practice')
                ->find();
        } catch (DataNotFoundException $e) {
            return null;
        } catch (ModelNotFoundException $e) {
            return null;
        } catch (DbException $e) {
            return null;
        }
        if (!$problem) {
            return null;
        }
        return $problem->toArray();
    }

    public static function getContestProblemList()
    {
        try {
            $problems = (new Problem)->with('problemAnswer')
                ->where('type', '=', 'contest')
                ->select();
        } catch (DataNotFoundException $e) {
            return null;
        } catch (ModelNotFoundException $e) {
            return null;
        } catch (DbException $e) {
            return null;
        }
        if (!$problems) {
            return null;
        }
        return $problems->toArray();
    }

    public static function getContestProblem($pID)
    {
        try {
            $problem = (new Problem)->with('problemAnswer')
                ->where('id', '=', $pID)
                ->where('type', '=', 'contest')
                ->find();
        } catch (DataNotFoundException $e) {
            return null;
        } catch (ModelNotFoundException $e) {
            return null;
        } catch (DbException $e) {
            return null;
        }
        if (!$problem) {
            return null;
        }
        return $problem->toArray();
    }
}