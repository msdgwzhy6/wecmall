<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-02-22
 * Time: 18:04
 */

namespace app\api\model;


class ProductProperty extends BaseModel
{
  public $hidden = ['product_id', 'delete_time', 'id'];

}