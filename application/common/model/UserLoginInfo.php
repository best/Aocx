<?php
/**
 * Created by XThundering.
 * Author: XThundering
 * Date: 2018/2/9
 * Time: 17:32
 */

namespace app\common\model;

class UserLoginInfo extends BaseModel
{
    protected $pk = 'userid';

    /**
     * @param $userID
     * @throws \think\exception\DbException
     */
    public static function updateUserLoginInfo($userID)
    {
        $user = self::get($userID);
        $user->login_times += 1;
        $user->save();
    }
}