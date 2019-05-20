<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-02-27
 * Time: 13:50
 */

namespace app\lib\exception;


class ForbiddenException extends BaseException
{
  public $code = 403;
  public $msg = '请求的权限不够';
  public $errorCode = 10000;
}