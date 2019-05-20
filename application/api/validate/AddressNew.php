<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-02-25
 * Time: 16:33
 */

namespace app\api\validate;


class AddressNew extends BaseValidate
{
  protected $rule = [
    'name' => 'require|isNotEmpty',
    'mobile' => 'require|isNotEmpty',
    'province' => 'require|isNotEmpty',
    'city' => 'require|isNotEmpty',
    'country' => 'require|isNotEmpty',
    'detail' => 'require|isNotEmpty'
  ];
}