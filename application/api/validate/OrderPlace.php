<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-02-27
 * Time: 15:07
 */

namespace app\api\validate;


use app\lib\exception\ParameterException;

class OrderPlace extends BaseValidate
{
  protected $rule = [
    'products' => 'require|checkProducts'
  ];

  protected $singleRule = [
    'product_id' => 'require|isPositiveInteger',
    'count' => 'require|isPositiveInteger'
  ];

  /**
   * 校验产品列表
   * @param $values
   * @return bool
   * @throws ParameterException
   */
  protected function checkProducts($values)
  {
    if (!is_array($values)) {
      throw new ParameterException([
        'msg' => '商品参数不正确'
      ]);
    }
    if (empty($values)) {
      throw new ParameterException([
        'msg' => '商品列表不能为空'
      ]);
    }

    foreach ($values as $value) {
      $this->checkProduct($value);
    }
    return true;
  }

  /**
   * 校验单个产品参数
   * @param $value
   * @throws ParameterException
   */
  protected function checkProduct($value)
  {
    $validate = new BaseValidate($this->singleRule);
    $result = $validate->check($value);
    if (!$result) {
      throw new ParameterException([
        'msg' => '商品参数不正确'
      ]);
    }
  }
}