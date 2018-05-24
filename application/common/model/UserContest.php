<?php
/**
 * Created by xthundering.
 * Author: xthundering
 * Date: 2018/3/11
 * Time: 下午9:27
 */

namespace app\common\model;

use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;

class UserContest extends BaseModel
{
    protected $updateTime = false;

    public static function getEnrollNumByCID($cID)
    {
        $enrollNum = (new UserContest)->where('cid', $cID)->count();
        return $enrollNum;
    }

    public static function applyValidate($cID, $userID)
    {
        $ret = (new UserContest)->where('cid', $cID)
            ->where('userid', $userID)
            ->count();
        return $ret;
    }

    /**
     * @param $userID
     * @return array|null|\PDOStatement|string|\think\Collection
     * @throws DbException
     */
    public static function getContestListByUserID($userID)
    {
        try {
            $list = (new UserContest)->where('userid', $userID)->select();
        } catch (DataNotFoundException $e) {
            return null;
        } catch (ModelNotFoundException $e) {
            return null;
        } catch (DbException $e) {
            return null;
        }

        $list = $list->toArray();
        for ($i = 0; $i < count($list); $i++) {
            $list[$i]['contest'] = Contest::get($list[$i]['cid'])->toArray();
        }
        return $list;
    }

    public static function cancelApply($userID, $cID)
    {
        // 这里由于user_contest表无主键加上采用了软删除操作的删除逻辑未检测更新的delete_time是否为空
        // 导致正常的删除语法会更新同一用户报名同一竞赛的所有记录的delete_time时间
        // 因此需要字节实现软删除操作，后期可以再采用关联更新的方式进行删除
//        $user_contest = self::where('userid', $userID)->where('cid', $cID)->find();
//        $user_contest->delete_time = date('Y-m-d H:i:s');
//        $user_contest->save();
//        $user_contest->delete();
        $user_contest = new UserContest();
        $user_contest->save([
            'delete_time' => date('Y-m-d H:i:s')
        ], ['userid' => $userID, 'cid' => $cID, 'delete_time' => null]);
    }
}