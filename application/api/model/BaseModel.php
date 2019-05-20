<?php

namespace app\api\model;

use think\Model;


/**
 * Class BaseModel Model类的基类
 * @package app\api\model
 */
class BaseModel extends Model
{

	protected $hidden = ['update_time', 'delete_time'];

	/**
	 * 图片读取器
	 * @param $value
	 * @param $data
	 * @return string
	 */
	protected function prefixImgUrl($value, $data)
	{
		// 1 本地图片 2 网络 3 七牛 4 阿里云
		if ($data['from'] == 1) {
			return config('setting.img_prefix') . $value;
		} else if ($data['from'] == 3) {
			return config('setting.img_prefix_qiniu') . $value;
		}
		return $value;
	}
}
