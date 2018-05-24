<?php
/**
 * Created by XThundering.
 * Author: XThundering
 * Date: 2018/2/6
 * Time: 20:54
 */

namespace app\practice\controller;

use app\common\model\Problem as ProblemModel;

class Index extends BaseController
{
    /**
     * @return \think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $problem = (new ProblemModel)->where('type', 'practice')->select();
        $this->assign('problem', $problem->hidden(['type', 'create_time', 'update_time'])->toArray());
        return view();
    }
}