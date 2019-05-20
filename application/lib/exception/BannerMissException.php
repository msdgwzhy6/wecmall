<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-02-11
 * Time: 13:54
 */

namespace app\lib\exception;


class BannerMissException extends BaseException
{
  public $code = 404;
  public $msg = '请求的banner不存在';
  public $errorCode = 40000;
}