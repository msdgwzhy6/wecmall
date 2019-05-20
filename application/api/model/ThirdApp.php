<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-03-20
 * Time: 17:30
 */

namespace app\api\model;


class ThirdApp extends BaseModel
{
  public static function check($ac, $se)
  {
    $app = self::where('app_id', '=', $ac)
      ->where('app_secret', '=', $se)
      ->find();

    return $app;
  }
}