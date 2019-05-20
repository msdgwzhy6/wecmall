<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-05-10
 * Time: 17:51
 */

namespace app\api\controller\v1;

use app\api\service\Common as CommonService;

class Common
{
	/**
	 * 上传文件
	 * @return array
	 * @throws \Exception
	 */
	public function uploadFile()
	{
		$file = request()->file('file');
		$type = input('post.type');
		$service = new CommonService();
		$result = $service->upload($file, $type);

		return $result;
	}
}