<?php
/**
 * Created by XThundering.
 * Author: XThundering
 * Date: 2018/5/15
 * Time: 下午5:21
 */

namespace app\common\model;

use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;

class ContestNotice extends BaseModel
{
    public static function getListByCID($cID)
    {
        try {
            $list = (new ContestNotice)->where('cid', $cID)->order('create_time', 'desc')->select();
        } catch (DataNotFoundException $e) {
            $list = null;
        } catch (ModelNotFoundException $e) {
            $list = null;
        } catch (DbException $e) {
            $list = null;
        }
        if ($list) {
            return $list->toArray();
        } else {
            return null;
        }
    }
}