<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-03-20
 * Time: 21:19
 */

namespace app\api\behavior;


class CORS
{
  public function appInit(&$params)
  {
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: token,source,Origin, X-Requested-With, Content-Type, Accept");
    header('Access-Control-Allow-Methods: OPTIONS,POST,GET,PUT,DELETE');
    if (request()->isOptions()) {
      exit();
    }
  }
}