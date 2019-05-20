<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-02-11
 * Time: 13:45
 */

namespace app\lib\exception;

use think\exception\Handle;
use think\facade\Log;

/**
 * Class ExceptionHandler
 * 异常处理类
 * @package app\exception
 */
class ExceptionHandler extends Handle
{
  private $code;
  private $msg;
  private $errorCode;

  // 客户端当前请求的URL路径

  public function render(\Exception $e)
  {
    if ($e instanceof BaseException) {
      // 如果是自定义异常类
      $this->code = $e->code;
      $this->msg = $e->msg;
      $this->errorCode = $e->errorCode;
    } else {
      // Config::get('app_debug');
      $switch = config('app_debug');
      if ($switch) {
        return parent::render($e);
      } else {
        $this->code = 200;
        $this->msg = '服务器内部错误。';
        $this->errorCode = 999;
        // 记录系统日志
        $this->recordErrorLog($e);
      }
    }

    $request = request();

    $result = [
      'msg' => $this->msg,
      'error_code' => $this->errorCode,
      'request_url' => $request->url()
    ];

    return json($result, $this->code);
  }

  /**
   * 记录异常日志
   * @param \Exception $e 异常信息
   */
  private function recordErrorLog(\Exception $e)
  {
    Log::init([
      'type' => 'File',
      'path' => LOG_PATH,
      'level' => ['error']
    ]);
    Log::record($e->getMessage(), 'error');
  }
}