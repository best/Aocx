<?php
/**
 * Created by XThundering.
 * Author: XThundering
 * Date: 2018/5/16
 * Time: 下午7:05
 */

namespace app\manager\controller;

use app\manager\model\Contest as ContestModel;
use app\manager\model\ContestExtraInfo as ContestExtraInfoModel;
use think\facade\Request;

class Contest extends BaseController
{
    protected $beforeActionList = [
        'loginCheck'
    ];

    public function index()
    {
        $contests = ContestModel::getContestList();

        return view('', ['contests' => $contests]);
    }

    public function edit($cid)
    {
        if (Request::isPost()) {
            $data = Request::post();

            $problem = new ContestModel;
            $problem->save($data, ['id' => $cid]);

            $problem_answer = new ContestExtraInfoModel;
            $problem_answer->save($data, ['cid' => $cid]);

            $this->success('更新成功', '/manager/contest');
        } else {
            $contest = ContestModel::getContest($cid);
            return view('', ['contest' => $contest]);
        }
    }

    public function add()
    {
        if (Request::isPost()) {
            $data = Request::post();
            $cID = $this->addContest($data);
            $this->addContestExtraInfo($data, $cID);

            $this->success('竞赛新增成功！', '/manager/contest');
        }
    }

    private function addContest($data)
    {
        $contest = new ContestModel;
        $contest->name = $data['name'];
        $contest->nickname = $data['nickname'];
        $contest->type = 'individual';
        $contest->status = 1;
        $contest->enroll_start_time = $data['enroll_start_time'];
        $contest->enroll_end_time = $data['enroll_end_time'];
        $contest->start_time = $data['start_time'];
        $contest->end_time = $data['end_time'];
        $contest->save();
        // 获取自增ID
        return $contest->id;
    }

    private function addContestExtraInfo($data, $cID)
    {
        $contest_extra_info = new ContestExtraInfoModel();
        $contest_extra_info->cid = $cID;
        $contest_extra_info->organizer = $data['organizer'];
        $contest_extra_info->place = $data['place'];
        $contest_extra_info->save();
    }

    /**
     * @param $cid
     * @return string
     * @throws \think\exception\DbException
     */
    public function delete($cid)
    {
        if (Request::isPost()) {
            $contest = ContestModel::get($cid);
            $contest->delete();

            $contest_extra_info = ContestExtraInfoModel::get($cid);
            $contest_extra_info->delete();

            return 'success';
        }
    }
}