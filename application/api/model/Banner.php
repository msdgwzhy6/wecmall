<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-02-11
 * Time: 11:41
 */

namespace app\api\model;

/**
 * @OA\Schema()
 */
class Banner extends BaseModel
{

//  protected $table = 'banner_item';
  protected $hidden = ['delete_time', 'update_time'];

  /**
   * @return \think\model\relation\HasMany 关联
   */
  public function items()
  {
    return $this->hasMany('BannerItem', 'banner_id', 'id');
  }

  /**
   * 获取轮播图数据
   * @params $id 轮播图Id
   * @return object 返回数据
   * @throws
   */
  public static function getBannerById($id)
  {
    $banner = self::with(['items', 'items.img'])
      ->find($id);

    return $banner;
  }
}