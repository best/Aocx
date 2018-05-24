<?php
/**
 * Created by xthundering.
 * Author: xthundering
 * Date: 2018/3/10
 * Time: 下午9:24
 */

namespace app\common\model;

use think\Db;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;

class Contest extends BaseModel
{
    public function problems()
    {
        return $this->belongsToMany('Problem', '\\app\\common\\model\\ContestProblem', 'pid', 'cid');
    }

    public static function getProblems($cid)
    {
//        $contest = self::get($cid);
//        $problems = $contest->problems();
//        $problem_list = [];
//        foreach ($problems as $problem){
//            $problem_list[] = $problem;
//        }
//        return $problem_list;
        // 可以采用TP的多对多关联查询，此处采用原生SQL语句方式查询
        // select pid from contest_problem where `cid` = '"{$cid}"';
        // select pid from contest_problem where `cid` = '1';
        // select * from problem where id in (select pid from contest_problem where `cid` = '1');
        $problems = Db::query("select `id`,`title`,`content`,`url`,`score`,`level`,`category`,`type`
                            from problem 
                            where id in (select pid from contest_problem where `cid` = '" . $cid . "')
                            and `delete_time` is NULL"
        );
        return $problems;
    }

    public function contestExtraInfo()
    {
        return $this->hasOne('ContestExtraInfo', 'cid')->bind([
            'organizer',
            'place'
        ]);
    }

    private $status;

    public function getIdAttr($value)
    {
        $contest = self::get($value);
        if (strtotime('now') <= strtotime($contest['enroll_start_time'])) {
            $this->status = $contest->status = 0;
            $contest->save();
        } elseif (strtotime('now') > strtotime($contest['enroll_start_time']) && strtotime('now') <= strtotime($contest['enroll_end_time'])) {
            $this->status = $contest->status = 1;
            $contest->save();
        } elseif (strtotime('now') > strtotime($contest['enroll_end_time']) && strtotime('now') <= strtotime($contest['start_time'])) {
            $this->status = $contest->status = 2;
            $contest->save();
        } elseif (strtotime('now') > strtotime($contest['start_time']) && strtotime('now') <= strtotime($contest['end_time'])) {
            $this->status = $contest->status = 3;
            $contest->save();
        } elseif (strtotime('now') > strtotime($contest['end_time'])) {
            $this->status = $contest->status = 4;
            $contest->save();
        }
        return $value;
    }

    public function getTypeAttr($value)
    {
        $type = ['individual' => '个人赛', 'team' => '团队赛'];
        return $type[$value];
    }

    public function getStatusAttr()
    {
        $value = $this->status;
        $status = ['0' => '未开始报名', '1' => '正在报名', '2' => '即将比赛', '3' => '正在比赛', '4' => '比赛结束'];
        return $status[$value];
    }

    public static function getContestList()
    {
        try {
            $contest_list = (new Contest)->with('contestExtraInfo')
                ->order('start_time', 'desc')
                ->hidden(['create_time', 'update_time'])
                ->select();
        } catch (DataNotFoundException $e) {
            return null;
        } catch (ModelNotFoundException $e) {
            return null;
        } catch (DbException $e) {
            return null;
        }
        return $contest_list->toArray();
    }

    public static function getContestByNickname($nickname)
    {
        try {
            $contest = (new Contest)->with('contestExtraInfo')
                ->where('nickname', $nickname)
                ->hidden(['create_time', 'update_time'])
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
        } else {
            return $contest->toArray();
        }
    }

    public static function getEnrollContestList()
    {
        try {
            $contest_list = (new Contest)->with('contestExtraInfo')
                ->whereTime('enroll_start_time', '<', date('Y-m-d H:i:s'))
                ->whereTime('enroll_end_time', '>', date('Y-m-d H:i:s'))
                ->hidden(['create_time', 'update_time'])
                ->select();
        } catch (DataNotFoundException $e) {
            return null;
        } catch (ModelNotFoundException $e) {
            return null;
        } catch (DbException $e) {
            return null;
        }
        return $contest_list->toArray();
    }

    public static function applyTimeValidate($cID)
    {
        try {
            $contestInfo = self::get($cID);
        } catch (DbException $e) {
            return false;
        }
        if (!$contestInfo) {
            return false;
        }
        if (strtotime($contestInfo['enroll_start_time']) <= strtotime('now') &&
            strtotime('now') <= strtotime($contestInfo['enroll_end_time'])) {
            return true;
        } else {
            return false;
        }
    }

    public static function competitionTimeValidate($cID)
    {
        try {
            $contestInfo = self::get($cID);
        } catch (DbException $e) {
            return false;
        }
        if (!$contestInfo) {
            return false;
        }
        if (strtotime($contestInfo['start_time']) <= strtotime('now') &&
            strtotime('now') <= strtotime($contestInfo['end_time'])) {
            return true;
        } else {
            return false;
        }
    }

    public static function contestNicknameValidate($nickname)
    {
        try {
            $contest = (new Contest)->where('nickname', $nickname)->find();
        } catch (DataNotFoundException $e) {
            return false;
        } catch (ModelNotFoundException $e) {
            return false;
        } catch (DbException $e) {
            return false;
        }
        if (!$contest) {
            return false;
        } else {
            return true;
        }
    }
}