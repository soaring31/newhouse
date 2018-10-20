<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package Tripod
* create date 2015-7-13
*/
namespace CoreBundle\Services\ImgUpload;

use CoreBundle\Services\FileUpload\Upload;
use CoreBundle\Services\Image\Image;

/**
 * 图片上传
 *
 */
class ImgUploadManager extends Upload
{
	//默认允许类型
	protected $allowType	= array('gif','jpg','jpeg','bmp','png');
	//创建文件权限属性
	protected $mkdir_mod	= "0755";
	
	//允许最大字符数,默认1M
	protected $maxSize		= 1048576;
	
	//保存文件名
	protected $saveName    = '';

	//保存路径
	protected $savePath    = '';
	
	//错误代码
	protected $errorNum    = -1;
	
	//错误消息
	protected $errorInfo   = array(
			//系统错误消息
			'0' => '没有错误发生',
			'1' => '上传文件大小超出系统限制', //php.ini中upload_max_filesize
			'2' => '上传文件大小超出网页表单限制', //HTML表单中规定的MAX_FILE_SIZE
			'3' => '文件只有部分被上传',
			'4' => '没有文件被上传',
			'5' => '上传文件大小为0',
			'6' => '找不到临时文件夹',
			'7' => '文件写入失败',
	
			//自定义错误消息
			'-1' => '未知错误',
			'-2' => '未找到相应的文件域',
			'-3' => '文件大小超出允许范围:',
			'-4' => '文件类型不符,只允许:',
			'-5' => '未指定上传目录',
			'-6' => '创建目录失败',
			'-7' => '目录不可写',
			'-8' => '临时文件不存在',
			'-9' => '存在同名文件,取消上传',
			'-10' => '文件移动失败',
	);
	
	/*
	 * 构造函数,设置上传的相关设定
	* @param string $allowTypes	允许的扩展名,使用|分隔
	* @param int		$maxSize	允许文件大小,字节
	* @param bool		$rename		是否重命名
	* @param bool		$overWrite	是否允许覆盖同名文件
	*/
	public function __construct($allowTypes =null, $maxSize =null, $rename = true, $overWrite = true)
	{
		parent::__construct();
		//允许的扩展名
		$this->allowType = $allowTypes?array_unique(explode('|', strtolower($allowTypes))):$this->allowType;
	
		//允许的大小
		$this->maxSize   = $maxSize?(int)$maxSize:$this->maxSize;
	
		//是否重命名
		$this->rename    = (bool)$rename;
	
		//是否允许覆盖同名文件
		$this->overWrite = (bool)$overWrite;
	}
	
	
	/*
	 * 获取上传结果
	* @return array $result
	*/
	public function getResult(){
		$this->result['info'] = $this->errorInfo[$this->errorNum];
		$this->result['path'] = $this->savePath;
		$this->result['name'] = $this->saveName;
		$this->result['size'] = $this->fileSize;
		return $this->result;
	}
	
	/*
	 * 文件上传开始
	* @param string $endpoint		上传文件目录的节点
	* @param string $savePath		文件保存路径
	* @param bool	$waterEnable	是否启用水印,0不启用，1启用
	* @return array $result
	* @$type 1为加图片水印
	* @$stag_id 1正面，2背面
	*/
	//public function imgupload($formName, $uid=0, $type=0, $water='',$stag_id=1){
	public function imgupload($endpoint, $savePath, $waterEnable=0){
		if (!isset($this->endpoints[$endpoint])) throw new \Exception('存储目录不存在或已删除');
	
		//生成上传目录
		$uploadDir = $this->endpoints[$endpoint] . DIRECTORY_SEPARATOR . $savePath;
	
		//设置上传目录
		$this->setRootDir($uploadDir);
	
		$check = $this->checkAll($uploadDir);
		// 		$this->water = $_SERVER['DOCUMENT_ROOT'].$water;
		if(!$check) return $this->getResult();
		$this->save($waterEnable);
		// 		return $this->getResult();
	}
	
	/*
	 * 保存文件
	*/
	private function save($waterEnable){
		//是否存在上传的临时文件
		if(!file_exists($this->fileTmpName)){
			$this->errorNum = -8;
			return false;
		}
	
		//判断是否使用随机文件名
		if($this->rename){
			$this->saveName = $this->_randName();
		}
		//新文件
		$newFile  = $this->savePath . DIRECTORY_SEPARATOR . $this->saveName;
		//源文件
		$sourceFile = $this->savePath . DIRECTORY_SEPARATOR. "source_" . $this->saveName;
		//是否覆盖同名文件
		if(file_exists($newFile) && !$this->overWrite){
			$this->errorNum = -9;
			return false;
		}
	
		//$waterEnable==1为加水印功能
		if($waterEnable==1){
			$res = @move_uploaded_file($this->fileTmpName, $sourceFile);
			$this->img = $sourceFile;
			$this->thumb($sourceFile,600,400);
			$this->water($newFile,5);
		}else{
			$res = @move_uploaded_file($this->fileTmpName, $sourceFile);
		}
	
		if(!$res)
			$this->errorNum = -10;
		else
			$this->result['status'] = true;
	
		return $res;
	}
	
	/*
	 * 所有检查步骤
	* @return bool
	*/
	private function checkAll($uploadDir){
		//设置保存路径
		$this->savePath = $uploadDir;
		return $this->matchingFile() && $this->checkUploadErr()
		&& $this->checkSize() && $this->checkExtention();
	}
	
	/*
	 * 匹配上传文件
	* @return bool
	*/
	private function matchingFile(){
		$this->errorNum = -2;
		$upload = !empty($_FILES)?current($_FILES):array();
	
		if(empty($upload)) return false;
	
		$this->saveName		= $upload['name'];//@iconv('UTF-8','gb2312',$upload['name']);
		$this->fileName		= $this->saveName;
		$this->fileTmpName	= $upload['tmp_name'];
		$this->fileSize		= $upload['size'];
		$this->fileType		= $this->_getExtention($this->fileName);
		$this->errorNum		= $upload['error'];
		return true;
	}
	
	/*
	 * 获取文件扩展名
	* @return 返回小写文件名
	*/
	private function _getExtention($fileName){
		$fileInfo = pathinfo($fileName);
		return strtolower($fileInfo['extension']);
	}
	
	/*
	 * 生成日期随机文件名
	*/
	private function _randName(){
		return date('YmdHis') .'_'. mt_rand(100, 1000) .'.'. $this->fileType;
	}
	
	/*
	 * 检查上传中的错误
	*/
	private function checkUploadErr(){
		$uploadErr = range(1,7);
		$res       = !in_array($this->errorNum, $uploadErr);
		return $res;
	}
	
	/*
	 * 检查文件大小
	*/
	private function checkSize(){
		$res = ($this->maxSize > $this->fileSize);
		if(!$res){
			$this->errorNum = -3;
			$this->errorInfo['-3'] .=  $this->humanReadableFilesize();
		}
	
		return $res;
	}
	
	/*
	 * 检查文件类型
	*/
	private function checkExtention(){
		$res = in_array($this->fileType, $this->allowType);
		if(!$res){
			$this->errorNum = -4;
			$this->errorInfo['-4'] .=  implode('|', $this->allowType);
		}
		return $res;
	}
}




class ImgUploadManageraa extends Upload
{
	// 当前图片
	protected $img;

	//创建文件权限属性
	protected $mkdir_mod	= "0755";
	protected $result      = array('status'=>false, 'info'=>'','path'=>'','name'=>'');	//结果
	protected $allowType   = array('gif','jpg','jpeg','bmp','png');	//默认允许类型
	protected $overWrite   = false;	//是否允许覆盖同名文件
	protected $rename      = true;	//是否使用随机文件名,还是直接使用上传文件的名称
	protected $maxSize     = 0;	//允许最大字符数,默认1M
	protected $saveName    = '';	//保存文件名
	protected $savePath    = '';	//保存路径
	protected $fileName    = ''; //文件名
	protected $fileTmpName = ''; //临时文件名
	protected $fileSize    = 0;	//文件大小
	protected $fileType    = '';	//文件类型
	
	
	// 图像types 对应表
	protected $types = array(
			1 => 'gif',
			2 => 'jpg',
			3 => 'png',
			6 => 'bmp'
	);
	
	
	/*
	 * 构造函数,设置上传的相关设定
	* @param string $allowTypes	允许的扩展名,使用|分隔
	* @param int		$maxSize		允许文件大小,字节
	* @param bool		$rename			是否重命名
	* @param bool		$overWrite		是否允许覆盖同名文件
	* @param bool		$waterEnable	是否启用水印,0不启用，1启用
	*/
	public function __construct($allowTypes ='gif|jpg|jpeg|png|bmp', $maxSize =1048576, $rename = true, $overWrite = true, $waterEnable=true)
	{
		parent::__construct();

		//允许的扩展名
		$this->allowType = $allowTypes?array_unique(explode('|', strtolower($allowTypes))):$this->allowType;
	
		//允许的大小
		$this->maxSize   = (int)$maxSize;
	
		//是否重命名
		$this->rename    = (bool)$rename;
	
		//是否允许覆盖同名文件
		$this->overWrite = (bool)$overWrite;
	}
	
	/*
	 * 获取上传结果
	* @return array $result
	*/
	public function getResult(){
		$this->result['info'] = $this->errorInfo[$this->errorNum];
		$this->result['path'] = $this->savePath;
		$this->result['name'] = $this->saveName;
		$this->result['size'] = $this->fileSize;
		return $this->result;
	}
	
	/*
	 * 文件上传开始
	* @param string $endpoint		上传文件目录的节点
	* @param string $savePath		文件保存路径
	* @param bool	$waterEnable	是否启用水印,0不启用，1启用
	* @return array $result
	*/
	//public function imgupload($formName, $uid=0, $type=0, $water='',$stag_id=1){
	public function imgupload($endpoint, $savePath, $waterEnable=0){
		if (!isset($this->endpoints[$endpoint])) throw new \Exception('存储目录不存在或已删除');
		
		//生成上传目录
		$uploadDir = $this->endpoints[$endpoint] . DIRECTORY_SEPARATOR . $savePath;
		
		//设置上传目录
		$this->setRootDir($uploadDir);
		
		$check = $this->checkAll($uploadDir);

 		if(!$check) return $this->getResult();
 		$this->save($waterEnable);
	}
	
	/*
	 * 保存文件
	*/
	private function save($waterEnable){
		//是否存在上传的临时文件
		if(!file_exists($this->fileTmpName)){
			$this->errorNum = -8;
			return false;
		}
		
		$img = new Image($this->fileTmpName);
		
		$img->parse();
	
		//判断是否使用随机文件名
		if($this->rename){
			$this->saveName = $this->_randName();
		}
		//新文件
		$newFile  = $this->savePath . DIRECTORY_SEPARATOR . $this->saveName;
		//源文件
		$sourceFile = $this->savePath . DIRECTORY_SEPARATOR. "source_" . $this->saveName;
		//是否覆盖同名文件
		if(file_exists($newFile) && !$this->overWrite){
			$this->errorNum = -9;
			return false;
		}
	
		//$waterEnable==1为加水印功能
		if($waterEnable==1){
			$res = @move_uploaded_file($this->fileTmpName, $sourceFile);
			$this->img = $sourceFile;
			$this->thumb($sourceFile,600,400);
			$this->water($newFile,5);
		}else{
			$res = @move_uploaded_file($this->fileTmpName, $sourceFile);
		}
	
		if(!$res)
			$this->errorNum = -10;
		else
			$this->result['status'] = true;
	
		return $res;
	}
	
	/*
	 * 所有检查步骤
	 * @return bool
	 */
	private function checkAll($uploadDir){
		//设置保存路径
		$this->savePath = $uploadDir;
		return $this->matchingFile() && $this->checkUploadErr()
				&& $this->checkSize() && $this->checkExtention();
	}
	
	/*
	 * 匹配上传文件
	 * @return bool
	 */
	private function matchingFile(){
		$this->errorNum = -2;
		$upload = !empty($_FILES)?current($_FILES):array();

		if(empty($upload)) return false;

		$this->saveName		= $upload['name'];//@iconv('UTF-8','gb2312',$upload['name']);
		$this->fileName		= $this->saveName;
		$this->fileTmpName	= $upload['tmp_name'];
		$this->fileSize		= $upload['size'];
		$this->fileType		= $this->_getExtention($this->fileName);
		$this->errorNum		= $upload['error'];
		return true;
	}
	
	/*
	 * 获取文件扩展名
	 * @return 返回小写文件名
	 */
	private function _getExtention($fileName){
		$fileInfo = pathinfo($fileName);
		return strtolower($fileInfo['extension']);
	}
	
	/*
	 * 生成日期随机文件名
	*/
	private function _randName(){
		return date('YmdHis') .'_'. mt_rand(100, 1000) .'.'. $this->fileType;
	}
	
	/*
	 * 检查上传中的错误
	 */
	private function checkUploadErr(){
		$uploadErr = range(1,7);
		$res       = !in_array($this->errorNum, $uploadErr);
		return $res;
	}
	
	/*
	 * 检查文件大小
	 */
	private function checkSize(){
		$res = ($this->maxSize > $this->fileSize);
		if(!$res){
			$this->errorNum = -3;
			$this->errorInfo['-3'] .=  $this->humanReadableFilesize();
		}
	
		return $res;
	}
	
	/*
	 * 检查文件类型
	*/
	private function checkExtention(){
		$res = in_array($this->fileType, $this->allowType);
		if(!$res){
			$this->errorNum = -4;
			$this->errorInfo['-4'] .=  implode('|', $this->allowType);
		}
		return $res;
	}
}