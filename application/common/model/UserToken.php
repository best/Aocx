<?php
/**
 * Created by XThundering.
 * Author: XThundering
 * Date: 2018/2/12
 * Time: 15:12
 */

namespace app\common\model;

use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;

class UserToken extends BaseModel
{
    protected $updateTime = false;

    public static function getCountByUserID($userID)
    {
        //此处放宽要求，已经验证的token不算在次数中
        $count = (new UserToken)->where('userid', '=', $userID)
            ->whereTime('create_time', 'today')
            ->count();
        return $count;
    }

    /**
     * @param $token
     * @return array|null|\PDOStatement|string|\think\Model
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public static function getRegisterTokenStatus($token)
    {
        $tokenInfo = (new UserToken)->whereTime('create_time', '-3 days')
            ->where('type', '=', 'register')
            ->where('token', '=', $token)
            ->find();
        return $tokenInfo;
    }

    public static function getResetPassTokenStatus($token)
    {
        try {
            $tokenInfo = (new UserToken)->whereTime('create_time', '-30 minutes')
                ->where('type', '=', 'findpass')
                ->where('token', '=', $token)
                ->find();
        } catch (DataNotFoundException $e) {
            return false;
        } catch (ModelNotFoundException $e) {
            return false;
        } catch (DbException $e) {
            return false;
        }
        return $tokenInfo;
    }
}