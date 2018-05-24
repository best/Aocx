<?php
/**
 * Created by xthundering.
 * Author: xthundering
 * Date: 2018/2/26
 * Time: 下午3:16
 */

namespace app\common\model;

use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;

class ProblemAnswer extends BaseModel
{
    protected $pk = 'pid';

    public static function checkFlag($pID, $flag)
    {
        try {
            $flag = (new ProblemAnswer)->where('pid', $pID)
                ->where('answer', $flag)
                ->find();
        } catch (DataNotFoundException $e) {
            return false;
        } catch (ModelNotFoundException $e) {
            return false;
        } catch (DbException $e) {
            return false;
        }
        if (!$flag) {
            return false;
        }
        return true;
    }
}