<?php
/**
 * Created by xthundering.
 * Author: xthundering
 * Date: 2018/2/24
 * Time: 下午8:15
 */

namespace app\practice\validate;

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

    protected function CategoryValidate($value)
    {
        $category = array("Web", "Pwn", "Crypto", "Reverse", "Misc");
        if (in_array($value, $category)) {
            return true;
        } else {
            return false;
        }
    }
}