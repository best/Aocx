<?php
/**
 * Created by xthundering.
 * Author: xthundering
 * Date: 2018/2/24
 * Time: 下午3:33
 */

namespace app\common\model;

use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;

class Problem extends BaseModel
{
    public function problemExtraInfo()
    {
        return $this->hasOne('ProblemExtraInfo', 'pid', 'id');
    }

    // 仅用于获取题库的题目
    public static function getProblem($category, $pID, $type)
    {
        try {
            $problem = (new Problem)->with('problemExtraInfo')
                ->where('category', $category)
                ->where('id', $pID)
                ->where('type', $type)
                ->hidden(['create_time', 'update_time'])
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
        $problemList = $problem->toArray();
        // 采用session控制本次登陆期间每个题目访问量最多+1
        if (!session($pID)) {
            $problem->problemExtraInfo->views = $problemList['problem_extra_info']['views'] + 1;
            $problem->together('problemExtraInfo')->save();
            session($pID, 'true');
        }
        return $problemList;
    }

    // 获取竞赛题目
    public static function getCtfProblem($cid, $pid)
    {
        // 要验证 是否是这个比赛的题目
        try {
            $result = (new ContestProblem)->where('cid', $cid)->where('pid', $pid)->find();
        } catch (DataNotFoundException $e) {
            return false;
        } catch (ModelNotFoundException $e) {
            return false;
        } catch (DbException $e) {
            return false;
        }
        if ($result) {
            try {
                $problem = (new Problem)->where('id', $pid)->where('type', 'contest')
                    ->hidden(['create_time', 'update_time'])->find();
            } catch (DataNotFoundException $e) {
                return false;
            } catch (ModelNotFoundException $e) {
                return false;
            } catch (DbException $e) {
                return false;
            }
            if ($problem) {
                return $problem->toArray();
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}