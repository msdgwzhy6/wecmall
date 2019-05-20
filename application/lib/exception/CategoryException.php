<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-02-21
 * Time: 11:40
 */

namespace app\lib\exception;


class CategoryException extends BaseException
{
  public $code = 404;
  public $msg = '请求的分类不存在';
  public $errorCode = 50000;
}