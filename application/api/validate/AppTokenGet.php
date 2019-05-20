<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-03-20
 * Time: 17:22
 */

namespace app\api\validate;


class AppTokenGet extends BaseValidate
{
  protected $rule = [
    'ac' => 'require|isNotEmpty',
    'se' => 'require|isNotEmpty'
  ];

  protected $message = [
    'ac' => '用户名不能为空',
    'se' => '用户名不能为空'
  ];
}