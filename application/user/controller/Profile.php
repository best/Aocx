<?php
/**
 * Created by XThundering.
 * Author: XThundering
 * Date: 2018/2/8
 * Time: 17:28
 */

namespace app\user\controller;

use app\common\model\UserExtraInfo;
use app\common\service\Session as SessionService;
use app\common\service\Ticket as TicketService;
use app\common\service\Upload as UploadService;
use app\user\validate\ProfilePost;

class Profile extends BaseController
{
    protected $beforeActionList = [
        'loginCheck'
    ];

    protected $userInfo = [
        'name' => null,
        'xuehao' => null,
        'site' => null,
        'bio' => null,
        'avatar' => 'https://static.ahusec.cn/img/avatar/default.jpg'
    ];

    public function index()
    {
        $userExtraInfo = (new TicketService())->getUserExtraInfo();
        if ($userExtraInfo) {
            $this->userInfo['name'] = $userExtraInfo['name'];
            $this->userInfo['xuehao'] = $userExtraInfo['xuehao'];
            $this->userInfo['site'] = $userExtraInfo['site'];
            $this->userInfo['bio'] = $userExtraInfo['bio'];
            if ($userExtraInfo['avatar']) {
                $this->userInfo['avatar'] = $userExtraInfo['avatar'];
            }
        }

        return view('', $this->userInfo);
    }

    public function postCheck()
    {
        $validate = new ProfilePost();
        $result = $validate->goCheck();
        if (!$result) {
            return view('error', ['message' => $validate->getError()]);
        }

        $data = $validate->getDataByRule(input('post.'));
        (new UserExtraInfo())::updateInfo($data);
        return view('success');
    }

    public function avatarPost()
    {
        // 获取表单上传文件
        $file = request()->file('image');
        //校验器，判断图片格式是否正确
        if (true !== $this->validate(['image' => $file], ['image' => 'require|image'])) {
            $message = [
                'status' => false,
                'message' => '文件类型错误！'
            ];
            return json($message);
        } else {
            // 移动到框架应用根目录/uploads/ 目录下
            $info = $file->move('../runtime/temp/avatar');
            if ($info) {
                // 上传文件
                $filePath = '../runtime/temp/avatar/' . $info->getSaveName();
                $result = (new UploadService())->uploadAvatar($filePath);
                if ($result === true) {
                    // 更新数据库
                    UserExtraInfo::updateAvatar();
                    // 缓存session
                    (new SessionService())->initSession();
                    // 返回结果
                    $message = [
                        'status' => true,
                        'message' => '上传成功！'
                    ];
                    return json($message);
                } else {
                    $message = [
                        'status' => false,
                        'message' => '文件上传云端失败'
                    ];
                    return json($message);
                }
            } else {
                $message = [
                    'status' => false,
                    'message' => $file->getError()
                ];
                return json($message);
            }
        }
    }
}