<?php
/**
 * Created by XThundering.
 * Author: XThundering
 * Date: 2018/2/20
 * Time: 15:14
 */

namespace app\user\validate;

class ProfilePost extends BaseValidate
{
    protected $rule = [
        'name' => 'chs|max:45',
        'xuehao' => 'alphaNum|max:9',
        'site' => 'url|max:45',
        'bio' => 'chsDash|max:256'
    ];

    protected $message = [
        'name.chs' => '姓名只能为汉字',
        'name.max' => '姓名最大长度为45',
        'xuehao.alphaNum' => '学号格式错误',
        'xuehao.max' => '学号最大长度为9',
        'site.url' => '请填写完整格式URL',
        'site.max' => 'URL最大长度为45',
        'bio.chsDash' => '个人简介只能为汉字、字母、数字和下划线_及破折号-',
        'bio.max' => '个人简介最大长度为256'
    ];
}