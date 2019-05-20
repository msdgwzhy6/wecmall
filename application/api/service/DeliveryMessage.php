<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-03-20
 * Time: 18:12
 */

namespace app\api\service;


use app\api\model\User;
use app\lib\exception\OrderException;
use app\lib\exception\UserException;

class DeliveryMessage
{
  // 模版消息的ID
  const DELIVERY_MSG_ID = '79mapHPWHYHNfjE2yGRS2BfuDVN2bKzt_9BJ16YqL7g';


  /**
   * 发送模版消息
   * @param $order
   * @param string $tplJumpPage
   * @return mixed
   * @throws OrderException
   * @throws UserException
   */
  public function sendDeliveryMessage($order, $tplJumpPage = '')
  {
    if (!$order) {
      throw new OrderException();
    }
    $this->tplID = self::DELIVERY_MSG_ID;
    $this->formID = $order->prepay_id;
    $this->page = $tplJumpPage;
    $this->prepareMessageData($order);
    $this->emphasisKeyWord = 'keyword2.DATA';
    return parent::sendMessage($this->getUserOpenID($order->user_id));
  }

  /**
   * 预备数据
   * @param $order
   * @throws \Exception
   */
  private function prepareMessageData($order)
  {
    $dt = new \DateTime();
    $data = [
      'keyword1' => [
        'value' => '顺风速运',
      ],
      'keyword2' => [
        'value' => $order->snap_name,
        'color' => '#27408B'
      ],
      'keyword3' => [
        'value' => $order->order_no
      ],
      'keyword4' => [
        'value' => $dt->format("Y-m-d H:i")
      ]
    ];
    $this->data = $data;

  }

  /**
   * 获取用户订单
   * @param $uid
   * @return mixed
   * @throws UserException
   */
  private function getUserOpenID($uid)
  {
    $user = User::get($uid);
    if (!$user) {
      throw new UserException();
    }
    return $user->openid;
  }
}