<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-02-20
 * Time: 16:06
 */

namespace app\api\validate;


class IDCollection extends BaseValidate
{
  protected $rule = [
    'ids' => 'require|checkIds'
  ];

  protected $message = [
    'ids' => 'ids参数必须是以逗号分隔的多个正整数'
  ];

  protected function checkIds($value, $rule = '', $data = '', $filed = '')
  {
    $value = explode(',', $value);
    if (empty($value)) {
      return false;
    }

    // 循环检查是否未正整数
    foreach ($value as $id) {
      if (!$this->isPositiveInteger($id)) {
        return false;
      }
    }

    return true;
  }
}