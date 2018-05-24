<?php
/**
 * Created by XThundering.
 * Author: XThundering
 * Date: 2018/5/15
 * Time: 下午3:58
 */

namespace app\common\model;

use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;

class ContestScore extends BaseModel
{
    protected $pk = ['userid', 'cid'];

    public static function updateScore($userID, $cID, $score)
    {
        try {
            $userScore = (new ContestScore)->where('userid', $userID)->where('cid', $cID)->find();
        } catch (DataNotFoundException $e) {
            $userScore = null;
        } catch (ModelNotFoundException $e) {
            $userScore = null;
        } catch (DbException $e) {
            $userScore = null;
        }
        if (!$userScore) {
            self::create([
                'userid' => $userID,
                'cid' => $cID,
                'score' => $score
            ]);
        } else {
            self::update(['userid' => $userID, 'cid' => $cID, 'score' => ($userScore['score'] + $score)]);
        }
    }

    // 获取某竞赛排名列表 姓名-答题数-分数
    public static function getRankList($cID)
    {
        try {
            $list = (new ContestScore)->where('cid', $cID)->order('score', 'desc')->select();
        } catch (DataNotFoundException $e) {
            $list = null;
        } catch (ModelNotFoundException $e) {
            $list = null;
        } catch (DbException $e) {
            $list = null;
        }
        $rank_list = [];
        for ($i = 0; $i < count($list); $i++) {
            $rank_list[$i]['name'] = (new UserExtraInfo)->where('userid', $list[$i]['userid'])->value('name');
            $rank_list[$i]['count'] = (new ContestCorrect)->where('userid', $list[$i]['userid'])->where('cid', $cID)->count();
            $rank_list[$i]['score'] = $list[$i]['score'];
        }
        return $rank_list;
    }

    public static function getScore($userID, $cID)
    {
        try {
            $userScore = (new ContestScore)->where('userid', $userID)->where('cid', $cID)->find();
        } catch (DataNotFoundException $e) {
            return 0;
        } catch (ModelNotFoundException $e) {
            return 0;
        } catch (DbException $e) {
            return 0;
        }
        if (!$userScore) {
            return 0;
        } else {
            return $userScore['score'];
        }
    }
}