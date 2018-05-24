<?php
/**
 * Created by XThundering.
 * Author: XThundering
 * Date: 2018/2/8
 * Time: 17:24
 */

namespace app\user\controller;

use app\user\service\FindPass as FindPassService;
use app\user\validate\FindPassPost;

class Findpass
{
    public function index()
    {
        return view();
    }

    public function postCheck()
    {
        $validate = new FindPassPost();
        $result = $validate->goCheck();
        if (!$result) {
            return view('common/error', ['message' => $validate->getError()]);
        }

        $data = $validate->getDataByRule(input('post.'));
        $message = (new FindPassService())->send($data['username'], $data['email']);
        if ($message['status'] === false) {
            return view('common/error', ['message' => $message['message']]);
        }
        return view('success');
    }
}