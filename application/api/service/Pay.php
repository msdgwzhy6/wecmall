<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-03-05
 * Time: 21:36
 */

namespace app\api\service;

use app\lib\enum\OrderStatusEnum;
use app\lib\exception\OrderException;
use app\lib\exception\TokenException;
use think\App;
use think\Exception;

use app\api\model\Order as OrderModel;
use app\api\service\Order as OrderService;
use think\Loader;
use think\Log;

//require_once App::getRootPath() . 'extend/WxPay/WxPay.Api.php';

Loader::import('WxPay.WxPay', EXTEND_PATH, '.Api.php');


class Pay
{
	private $orderId;
	private $orderNo;
	private $source;
	private $type;

	public function __construct($orderId, $source = 'android', $type = 'wechat')
	{
		if (!$orderId) {
			throw new Exception("订单号不能为空");
		}

		$this->orderId = $orderId;
		$this->source = $source;
		$this->type = $type;
	}

	/**
	 * 支付
	 * @return array
	 * @throws Exception
	 * @throws OrderException
	 * @throws TokenException
	 * @throws \think\db\exception\DataNotFoundException
	 * @throws \think\db\exception\ModelNotFoundException
	 * @throws \think\exception\DbException
	 */
	public function pay()
	{
		$this->checkOrderValid();
		// 进行库存量检测
		$orderService = new OrderService();
		$status = $orderService->checkOrderStock($this->orderId);
		// 订单库存检测失败，返回订单信息
		if (!$status['pass']) {
			return $status;
		}

		if ($this->type == 'wechat') {
			// 微信支付相关操作
			return $this->makeWxPreOrder($status['orderPrice']);
		} else {
			$alipay = new AliPayPayment();
			return $alipay->makePreOrder($status['orderPrice'], $this->orderNo);
		}
	}


	/**
	 * @param $totalPrice
	 * @return array
	 * @throws Exception
	 * @throws OrderException
	 * @throws TokenException
	 */
	private function makeWxPreOrder($totalPrice)
	{
		// 获取当前用户的openid
		$openId = Token::getCurrentTokenVar('openid');
		if (!$openId) {
			throw new TokenException();
		}
		//
		$wxOrderData = new \WxPayUnifiedOrder();
		$wxOrderData->SetOut_trade_no($this->orderNo);
		$wxOrderData->SetTrade_type('JSAPI');
		$wxOrderData->SetTotal_fee($totalPrice * 100);
		$wxOrderData->SetBody('破冰商城');
		$wxOrderData->SetOpenid($openId);
		$wxOrderData->SetNotify_url(config('secure.pay_back_url'));
		return $this->getPaySignature($wxOrderData);

	}

	/**
	 * 支付签名
	 * @param $wxOrderData
	 * @return array
	 * @throws Exception
	 * @throws OrderException
	 * @throws \WxPayException
	 * @throws \think\exception\PDOException
	 */
	private function getPaySignature($wxOrderData)
	{
		$config = new \WxPayConfig();
		$wxOrder = \WxPayApi::unifiedOrder($config, $wxOrderData);
		Log::record($wxOrder);
		// 判断预订单是否成功
		if ($wxOrder['return_code'] != 'SUCCESS' ||
			$wxOrder['result_code'] != 'SUCCESS') {
			Log::record($wxOrder, 'error');
			Log::record('获取预订单失败', 'error');
			throw new OrderException([
				'msg' => '下单失败，请稍后重试',
				'errorCode' => 50003,
				'code' => 200
			]);
		}
		// prepay_id
		$this->recordPreOrder($wxOrder);
		//
		$signature = $this->sign($wxOrder);
		return $signature;
	}

	/**
	 * 签名
	 * @param $wxOrder
	 * @return array
	 * @throws \WxPayException
	 */
	private function sign($wxOrder)
	{
		$jsApiPayData = new \WxPayJsApiPay();
		$jsApiPayData->SetAppid(config('wechat.app_id'));
		$jsApiPayData->SetTimeStamp((string)time());

		$rand = md5(time() . mt_rand(0, 1000));
		$jsApiPayData->SetNonceStr($rand);
		$jsApiPayData->SetPackage('prepay_id=' . $wxOrder['prepay_id']);
		$jsApiPayData->SetSignType('md5');
//    Log::record($jsApiPayData->GetValues());
		$config = new \WxPayConfig();
		$sign = $jsApiPayData->MakeSign($config);

		$rawValues = $jsApiPayData->GetValues();
		$rawValues['paySign'] = $sign;

		unset($rawValues['appId']);

		return $rawValues;
	}

	/**
	 * 记录预付费订单
	 * @param $wxOrder
	 * @throws Exception
	 * @throws \think\exception\PDOException
	 */
	private function recordPreOrder($wxOrder)
	{
		OrderModel::where('id', '=', $this->orderId)
			->update(['prepay_id' => $wxOrder['prepay_id']]);
	}

	/**
	 * 检测订单是否通过
	 * @return bool
	 * @throws Exception
	 * @throws OrderException
	 * @throws TokenException
	 * @throws \think\db\exception\DataNotFoundException
	 * @throws \think\db\exception\ModelNotFoundException
	 * @throws \think\exception\DbException
	 */
	private function checkOrderValid()
	{
		// 获取当前订单
		$order = OrderModel::where('id', '=', $this->orderId)
			->find();
		if (!$order) {
			throw new OrderException();
		}
		// 检查订单是否是合法操作
		if (!Token::isValidOperate($order->user_id)) {
			throw new TokenException([
				'msg' => '订单与用户不匹配',
				'errorCode' => 10003
			]);
		}
		// 检查订单是否已经支付
		if ($order->status != OrderStatusEnum::UNPAID) {
			throw new OrderException([
				'msg' => '订单已支付',
				'errorCode' => 80003
			]);
		}

		$this->orderNo = $order->order_no;
		return true;
	}

}