<?php
/**
 * Created by XThundering.
 * Author: XThundering
 * Date: 2018/2/19
 * Time: 20:23
 */

namespace app\common\service;

use app\common\service\Ticket as TicketService;
use OSS\Core\OssException;
use OSS\OssClient;

class Upload
{
    //上传用户头像
    public function uploadAvatar($file)
    {
        $userID = (new TicketService())->getUserID();
        if (!$userID) {
            return false;
        }
        $bucket = config('upload.Bucket');
        $object = 'img/avatar/@' . $userID;
        $path = $file;
        try {
            $ossClient = $this->new_oss();
            //uploadFile的上传方法
            $ossClient->uploadFile($bucket, $object, $path);
        } catch (OssException $e) {
            //如果出错这里返回报错信息
            return $e->getMessage();
        }
        //否则，完成上传操作
        return true;
    }

    public function uploadFile($object, $path)
    {
        $bucket = config('upload.Bucket');
        //try 要执行的代码,如果代码执行过程中某一条语句发生异常,则程序直接跳转到CATCH块中,由$e收集错误信息和显示
        try {
            $ossClient = $this->new_oss();
            //uploadFile的上传方法
            $ossClient->uploadFile($bucket, $object, $path);
        } catch (OssException $e) {
            //如果出错这里返回报错信息
            return $e->getMessage();
        }
        //否则，完成上传操作
        return true;
    }

    /**
     * @return OssClient
     * @throws OssException
     */
    private function new_oss()
    {
        $oss = new OssClient(config('upload.KeyId'), config('upload.KeySecret'), config('upload.Endpoint'));
        return $oss;
    }
}