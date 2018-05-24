<?php
/**
 * Created by XThundering.
 * Author: XThundering
 * Date: 2018/5/15
 * Time: 下午10:26
 */

namespace app\manager\controller;

use think\Db;

class Index extends BaseController
{
    protected $beforeActionList = [
        'loginCheck'
    ];

    public function index()
    {
        return view('', ['count' => $this->count()]);
    }

    private function count()
    {
        $count['user'] = Db::table('user')->count();
        $count['problem'] = Db::table('problem')->count();
        $count['problem_practice'] = Db::table('problem')->where('type', '=', 'practice')->count();
        $count['problem_contest'] = Db::table('problem')->where('type', '=', 'contest')->count();
        $count['user_contest'] = Db::table('user_contest')->where('delete_time', 'NULL')->count();
        $count['distribute_uid'] = Db::table('distribute_uid')->where('delete_time', 'NULL')->count();
        $count['contest'] = Db::table('contest')->count();
        $count['practice_post'] = Db::table('practice_post')->count();

        return $count;
    }
}