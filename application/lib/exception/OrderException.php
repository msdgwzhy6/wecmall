<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-02-28
 * Time: 21:19
 */

namespace app\lib\exception;


class OrderException extends BaseException
{
  public $code = 404;
  public $msg = '请求的商品不存在';
  public $errorCode = 80000;
}