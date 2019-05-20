<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-02-22
 * Time: 14:07
 */

namespace app\lib\exception;


class WeChatException extends BaseException
{
  public $code = 404;
  public $msg = '请求的banner不存在';
  public $errorCode = 40000;
}