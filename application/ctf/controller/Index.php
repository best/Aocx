<?php
/**
 * Created by XThundering.
 * Author: XThundering
 * Date: 2018/2/6
 * Time: 20:59
 */

namespace app\ctf\controller;

use app\common\model\Contest as ContestModel;
use app\common\model\UserContest as UserContestModel;

class Index
{
    public function index()
    {
        $contestList = ContestModel::getContestList();
        foreach ($contestList as $key => $value) {
            $contestList[$key]['enrollNum'] = UserContestModel::getEnrollNumByCID($value['id']);
        }
        return view('', ['contestList' => $contestList]);
    }
}