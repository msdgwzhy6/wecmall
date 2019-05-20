<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-02-11
 * Time: 16:19
 */

namespace app\lib\exception;


class ParameterException extends BaseException
{
  public $code = 400;
  public $msg = 'invalid parameter!';
  public $errorCode = 10000;
}