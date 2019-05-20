<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-01-31
 * Time: 16:51
 */

namespace app\api\validate;


class IDMustBePositiveInt extends BaseValidate
{

  protected $rule = [
    'id' => 'require|isPositiveInteger'
  ];

  protected $message = [
    'id' => 'id必须是正整数'
  ];
}