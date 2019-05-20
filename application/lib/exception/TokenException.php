<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-02-22
 * Time: 15:28
 */

namespace app\lib\exception;


class TokenException extends BaseException
{
  public $code = 401;
  public $msg = 'token已过期或无效';
  public $errorCode = 10001;
}