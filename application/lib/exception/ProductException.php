<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-02-21
 * Time: 10:40
 */

namespace app\lib\exception;


class ProductException extends BaseException
{
  public $code = 404;
  public $msg = '请求的产品不存在';
  public $errorCode = 20000;
}