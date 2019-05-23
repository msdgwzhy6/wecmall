<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-05-23
 * Time: 16:36
 */

namespace app\api\validate;


class UserNew extends BaseValidate
{

	protected $rule = [
		'username' => 'require|isNotEmpty',
		'password' => 'require|isNotEmpty'
	];

	protected $message = [
		'username' => '用户名不能为空',
		'password' => '密码不能为空'
	];
}