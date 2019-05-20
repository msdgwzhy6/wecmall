<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-02-11
 * Time: 13:51
 */

namespace app\lib\exception;


use think\Exception;
use Throwable;

class BaseException extends Exception
{
  //HTTP状态码 404, 200 ...
  public $code = 400;

  // 错误信息
  public $msg = 'error params!';

  // 自定义错误码
  public $errorCode = 10000;

  /**
   * BaseException constructor.
   * @params $params 参数
   * @throws
   */
  public function __construct($params=[])
  {

    if (!is_array($params)) {
      // throw new Exception("参数必须是数组");
      return;
    }
    if (array_key_exists('code', $params)) {
      $this->code = $params['code'];
    }
    if (array_key_exists('msg', $params)) {
      $this->msg = $params['msg'];
    }
    if (array_key_exists('errorCode', $params)) {
      $this->errorCode = $params['errorCode'];
    }
  }

}