<?php
/**
 * Created by XThundering.
 * Author: XThundering
 * Date: 2018/2/19
 * Time: 12:31
 */

namespace app\common\model;

use app\common\service\Ticket;
use think\exception\DbException;

class UserExtraInfo extends BaseModel
{
    protected $pk = 'userid';

    public static function updateAvatar()
    {
        $userID = (new Ticket())->getUserID();
        $avatar = 'https://static.ahusec.cn/img/avatar/@' . $userID;
        try {
            $userExtraInfo = self::get($userID);
        } catch (DbException $e) {
            return;
        }
        if (!$userExtraInfo) {
            self::create([
                'userid' => $userID,
                'avatar' => $avatar
            ]);
        } else {
            $userExtraInfo->save([
                'avatar' => $avatar
            ], ['userid' => $userID]);
        }
        return;
    }

    public static function updateInfo($data)
    {
        $userID = (new Ticket())->getUserID();
        try {
            $userExtraInfo = self::get($userID);
        } catch (DbException $e) {
            return;
        }
        if (!$userExtraInfo) {
            self::create([
                'userid' => $userID,
                'name' => $data['name'],
                'xuehao' => $data['xuehao'],
                'site' => $data['site'],
                'bio' => $data['bio']
            ]);
        } else {
            $userExtraInfo->save([
                'name' => $data['name'],
                'xuehao' => $data['xuehao'],
                'site' => $data['site'],
                'bio' => $data['bio']
            ], ['userid' => $userID]);
        }
        return;
    }

    public function applyValidate($userID)
    {
        try {
            $userExtraInfo = self::get($userID);
        } catch (DbException $e) {
            return false;
        }
        if ($userExtraInfo['name'] && $userExtraInfo['xuehao']) {
            return true;
        } else {
            return false;
        }
    }
}