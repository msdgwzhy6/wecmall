<?php

namespace app\api\model;


/**
 * Class Image 图片模型
 * @package app\api\model
 */
class Image extends BaseModel
{

  protected $hidden = ['update_time', 'delete_time', 'from', 'id'];

  /**
   * 图片读取器
   * @param $value
   * @param $data
   * @return string
   */
  public function getUrlAttr($value, $data)
  {
    return $this->prefixImgUrl($value, $data);
  }

}
