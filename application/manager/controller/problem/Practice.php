<?php
/**
 * Created by XThundering.
 * Author: XThundering
 * Date: 2018/5/16
 * Time: 上午10:34
 */

namespace app\manager\controller\problem;

use app\manager\controller\BaseController;
use app\manager\model\Problem as ProblemModel;
use app\manager\model\ProblemAnswer as ProblemAnswerModel;
use app\manager\model\ProblemExtraInfo as ProblemExtraInfoModel;
use think\facade\Request;

class Practice extends BaseController
{
    protected $beforeActionList = [
        'loginCheck'
    ];

    public function index()
    {
        $problems = ProblemModel::getPracticeProblemList();

        return view('', ['problems' => $problems]);
    }

    public function add()
    {
        if (Request::isPost()) {
            $data = Request::post();
            $pID = $this->addProblem($data);
            $this->addProblemAnswer($data, $pID);
            $this->addProblemExtraInfo($data, $pID);

            $this->success('题目新增成功！', '/manager/problem/practice');
        }
    }

    private function addProblem($data)
    {
        $problem = new ProblemModel;
        $problem->title = $data['title'];
        $problem->content = $data['content'];
        $problem->url = $data['url'];
        $problem->score = $data['score'];
        $problem->level = $data['level'];
        $problem->category = $data['category'];
        $problem->type = 'practice';
        $problem->save();
        // 获取自增ID
        return $problem->id;
    }

    private function addProblemAnswer($data, $pID)
    {
        $problem_answer = new ProblemAnswerModel;
        $problem_answer->pid = $pID;
        $problem_answer->answer = $data['answer'];
        $problem_answer->save();
    }

    private function addProblemExtraInfo($data, $pID)
    {
        $problem_extra_info = new ProblemExtraInfoModel;
        $problem_extra_info->pid = $pID;
        $problem_extra_info->source = $data['source'];
        $problem_extra_info->save();
    }

    public function edit($pid)
    {
        if (Request::isPost()) {
            $data = Request::post();

            $problem = new ProblemModel;
            $problem->save($data, ['id' => $pid]);

            $problem_extra_info = new ProblemExtraInfoModel;
            $problem_extra_info->save($data, ['pid' => $pid]);

            $problem_answer = new ProblemAnswerModel;
            $problem_answer->save($data, ['pid' => $pid]);

            $this->success('更新成功', '/manager/problem/practice');
        } else {
            $problem = ProblemModel::getPracticeProblem($pid);
            return view('', ['problem' => $problem]);
        }
    }

    /**
     * @param $pid
     * @return string
     * @throws \think\exception\DbException
     */
    public function delete($pid)
    {
        if (Request::isPost()) {
            $problem = ProblemModel::get($pid);
            $problem->delete();

            $problem_extra_info = ProblemExtraInfoModel::get($pid);
            $problem_extra_info->delete();

            $problem_answer = ProblemAnswerModel::get($pid);
            $problem_answer->delete();

            return 'success';
        }
    }
}