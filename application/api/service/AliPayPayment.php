<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-03-22
 * Time: 18:28
 */

namespace app\api\service;


use app\lib\exception\TokenException;
use think\Loader;

Loader::import('Alipay.AopSdk', EXTEND_PATH, '.php');

/**
 * 支付宝相关支付功能
 * Class AliPayPayment
 * @package app\api\service
 */
class AliPayPayment
{

  /**
   * 支付宝，预订单
   * @param $orderPrice
   * @param $orderNo
   * @return mixed|\SimpleXMLElement
   * @throws TokenException
   * @throws \think\Exception
   */
  public function makePreOrder($orderPrice, $orderNo)
  {

    // 获取当前用户的openid
    $openId = Token::getCurrentTokenVar('openid');
    if (!$openId) {
      throw new TokenException();
    }

    $c = new \AopClient();
    $c->format = "json";
    $c->apiVersion = '1.0';
    $c->charset = "GBK";
    $c->signType = "RSA2";

    $c->gatewayUrl= 'https://openapi.alipay.com/gateway.do';
    $c->appId = config('alipay.app_id');
    $c->rsaPrivateKey = config('alipay.rsa_private_key');
    $c->alipayrsaPublicKey = config('alipay.rsa_public_key');
    $request = new \AlipayTradePrecreateRequest();

    $request->setBizContent("{" .
      "\"out_trade_no\":\"" . $orderNo . "\"," .
      "\"seller_id\":\"2088802143048200\"," .
      "\"total_amount\":" . $orderPrice . "," .
      "\"discountable_amount\":" . 0 . "," .
      "\"subject\":\"小商品\"," .
      "\"body\":\"破冰商城--小商品\"," .
      "\"buyer_id\":\".$openId.\"" .
      "  }");

    $result = $c->execute($request);

    $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";

    return $result;
  }
}