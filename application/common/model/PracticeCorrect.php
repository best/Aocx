<?php
/**
 * Created by xthundering.
 * Author: xthundering
 * Date: 2018/2/26
 * Time: 下午1:09
 */

namespace app\common\model;

use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;

class PracticeCorrect extends BaseModel
{
    protected $updateTime = false;

    public static function getInfoByPID($userID, $pID)
    {
        try {
            $info = (new PracticeCorrect)->where('userid', $userID)
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

    public static function getTopByPID($pID, $top = 5)
    {
        try {
            $topList = (new PracticeCorrect)->where('pid', $pID)
                ->hidden(['id', 'pid', 'score'])
                ->order('create_time', 'asc')
                ->limit($top)
                ->select();
        } catch (DataNotFoundException $e) {
            return null;
        } catch (ModelNotFoundException $e) {
            return null;
        } catch (DbException $e) {
            return null;
        }
        // 根据userid获取username
        $rankList = $topList->toArray();
        for ($i = 0; $i < count($rankList); $i++) {
            $rankList[$i]['create_time'] = substr($rankList[$i]['create_time'], 0, 10);
            $rankList[$i]['username'] = User::getUsernameByUserID($rankList[$i]['userid']);
        }
        return $rankList;
    }

    public static function getListByUserID($userID)
    {
        try {
            $list = (new PracticeCorrect)->where('userid', $userID)
                ->order('create_time', 'asc')
                ->hidden(['id', 'userid'])
                ->select();
        } catch (DataNotFoundException $e) {
            return null;
        } catch (ModelNotFoundException $e) {
            return null;
        } catch (DbException $e) {
            return null;
        }
        $list = $list->toArray();
        for ($i = 0; $i < count($list); $i++) {
            $list[$i]['create_time'] = substr($list[$i]['create_time'], 0, 10);
            try {
                $problem_info = (new Problem)->where('id', $list[$i]['pid'])->find();
            } catch (DataNotFoundException $e) {
                return null;
            } catch (ModelNotFoundException $e) {
                return null;
            } catch (DbException $e) {
                return null;
            }
            $list[$i]['problem_title'] = $problem_info['title'];
            $list[$i]['problem_category'] = $problem_info['category'];
            $list[$i]['problem_level'] = $problem_info['level'];
        }
        return $list;
    }
}