<?php
/**
 * Created by XThundering.
 * Author: XThundering
 * Date: 2018/5/16
 * Time: 上午10:34
 */

namespace app\manager\controller\problem;

use app\manager\controller\BaseController;
use app\manager\model\Contest as ContestModel;
use app\manager\model\ContestProblem as ContestProblemModel;
use app\manager\model\Problem as ProblemModel;
use app\manager\model\ProblemAnswer as ProblemAnswerModel;
use think\facade\Request;

class Contest extends BaseController
{
    protected $beforeActionList = [
        'loginCheck'
    ];

    public function index()
    {
        // 获取竞赛列表
        $contests = ContestModel::getContestList();

        $problems = ProblemModel::getContestProblemList();

        return view('', ['problems' => $problems, 'contests' => $contests]);
    }

    public function edit($pid)
    {
        if (Request::isPost()) {
            $data = Request::post();

            $problem = new ProblemModel;
            $problem->save($data, ['id' => $pid]);

            $problem_answer = new ProblemAnswerModel;
            $problem_answer->save($data, ['pid' => $pid]);

            // 更新题目所属竞赛 一个题目之内属于一个竞赛
            $contest_problem = new ContestProblemModel;
            $contest_problem->save($data, ['pid' => $pid]);

            $this->success('更新成功', '/manager/problem/contest');
        } else {
            // 获取竞赛列表
            $contests = ContestModel::getContestList();
            // 获取题目所属竞赛
            $cid = ContestProblemModel::getContestID($pid);

            $problem = ProblemModel::getContestProblem($pid);
            return view('', ['problem' => $problem, 'contests' => $contests, 'cid' => $cid]);
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

            $problem_answer = ProblemAnswerModel::get($pid);
            $problem_answer->delete();

            $contest_problem = ContestProblemModel::get($pid);
            $contest_problem->delete();

            return 'success';
        }
    }

    public function add()
    {
        if (Request::isPost()) {
            $data = Request::post();
            $pID = $this->addProblem($data);
            $this->addProblemAnswer($data, $pID);
            $this->addContestProblem($data, $pID);

            $this->success('题目新增成功！', '/manager/problem/contest');
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
        $problem->type = 'contest';
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

    private function addContestProblem($data, $pID)
    {
        $contest_problem = new ContestProblemModel;
        $contest_problem->cid = $data['contest'];
        $contest_problem->pid = $pID;
        $contest_problem->save();
    }
}