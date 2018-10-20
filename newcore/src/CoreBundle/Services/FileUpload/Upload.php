<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package Tripod
* create date 2015-7-13
*/
namespace CoreBundle\Services\FileUpload;

use Symfony\Component\Finder\Finder;
use CoreBundle\Services\ServiceBase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;

/**
 * 上传服务
 */
class Upload extends ServiceBase implements FileUploadInterface
{
	/**
	 * 设置上传目录
	 * @param string $rootDir 上传目录
	 */
	public function setRootDir($rootDir)
	{
		if (!$this->filesystem->exists($rootDir)) {
			$this->filesystem->mkdir($rootDir,isset($this->mkdirMod)?$this->mkdirMod:0755,true);
		}
	
		$this->rootDir = $rootDir;
	}
	
	public function getRootDir()
	{
		return $this->rootDir ;
	}
	
	/**
	 * 建立节点
	 * @param array $endpoint ['endpoint_name', 'endpoint_dir_name']
	 */
	public function addEndpoint(array $endpoint)
	{
		if (!isset($this->endpoints[$endpoint[0]])) {
			$endpointDir = $this->rootDir . DIRECTORY_SEPARATOR . $endpoint[1];
			if (!$this->filesystem->exists($endpointDir)) {
				$this->filesystem->mkdir($endpointDir,isset($this->mkdirMod)?$this->mkdirMod:0755,true);
			}
			$this->endpoints[$endpoint[0]] = $endpointDir;
		}
	}
	
	/**
	 * 计算文件大小
	 * @param int $size
	 * @return string
	 */
	public function humanReadableFilesize($size)
	{
		if ($size >= 1073741824) {
			$fileSize = round($size / 1024 / 1024 / 1024,1) . 'GB';
		} elseif ($size >= 1048576) {
			$fileSize = round($size / 1024 / 1024,1) . 'MB';
		} elseif($size >= 1024) {
			$fileSize = round($size / 1024,1) . 'KB';
		} else {
			$fileSize = $size . ' bytes';
		}
	
		return $fileSize;
	}
	
	/**
	 * 文件信息
	 * @param string $endpoint
	 * @param string $filename
	 * @return array
	 * @throws \Exception
	 */
	public function getFilesInfo($endpoint, $filename)
	{
		if (!isset($this->endpoints[$endpoint]))
		    throw new \Exception('存储目录不存在或已删除');
		
		$filename = str_replace("/", "\\", $filename);
		//去掉重复的路径
		if (false !== strpos($filename, $this->endpoints[$endpoint])){
		    $filename = trim(str_replace(array($this->endpoints[$endpoint]),"",$filename),DIRECTORY_SEPARATOR);
		}
	
		$storeDir = $this->endpoints[$endpoint] . DIRECTORY_SEPARATOR . $filename;
		
		$fs = new Filesystem();
	
		if ($fs->exists($storeDir)) {
			$filesInfo = array();
			$files = Finder::create()->files()->in($storeDir);
	
			/** @var SplFileInfo $file */
			foreach ($files as $file) {
				$filesInfo[] = array(
						'name' => $file->getFilename(),
						'size' => $this->humanReadableFilesize($file->getSize()),
				);
			}
	
			return $filesInfo;
		}
	
		return array();
	}
	
	/**
	 * 删除文件
	 * @param string $endpoint
	 * @param string $store_id
	 * @param string $filename
	 * @throws \Exception
	 */
	public function removeFile($endpoint, $filename)
	{
		if (!isset($this->endpoints[$endpoint]))
		    throw new \Exception('存储目录不存在或已删除');
		$filename = str_replace("/", "\\", $filename);
		//去掉重复的路径
		if (false !== strpos($filename, $this->endpoints[$endpoint])){
			$filename = trim(str_replace(array($this->endpoints[$endpoint]),"",$filename),DIRECTORY_SEPARATOR);
		}		

		$storeDir = $this->endpoints[$endpoint] . DIRECTORY_SEPARATOR . $filename;
		
		$fs = new Filesystem();
	
		if ($fs->exists($storeDir)) {
			$fs->remove($storeDir);
		}
	}
	
	/**
	 * 文件变更
	 * @param string $endpoint
	 * @param string $from_store_id
	 * @param string $to_store_id
	 * @throws \Exception
	 */
	public function changeFilesStoreId($endpoint, $from_store_id, $to_store_id)
	{
		if (!isset($this->endpoints[$endpoint])) throw new \Exception('存储目录不存在或已删除');
	
		$subDir = $this->endpoints[$endpoint];
	
		$fromDir = $subDir . DIRECTORY_SEPARATOR . $from_store_id;
		$toDir = $subDir . DIRECTORY_SEPARATOR . $to_store_id;
	
		$fs = new Filesystem();
	
		if ($fs->exists($fromDir)) {
			if ($fs->exists($toDir)) {
				$files = Finder::create()->files()->in($fromDir);
				/** @var SplFileInfo $file */
				foreach ($files as $file) {
					$fs->rename($fromDir . DIRECTORY_SEPARATOR . $file->getFilename(), $toDir . DIRECTORY_SEPARATOR . $file->getFilename(), true);
				}
				$fs->remove($fromDir);
			} else {
				$fs->rename($fromDir, $toDir);
			}
		}
	}
	
	/**
	 * 文件流响应
	 * @param string $endpoint
	 * @param string $store_id
	 * @param string $filename
	 * @return Response
	 * @throws \Exception
	 */
	public function getFilestreamResponse($endpoint, $store_id, $filename)
	{
		if (!isset($this->endpoints[$endpoint]))
		    throw new \Exception('存储目录不存在或已删除');
	
		$file = $this->endpoints[$endpoint] . DIRECTORY_SEPARATOR . $store_id . DIRECTORY_SEPARATOR . $filename;
		$fs = new Filesystem();
	
		if (!$fs->exists($file)) throw new \Exception('文件不存在或已被删除');
	
		$response = new Response();
		$response->headers->set('Cache-Control', 'no-cache');
		$response->headers->set('Content-Type', mime_content_type($file));
		$response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');
		$response->headers->set('Content-Length', filesize($file));
	
		$response->sendHeaders();
	
		$response->setContent(readfile($file));
	
		return $response;
	}
}