<?php
/**
 * Created by XThundering.
 * Author: XThundering
 * Date: 2018/4/15
 * Time: 下午4:40
 */

namespace app\ctf\controller;

class Waiting
{
    public function index($contest)
    {
        return view('waiting', ['contest' => $contest]);
    }
}