<?php
/**
 * Created by XThundering.
 * Author: XThundering
 * Date: 2018/2/12
 * Time: 14:56
 */

namespace app\common\service;

use app\common\model\UserToken as UserTokenModel;

class Token
{
    protected $token = [
        'userid' => 0,
        'token' => '',
        'type' => '',
        'status' => 0
    ];

    public function generateToken($type, $userID)
    {
        $this->token['userid'] = $userID;
        $this->token['token'] = substr(md5($userID . time()), 8, 16);
        $this->token['type'] = $type;
        UserTokenModel::create($this->token);

        return $this->token['token'];
    }

    public function verifyResetPassToken($token)
    {
        $tokenInfo = UserTokenModel::getResetPassTokenStatus($token);
        if ($tokenInfo === false || $tokenInfo === null) {
            return false;
        }
        UserTokenModel::destroy($tokenInfo['id']);
        session('resetPass', 1);
        session('tokenInfo', json_encode($tokenInfo));
        return true;
    }
}