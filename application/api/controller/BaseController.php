<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-02-27
 * Time: 14:54
 */

namespace app\api\controller;

use app\api\service\Token as TokenService;

use think\Controller;

class BaseController extends Controller
{

  /**
   * 校验用户操作权限
   * @throws \think\Exception
   */
  protected function checkPrimaryScope()
  {
    TokenService::needPrimaryScope();
  }

  /**
   * 校验用户操作权限
   * @throws \think\Exception
   */
  protected function checkExclusiveScope()
  {
    TokenService::needExclusiveScope();
  }


}