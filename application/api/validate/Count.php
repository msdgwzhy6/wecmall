<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-02-21
 * Time: 10:31
 */

namespace app\api\validate;


class Count extends BaseValidate
{
  // 校验规则
  protected $rule = [
    'count' => 'isPositiveInteger|between:1,15'
  ];

  protected $message = [
    'count' => 'count只能是1-15之间的正整数'
  ];
}