<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-04-29
 * Time: 16:16
 */

namespace app\api\validate;


class Category extends BaseValidate
{
	protected $rule = [
		'name' => 'require|isNotEmpty',
		'topic_img_id' => 'isNotEmpty',
		'id' => 'isNotEmpty',
		'description' => 'isNotEmpty',
		'is_check_show' => 'isNotEmpty'
	];
}