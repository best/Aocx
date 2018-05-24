<?php
/**
 * Created by xthundering.
 * Author: xthundering
 * Date: 2018/4/8
 * Time: 下午6:35
 */

namespace app\ctf\validate;

use app\common\model\Contest as ContestModel;
use think\facade\Request;
use think\Validate;

class BaseValidate extends Validate
{
    public function goCheck()
    {
        $params = Request::param();

        if (!$this->check($params)) {
            return false;
        }
        return true;
    }

    public function getDataByRule($arrays)
    {
        $newArray = [];
        foreach ($this->rule as $key => $value) {
            $newArray[$key] = $arrays[$key];
        }
        return $newArray;
    }

    // contest.nickname验证器
    protected function ContestNicknameValidate($value)
    {
        $result = ContestModel::contestNicknameValidate($value);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    // problem分类验证器
    protected function CategoryValidate($value)
    {
        $category = array("Web", "Pwn", "Crypto", "Reverse", "Misc");
        if (in_array($value, $category)) {
            return true;
        } else {
            return false;
        }
    }

    // problem.id验证器
    protected function ProblemValidate($value)
    {
        // 此处只验证格式，具体的题目和竞赛的关系判断交给后续操作
        if (strlen($value) == 5) {
            return true;
        } else {
            return false;
        }

    }
}