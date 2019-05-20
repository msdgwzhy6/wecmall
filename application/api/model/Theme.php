<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-02-19
 * Time: 22:16
 */

namespace app\api\model;

/**
 * Class Theme 主题模型
 * @package app\api\model
 */
class Theme extends BaseModel
{

  // 设置隐藏字段
  protected $hidden = ['update_time', 'delete_time', 'topic_img_id', 'head_img_id'];

  // 获取顶部的图片
  public function topicImg()
  {
    return $this->belongsTo('Image', 'topic_img_id', 'id');
  }

  // 获取头部的图片
  public function headImg()
  {
    return $this->belongsTo('Image', 'head_img_id', 'id');
  }



  // 定义 主题产品的多对多关系
  public function products()
  {
    return $this->belongsToMany('Product', 'theme_product', 'product_id', 'theme_id');
  }

  /**
   * 查询 主题信息
   * @param $id
   * @return mixed
   * @throws \think\db\exception\DataNotFoundException
   * @throws \think\db\exception\ModelNotFoundException
   * @throws \think\exception\DbException
   */
  public static function getThemeWithProducts($id)
  {
    $theme = self::with('products,headImg,topicImg')->find($id);
    return $theme;
  }
}