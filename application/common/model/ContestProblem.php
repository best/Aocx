<?php
/**
 * Created by XThundering.
 * Author: XThundering
 * Date: 2018/5/9
 * Time: 下午3:37
 */

namespace app\common\model;

use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;
use think\model\Pivot;

class ContestProblem extends Pivot
{
    public static function flagValidate($cID, $pID)
    {
        try {
            $result = (new ContestProblem())->where('cid', $cID)->where('pid', $pID)->find();
        } catch (DataNotFoundException $e) {
            return false;
        } catch (ModelNotFoundException $e) {
            return false;
        } catch (DbException $e) {
            return false;
        }
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
}