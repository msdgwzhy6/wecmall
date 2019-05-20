<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-02-27
 * Time: 11:12
 */

namespace app\api\model;


class UserAddress extends BaseModel
{
  protected $hidden = ['id', 'delete_time', 'user_id'];
}