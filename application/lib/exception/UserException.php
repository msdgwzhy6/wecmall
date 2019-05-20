<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-02-25
 * Time: 17:53
 */

namespace app\lib\exception;


class UserException extends BaseException
{
  public $code = 404;
  public $msg = '请求的用户不存在';
  public $errorCode = 40000;
}