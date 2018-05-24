<?php
/**
 * Created by XThundering.
 * Author: XThundering
 * Date: 2018/2/6
 * Time: 21:01
 */

namespace app\rank\controller;

use app\common\model\PracticeScore as PracticeScoreModel;

class Index
{
    /**
     * @return \think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $rankList = PracticeScoreModel::getRankList();
        return view('', ['rank' => $rankList]);
    }
}