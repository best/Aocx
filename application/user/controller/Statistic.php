<?php
/**
 * Created by XThundering.
 * Author: XThundering
 * Date: 2018/2/18
 * Time: 19:56
 */

namespace app\user\controller;

class Statistic extends BaseController
{
    protected $beforeActionList = [
        'loginCheck'
    ];

    public function index()
    {
        return view();
    }
}