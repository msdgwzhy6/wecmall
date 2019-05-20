<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-02-28
 * Time: 17:14
 */

namespace app\api\service;


use app\api\model\OrderProduct;
use app\api\model\Product;
use app\api\model\UserAddress;
use app\lib\enum\OrderStatusEnum;
use app\lib\exception\OrderException;
use app\lib\exception\UserException;
use app\api\model\Order as OrderModel;
use think\Db;
use think\Exception;

class Order
{
  // 订单的商品列表，客户端传入的商品列表
  protected $oProducts;
  // 数据库中查询到的商品列表，包括库存量
  protected $products;
  //
  protected $uid;

  /**
   * 下单
   * @param $uid
   * @param $oProducts
   * @return array
   * @throws Exception
   */
  public function place($uid, $oProducts)
  {
    // 从数据库中查询到产品
    $this->oProducts = $oProducts;
    $this->products = $this->getProductsByOrder($oProducts);
    $this->uid = $uid;
    $status = $this->getOrderStatus();
    if (!$status['pass']) {
      $status['order_id'] = -1;
      return $status;
    }
    // 创建订单
    $orderSnap = $this->snapOrder($status);
    $order = $this->createOrder($orderSnap);
    $order['pass'] = true;
    return $order;
  }

  private function createOrder($snap)
  {
    Db::startTrans();
    try {
      $orderNo = self::makeOrderNo();
      $order = new OrderModel();
      // 赋值
      $order->user_id = $this->uid;
      $order->order_no = $orderNo;
      $order->total_price = $snap['orderPrice'];
      $order->total_count = $snap['totalCount'];
      $order->snap_img = $snap['snapImg'];
      $order->snap_name = $snap['snapName'];
      $order->snap_address = $snap['snapAddress'];
      $order->snap_items = json_encode($snap['pStatus']);
      // 保存订单
      $order->save();

      $orderId = $order->id;
      $create_time = $order->create_time;

      foreach ($this->oProducts as &$p) {
        $p['order_id'] = $orderId;
      }

      $orderProduct = new OrderProduct();
      $orderProduct->saveAll($this->oProducts);

      Db::commit();

      return [
        'order_no' => $orderNo,
        'order_id' => $orderId,
        'create_time' => $create_time
      ];
    } catch (Exception $exception) {
      Db::rollback();
      throw $exception;
    }

  }

  /**
   * 创建订单编号
   * @return string
   */
  public static function makeOrderNo()
  {
    $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
    $orderSn =
      $yCode[intval(date('Y')) - 2019] . strtoupper(dechex(date('m'))) . date(
        'd') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf(
        '%02d', rand(0, 99));
    return $orderSn;
  }

  // 生成订单快照
  private function snapOrder($status)
  {
    $snap = [
      'orderPrice' => 0,
      'totalCount' => 0,
      'pStatus' => [],
      'snapAddress' => null,
      'snapName' => '',
      'snapImg' => ''
    ];

    $snap['orderPrice'] = $status['orderPrice'];
    $snap['totalCount'] = $status['totalCount'];
    $snap['pStatus'] = $status['pStatusArray'];
    $snap['snapAddress'] = json_encode($this->getUserAddress());
    $snap['snapName'] = $this->products[0]['name'];
    $snap['snapImg'] = $this->products[0]['main_img_url'];

    if (count($this->products) > 1) {
      $snap['snapName'] .= '等';
    }

    return $snap;
  }

  /**
   * 获取用户地址
   * @return array
   * @throws UserException
   * @throws \think\db\exception\DataNotFoundException
   * @throws \think\db\exception\ModelNotFoundException
   * @throws \think\exception\DbException
   */
  private function getUserAddress()
  {
    $userAddress = UserAddress::where('user_id', '=', $this->uid)
      ->find();

    if (!$userAddress) {
      throw new UserException([
        'msg' => '用户地址不存在 ，下单失败',
        'errorCode' => 60001
      ]);
    }

    return $userAddress->toArray();
  }

  /**
   * 检查订单的库存量检查
   * @param $orderId
   * @return array
   * @throws \think\db\exception\DataNotFoundException
   * @throws \think\db\exception\ModelNotFoundException
   * @throws \think\exception\DbException
   */
  public function checkOrderStock($orderId)
  {
    $oProducts = OrderProduct::where('order_id', '=', $orderId)
      ->select();
    $this->oProducts = $oProducts;

    $this->products = $this->getProductsByOrder($oProducts);
    $status = $this->getOrderStatus();
    return $status;
  }

  /**
   * 获取订单状态
   */
  private function getOrderStatus()
  {
    $status = [
      'pass' => true,
      'orderPrice' => 0,
      'totalCount' => 0,
      'pStatusArray' => []
    ];

    foreach ($this->oProducts as $oProduct) {
      $pStatus = $this->getProductStatus($oProduct['product_id'],
        $oProduct['count'], $this->products);

      if (!$pStatus['hasStock']) {
        $status['pass'] = false;
      }

      $status['totalCount'] += $pStatus['counts'];
      $status['orderPrice'] += $pStatus['totalPrice'];
      array_push($status['pStatusArray'], $pStatus);
    }

    return $status;
  }


  /**
   * 获取产品状态
   * @param $oPId
   * @param $oCount
   * @param $products
   * @return array
   * @throws OrderException
   */
  private function getProductStatus($oPId, $oCount, $products)
  {
    $pIndex = -1;
    $pStatus = [
      'id' => null,
      'hasStock' => false,
      'counts' => 0,
      'price' => 0,
      'name' => '',
      'totalPrice' => 0,
      'main_img_url' => ''
    ];

    for ($i = 0; $i < count($products); $i++) {
      if ($oPId == $products[$i]['id']) {
        $pIndex = $i;
      }
    }

    if ($pIndex == -1) {
      throw new OrderException([
        'msg' => 'id为' . $oPId . '商品不存在，订单创建失败'
      ]);
    } else {
      //
      $product = $products[$pIndex];
      $pStatus['id'] = $product['id'];
      $pStatus['name'] = $product['name'];
      $pStatus['counts'] = $oCount;
      $pStatus['price'] = $product['price'];
      $pStatus['main_img_url'] = $product['main_img_url'];
      $pStatus['totalPrice'] = $product['price'] * $oCount;
      $pStatus['hasStock'] = $product['stock'] - $oCount >= 0;
    }

    return $pStatus;
  }

  /**
   * 获取数据库中的产品
   * @param $oProducts
   * @return mixed
   */
  private function getProductsByOrder($oProducts)
  {
//    foreach ($oProducts as $oProduct) {
//      // 循环查询数据库
//    }

    $oPIds = [];
    foreach ($oProducts as $item) {
      array_push($oPIds, $item['product_id']);
    }

    $products = Product::all($oPIds)
      ->visible(['id', 'price', 'stock', 'name', 'main_img_url'])
      ->toArray();
    return $products;
  }

  /**
   * 发送模版消息
   * @param $orderID
   * @param string $jumpPage
   * @return mixed
   * @throws OrderException
   * @throws \think\db\exception\DataNotFoundException
   * @throws \think\db\exception\ModelNotFoundException
   * @throws \think\exception\DbException
   */
  public function delivery($orderID, $jumpPage = '')
  {
    $order = OrderModel::where('id', '=', $orderID)
      ->find();
    if (!$order) {
      throw new OrderException();
    }
    if ($order->status != OrderStatusEnum::PAID) {
      throw new OrderException([
        'msg' => '还没付款呢，想干嘛？或者你已经更新过订单了，不要再刷了',
        'errorCode' => 80002,
        'code' => 403
      ]);
    }
    $order->status = OrderStatusEnum::DELIVERED;
    $order->save();
//            ->update(['status' => OrderStatusEnum::DELIVERED]);
    $message = new DeliveryMessage();
    return $message->sendDeliveryMessage($order, $jumpPage);
  }
}