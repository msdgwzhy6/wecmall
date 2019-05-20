<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-02-20
 * Time: 16:31
 */

namespace app\lib\exception;


class ThemeException extends BaseException
{
  public $code = 404;
  public $msg = '请求的主题不存在';
  public $errorCode = 30000;
}