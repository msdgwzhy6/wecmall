<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-03-22
 * Time: 14:33
 */

namespace app\lib\exception;


class AreaException extends BaseException
{
  public $code = 200;
  public $msg = '请求的区域不存在';
  public $errorCode = 40000;
}