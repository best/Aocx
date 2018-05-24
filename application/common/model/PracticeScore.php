<?php
/**
 * Created by xthundering.
 * Author: xthundering
 * Date: 2018/2/26
 * Time: 下午3:40
 */

namespace app\common\model;

class PracticeScore extends BaseModel
{
    protected $pk = 'userid';

    /**
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getRankList()
    {
        $rankList = (new PracticeScore)->order('score', 'desc')->select();
        $rankList = $rankList->toArray();
        for ($i = 0; $i < count($rankList); $i++) {
            $rankList[$i]['user_info'] = User::getUserInfoByUserID($rankList[$i]['userid']);
        }
        return $rankList;
    }

    /**
     * @param $userID
     * @param $score
     * @throws \think\exception\DbException
     */
    public static function updateScore($userID, $score)
    {
        $userScore = self::get($userID);
        if (!$userScore) {
            self::create([
                'userid' => $userID,
                'score' => $score
            ]);
        } else {
            self::update(['userid' => $userID, 'score' => ($userScore['score'] + $score)]);
        }
    }
}