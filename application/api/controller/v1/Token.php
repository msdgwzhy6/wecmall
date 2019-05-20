<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-02-22
 * Time: 13:18
 */

namespace app\api\controller\v1;


use app\api\service\AppToken;
use app\api\service\UserToken;
use app\api\service\Token as TokenService;
use app\api\validate\AppTokenGet;
use app\api\validate\TokenGet;
use app\lib\exception\ParameterException;
use think\Request;


// 指定允许其他域名访问
//header('Access-Control-Allow-Origin:*');
// 响应类型
//header('Access-Control-Allow-Methods:*');
// 响应头设置
//header('Access-Control-Allow-Headers:x-requested-with,content-type');

class Token
{
  /**
   * 获取用户token
   * @param string $code
   * @throws
   * @return mixed
   */
  public function getToken($code = '')
  {
    (new TokenGet())->goCheck();
    // 根据来源获取不同的token
    $type = Request::instance()->header('source');

    $ut = new UserToken($code, $type);
    $token = $ut->get();

    return [
      'token' => $token
    ];
  }

  public function verifyToken($token = '')
  {
    if (!$token) {
      throw new ParameterException([
        'token不能为空'
      ]);
    }

    $valid = TokenService::verifyToken($token);

    return [
      'isValid' => $valid
    ];

  }

  /**
   * 获取第三方应用的token
   * @param string $ac
   * @param string $se
   * @return array
   * @throws ParameterException
   * @throws \app\lib\exception\TokenException
   */
  public function getAppToken($ac = '', $se = '')
  {
    (new AppTokenGet())->goCheck();

    $app = new AppToken();
    $token = $app->get($ac, $se);
    return [
      'token' => $token
    ];
  }
}