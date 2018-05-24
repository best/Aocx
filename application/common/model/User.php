<?php
/**
 * Created by XThundering.
 * Author: XThundering
 * Date: 2018/2/9
 * Time: 11:51
 */

namespace app\common\model;

class User extends BaseModel
{
    public static function getUserByLoginInfo($username)
    {
        $user = self::getByUserid($username);
        if ($user) {
            return $user;
        }

        $user = self::getByUsername($username);
        if ($user) {
            return $user;
        }

        $user = self::getByEmail($username);
        if ($user) {
            return $user;
        }

        return false;
    }

    public static function getUserInfoByEmail($email)
    {
        $user = self::getByEmail($email);
        if ($user) {
            return $user;
        }
        return false;
    }

    /**
     * @param $userID
     * @return bool
     * @throws \think\exception\DbException
     */
    public static function getUserInfoByUserID($userID)
    {
        $user = self::getByUserid($userID);
        if ($user) {
            $user = $user->toArray();
            $userExtraInfo = UserExtraInfo::get($userID);
            if (!$userExtraInfo) {
                $user['user_extra_info'] = null;
            } else {
                $user['user_extra_info'] = $userExtraInfo->toArray();
            }
            return $user;
        }
        return false;
    }

    /**
     * @param $username
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getStatusByUsername($username)
    {
        $result = (new User)->where('username', '=', $username)->find();
        if (!$result) {
            return false;
        }
        return true;
    }

    /**
     * @param $email
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getStatusByUserEmail($email)
    {
        $result = (new User)->where('email', '=', $email)->find();
        if (!$result) {
            return false;
        }
        return true;
    }

    /**
     * @param $email
     * @return bool|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getMailStatusByUserEmail($email)
    {
        $result = (new User)->where('email', '=', $email)->find();
        if (!$result) {
            return false;
        }
        return $result['status'];
    }

    /**
     * @param $userID
     * @return bool|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getMailStatusByUserID($userID)
    {
        $result = (new User)->where('userid', '=', $userID)->find();
        if (!$result) {
            return false;
        }
        return $result['status'];
    }

    public static function getUsernameByUserID($userID)
    {
        $user = self::getByUserid($userID);
        if (!$user) {
            return null;
        }
        return $user['username'];
    }
}