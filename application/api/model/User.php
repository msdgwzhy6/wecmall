<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-02-22
 * Time: 13:26
 */

namespace app\api\model;


class User extends BaseModel
{
  protected $hidden = ['create_time', 'delete_time', 'update_time'];

  public function address()
  {
    return $this->hasOne('UserAddress', 'user_id', 'id');
  }

  /**
   * @param $openid
   * @return array|\PDOStatement|string|\think\Model|null
   * @throws \think\db\exception\DataNotFoundException
   * @throws \think\db\exception\ModelNotFoundException
   * @throws \think\exception\DbException
   */
  public static function getByOpenId($openid)
  {
    $user = self::where('openid', '=', $openid)
      ->find();
    return $user;
  }


}