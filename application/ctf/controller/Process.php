<?php
/**
 * Created by XThundering.
 * User: XThundering
 * Date: 2018/4/22
 * Time: 17:20
 */

namespace app\ctf\controller;


use app\common\model\Contest as ContestModel;

class Process extends BaseController
{
    protected $beforeActionList = [
        'loginCheck'
    ];

    public function index($contest)
    {
        // 验证用户是否报名
        $this->applyCheck($contest['id']);
        // 获取题目列表并显示
        $problems = ContestModel::getProblems($contest['id']);
        $problems = $this->formatProblems($problems);
        return view('process', ['contest' => $contest, 'problems' => $problems]);
    }

    // 格式化输出Problems数组
    private function formatProblems($problems)
    {
        $new_problems['Web'] = [];
        $new_problems['Pwn'] = [];
        $new_problems['Crypto'] = [];
        $new_problems['Reverse'] = [];
        $new_problems['Misc'] = [];
        foreach ($problems as $problem) {
            switch ($problem['category']) {
                case 'Web':
                    $new_problems['Web'][] = $problem;
                    break;
                case 'Pwn':
                    $new_problems['Pwn'][] = $problem;
                    break;
                case 'Crypto':
                    $new_problems['Crypto'][] = $problem;
                    break;
                case 'Reverse':
                    $new_problems['Reverse'][] = $problem;
                    break;
                case 'Misc':
                    $new_problems['Misc'][] = $problem;
                    break;
            }
        }
        return $new_problems;
    }
}