<?php
/**
 * Created by XThundering.
 * Author: XThundering
 * Date: 2018/2/18
 * Time: 19:56
 */

namespace app\user\controller;

use app\common\model\Contest as ContestModel;
use app\common\model\UserContest as UserContestModel;
use app\user\service\ApplyContest as ApplyContestService;
use app\user\validate\ContestIDGet;

class Apply extends BaseController
{
    protected $beforeActionList = [
        'loginCheck'
    ];

    public function index()
    {
        $contestList = ContestModel::getEnrollContestList();
        foreach ($contestList as $key => $value) {
            $contestList[$key]['enrollNum'] = UserContestModel::getEnrollNumByCID($value['id']);
        }
        return view('', ['contestList' => $contestList]);
    }

    public function contest()
    {
        $validate = new ContestIDGet();
        $result = $validate->goCheck();
        if (!$result) {
            return view('common/error', ['message' => $validate->getError()]);
        }
        $data = $validate->getDataByRule(input());
        $message = (new ApplyContestService())->go($data['id']);
        if (!$message['status']) {
            return view('common/error', ['message' => $message['message']]);
        }
        return view('success');
    }
}