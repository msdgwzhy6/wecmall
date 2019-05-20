<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-05-10
 * Time: 18:02
 */

namespace app\api\service;

use app\api\model\Image;
use Qiniu\Auth;
use Qiniu\Storage\BucketManager;
use Qiniu\Storage\UploadManager;
use think\Log;

require_once APP_PATH . '../vendor/qiniu/autoload.php';

class Common
{

	private $accessKey = 'lwKMAOMz7zkL3341bmaIl_UNURRCxEHg35WzyWaH';
	private $secretKey = 'pJdAcMjFL24ENIPT8gmfc3CkbPsbwP64snhbvy2y';
	private $bucket = 'yawenpobing';

	/**
	 * 上传文件
	 * @param $file  文件
	 * @return array
	 * @throws \Exception
	 */
	public function upload($file, $type)
	{
		$auth = new Auth($this->accessKey, $this->secretKey);
		// 创建token
		$token = $auth->uploadToken($this->bucket);

		$uploadMgr = new UploadManager();

		//初始化BucketManager
		$bucketMgr = new BucketManager($auth);

		$info = $file->getInfo();
		$ext = pathinfo($file->getInfo('name'), PATHINFO_EXTENSION);  //后缀
		// 上传到七牛后保存的文件名
		$key = substr(md5($file->getRealPath()), 0, 5) . date('YmdHis') . rand(0, 9999) . '.' . $ext;
		// 需要填写你的 Access Key 和 Secret Key
		$filePath = $file->getRealPath();
		$fullKey = $type . '/' . $key;
		$result = $uploadMgr->putFile($token, $fullKey, $filePath);
		// 根据配置信息把文件存到数据库中
		$image = [
			'url' => $result[0]['key'],
			'from' => config('setting.store_type')['type']
		];

		$data = Image::create($image);
		return $data->id;
	}
}