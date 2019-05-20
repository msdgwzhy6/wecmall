<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-03-12
 * Time: 17:45
 */

namespace app\api\validate;


class PagingParameter extends BaseValidate
{
  protected $rule = [
    'page' => 'isPositiveInteger',
    'size' => 'isPositiveInteger'
  ];

  protected $message = [
    'page' => '分页参数必须为正整数',
    'size' => '分页参数必须为正整数'
  ];
}