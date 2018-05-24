<?php
/**
 * Created by xthundering.
 * Author: xthundering
 * Date: 2018/2/27
 * Time: 下午2:13
 */

namespace app\practice\controller;

use app\common\model\Problem as ProblemModel;
use app\practice\validate\CategoryGet;

class Category extends BaseController
{
    /**
     * @return \think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $validate = new CategoryGet();
        $result = $validate->goCheck();
        if (!$result) {
            $this->error('题目分类不存在！');
        }
        $data = $validate->getDataByRule(input());
        $this->assign('category', $data['category']);
        $problem = (new ProblemModel)->where('type', 'practice')->where('category', $data['category'])->select();
        $this->assign('problem', $problem->hidden(['type', 'create_time', 'update_time'])->toArray());
        return view();
    }
}