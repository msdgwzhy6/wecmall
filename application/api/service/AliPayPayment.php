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
use think\Log;

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
		$uid = Token::getCurrentTokenVar('uid');
		if (!$uid) {
			throw new TokenException();
		}

		$c = new \AopClient();
		$c->format = "json";
		$c->charset = "utf-8";
		$c->signType = "RSA2";

		$c->gatewayUrl = 'https://openapi.alipay.com/gateway.do';

		$c->appId = config('alipay.app_id');
		$c->rsaPrivateKey = config('alipay.rsa_private_key');
		$c->alipayrsaPublicKey = config('alipay.rsa_public_key');
		$request = new \AlipayTradeAppPayRequest();
//		$request = new \AlipayTradePrecreateRequest();
		$request->setNotifyUrl(config('alipay.notify_url'));

		$bizcontent = "{\"body\":\"破冰商城--小商品\","
			. "\"subject\": \"App支付测试\","
			. "\"out_trade_no\": \"$orderNo\","
			. "\"total_amount\": \"$orderPrice\""
			. "}";
		$request->setBizContent($bizcontent);
		Log::record($c);
		Log::record($request);
		$result = $c->sdkExecute($request);
		Log::record($result);
		return $result;
	}
}