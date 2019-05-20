<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-02-22
 * Time: 13:20
 */

namespace app\api\validate;


class TokenGet extends BaseValidate
{
  public $rule = [
    'code' => 'require|isNotEmpty'
  ];

  public $message = [
    'code' => 'code不能为空'
  ];
}