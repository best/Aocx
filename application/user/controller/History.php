<?php

/**
 * Created by XThundering.
 * Author: XThundering
 * Date: 2018/2/8
 * Time: 17:28
 */

namespace app\user\controller;

use app\common\model\PracticeCorrect as PracticeCorrectModel;
use app\common\service\Ticket as TicketService;

class History extends BaseController
{
    protected $beforeActionList = [
        'loginCheck'
    ];

    public function index()
    {
        $userID = (new TicketService())->getUserID();
        $status = (new PracticeCorrectModel())->where('userid', $userID)->count();
        return view('', ['status' => $status]);
    }

    public function rankList()
    {
        $userID = (new TicketService())->getUserID();
        $list = PracticeCorrectModel::getListByUserID($userID);
        $r_list = array(
            'data' => array()
        );
        for ($i = 0; $i < count($list); $i++) {
            if ($list[$i]['problem_level'] == 1 || $list[$i]['problem_level'] == 2 || $list[$i]['problem_level'] == 3) {
                $list[$i]['problem_level'] = '简单';
            } elseif ($list[$i]['problem_level'] == 4 || $list[$i]['problem_level'] == 5 || $list[$i]['problem_level'] == 6) {
                $list[$i]['problem_level'] = '中等';
            } else {
                $list[$i]['problem_level'] = '困难';
            }
            $r_list['data'][$i][] = $list[$i]['problem_title'];
            $r_list['data'][$i][] = $list[$i]['problem_category'];
            $r_list['data'][$i][] = $list[$i]['problem_level'];
            $r_list['data'][$i][] = $list[$i]['score'];
            $r_list['data'][$i][] = $list[$i]['create_time'];
        }
        return json($r_list);
    }
}