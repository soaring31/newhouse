<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package Tripod
* create date 2015-7-13
*/
namespace CoreBundle\Services\FileUpload;

use CoreBundle\Services\Image\Image;
use Symfony\Component\Filesystem\Filesystem;


/**
 * 上传服务
 */
class FileUpload extends Upload
{
	 //上传目录
	protected $rootDir;

	//节点集
	protected $endpoints;

	//文件系统指针
	protected $filesystem;

	//保存文件名
	protected $saveName = array();

	//保存路径
	protected $savePath = '';

	//错误代码
	protected $errorNum = -1;

	//创建文件权限属性
	protected $mkdirMod = "0755";

	//允许最大字符数,默认1M
	public $maxSize = 1048576;

	public $maxPictureSize = 1048576;

	//是否使用随机文件名,还是直接使用上传文件的名称
	protected $rename = true;

	//允许类型
	public $allowExts;

	//上传文件大小
	protected $fileSize = 0;

	//上传文件类型
	protected $fileType;

	//缩略图标识
	public $thumbFlag = "thumb_";

	//缩略图宽
	public $thumbMaxWidth = 0;

	//缩略图高
	public $thumbMaxHeight = 0;

	private $fileTmpName = array();

	//结果
	protected $result = array(
	    'status'=>false,
	    'info'=>'',
	    'path'=>'',
	    'name'=>'',
	    'filename'=>''
	);

	//错误消息
	protected $errorInfo   = array(
			//系统错误消息
			'0' => '上传成功',
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


	public function __construct($container)
	{
		$this->filesystem = new Filesystem();
		$this->container = $container;
		$this->matchingFile();
	}

	/**
	 * 处理图片上传处理请求
	 * @param string $endpoint 节点
	 * @param string $savePath 保存路径
	 */
	public function handleUploadImgRequest($endpoint, $savePath, $fileTmp, $saveName)
	{
		//$this->errorNum = -2;
		if (!function_exists('imagecreate'))
		    throw new \Exception('php不支持gd库，请配置后再使用');

		//判断节点是否存在，不存在则自动创建
		if (!isset($this->endpoints[$endpoint]))
			$this->addEndpoint(array($endpoint,$endpoint));

		//设置保存路径
		$this->savePath = $this->endpoints[$endpoint] . DIRECTORY_SEPARATOR . $savePath;

		//生成上传目录
		$this->setRootDir($this->savePath);

		//检查所有
		$check = $this->checkAll();

		if(!(bool)$check)
		    return $this->getResult();

		//判断是否是通过HTTP POST上传的
// 		if(!@is_uploaded_file($fileTmp)){
// 		    //如果不是通过HTTP POST上传的
// 		    return ;
// 		}

		//开始移动文件到相应的文件夹
		if(move_uploaded_file($fileTmp, $this->savePath.DIRECTORY_SEPARATOR.$saveName))
		    $this->result['status'] = true;
		else
		    $this->result['status'] = false;

		return true;
		//加载图片处理服务
		$img = new Image($fileTmp);

		//生成图片
		$img->makeThumb($this->savePath.DIRECTORY_SEPARATOR.$saveName);

		$this->result['status'] = true;
	}

	/**
	 * 处理文件上传处理请求
	 * @param string $endpoint 节点
	 * @param string $savePath 保存路径
	 * @throws \Exception
	 */
	public function handleUploadRequest($endpoint, $savePath)
	{
	    foreach($this->fileTmpName as $key=>$fileTmp)
	    {
    	    try {
        	    //判断是否为图片
        	    $imginfo = getimagesize($fileTmp);
    	    } catch (\Exception $e) {
    	        $imginfo = false;
    	    }

    	    //检查所有
    	    $check = $this->checkAll();

    	    if(!(bool)$check)
    	        return $this->getResult();

			$fileMax = $this->get('core.common')->C('up_file_size');
			$imageMax = $this->get('core.common')->C('up_image_size');
    	    if($imginfo){
    	        $this->maxSize = $fileMax * 1048576 ? $fileMax * 1048576 : $this->maxPictureSize;
    	        $this->handleUploadImgRequest($endpoint, $savePath, $fileTmp, $this->saveName[$key]);
    	    }else{
    	        // $this->maxSize = $this->maxPictureSize;
    	        $this->maxSize = $imageMax * 1048576 ? $imageMax * 1048576 : $this->maxPictureSize;
    	        $this->handleUploadFileRequest($endpoint, $savePath, $fileTmp, $this->saveName[$key]);
    	    }
	    }

	    return $this->getResult();
	}

	/**
	 * 文件上传
	 * @param string $endpoint 节点名
	 * @param string $savePath 保存路径
	 * @throws \Exception
	 */
	public function handleUploadFileRequest($endpoint, $savePath, $fileTmp, $saveName)
	{
	    $this->errorNum = -2;

	    //判断节点是否存在，不存在则自动创建
	    if (!isset($this->endpoints[$endpoint]))
	        $this->addEndpoint(array($endpoint,$endpoint));

	    //设置保存路径
	    $this->savePath = $this->endpoints[$endpoint] . DIRECTORY_SEPARATOR . $savePath;

	    //生成上传目录
	    $this->setRootDir($this->savePath);

	    //检查所有
	    $check = $this->checkAll();

	    if(!(bool)$check)
	        return $this->getResult();

	    @move_uploaded_file($fileTmp, $this->savePath.DIRECTORY_SEPARATOR.$saveName);

	    $this->result['status'] = true;
	}

	/**
	 * 处理文件远程上传处理请求
	 * @param string $endpoint 节点
	 * @param string $savePath 保存路径
	 * @throws \Exception
	 */
	public function handleRemoteUploadRequest($option)
	{
	    foreach($this->fileTmpName as $key=>$fileTmp)
	    {
	        try {
                //判断是否为图片
                $imginfo = getimagesize($fileTmp);
    	    } catch (\Exception $e) {
                $imginfo = false;
    	    }

    	    //检查所有
    	    $check = $this->checkAll();

    	    if(!(bool)$check)
    	        return $this->getResult();

			$fileMax = $this->get('core.common')->C('up_file_size');
			$imageMax = $this->get('core.common')->C('up_image_size');
			
			if ($imginfo)
			    $this->maxSize = $fileMax * 1048576 ? $fileMax * 1048576 : $this->maxPictureSize;
			else
			    $this->maxSize = $imageMax * 1048576 ? $imageMax * 1048576 : $this->maxPictureSize;
	        
	        //获取提交信息
	        $fileType = $_FILES['photo']['type'][0];
	        $fileName = $_FILES['photo']['name'][0];
	        $postUrl = $option['up_remote_url']."?token=".$option['up_remote_token'];
	        
	        //php 5.5以上的用法
	        if(class_exists('\CURLFile'))
	            $postData = array('photo' => new \CURLFile(realpath($fileTmp), $fileType, $fileName));
	        else
	            $postData = array('photo' => '@'.realpath($fileTmp).";type=".$fileType.";filename=".$fileName);
	         
	        header('content-type:text/html;charset=utf8');
	        $curl = curl_init();
	        curl_setopt($curl, CURLOPT_URL, $postUrl);
	        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	        curl_setopt($curl, CURLOPT_POST, true);
	        curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
	        curl_exec($curl);
	        $data = curl_multi_getcontent($curl);
	        curl_close($curl);
	        
	        //构造返回信息
	        $length = strpos($data, "\n");
	        $str = substr($data, 0, $length);
	        $fileData = explode("|", $str);
	        
	        $saveName = $fileData[2];
	        $saveFileName = $fileData[1];
	        
	        $saveFilePath = str_replace($saveName, "", $saveFileName);
	        $saveFilePath = substr($saveFilePath, 0, strlen($saveFilePath)-1);
	        $saveFilePath = str_replace("/", "\\", $saveFilePath);
	        
	        $this->savePath = $saveFilePath;
	        $this->saveName[$key] = $saveName;
	        
	        $this->result['status'] = true;
	    }
	    
	    return $this->getResult();
	}
	
	/*
	 * 获取上传结果
	* @return array $result
	*/
	public function getResult()
	{
		$filename = array();

		foreach($this->saveName as $key=>$item)
		{
			$filename[$key] = str_replace("\\", "/", $this->savePath?$this->savePath.DIRECTORY_SEPARATOR.$item:'');
		}

		$this->result['info'] = $this->errorInfo[$this->errorNum];
		$this->result['path'] = $this->savePath;
		$this->result['name'] = $this->saveName;
		$this->result['size'] = $this->fileSize;
		$this->result['filename'] = $filename;
		return $this->result;
	}

	/*
	 * 所有检查步骤
	* @return bool
	*/
	private function checkAll()
	{
		return $this->checkUploadErr()
		&& $this->checkSize() && $this->checkExtention();
	}

	/*
	 * 匹配上传文件
	* @return bool
	*/
	private function matchingFile()
	{
		$this->errorNum = -2;
		$upload = !empty($_FILES)?current($_FILES):array();

		if(empty($upload))
		    return false;

		$upload['name'] = is_array($upload['name'])?$upload['name']:array($upload['name']);


		$this->fileName = $this->saveName;
		$this->fileTmpName = is_array($upload['tmp_name'])?$upload['tmp_name']:array($upload['tmp_name']);
		$this->fileSize = is_array($upload['size'])?$upload['size']:array($upload['size']);
		$this->fileType = $this->_getExtention($upload['name']);
		$this->errorNum = is_array($upload['error'])?$upload['error']:array($upload['error']);
		$this->saveName = $this->rename?$this->_randName($upload['name']):$upload['name'];//@iconv('UTF-8','gb2312',$upload['name']);
		return true;
	}

	//获得
	public function getName($endpoint){

		foreach($this->fileTmpName as $fileTmp)
		{
			//判断是否为图片
			getimagesize($fileTmp);


			//检查所有
			$check = $this->checkAll();

			if(!(bool)$check)
				return $this->getResult();
		}
		//获取中心访问域名
		$domainname = $this->get('core.common')->C('up_domainname');

		$newInfo = $this->getResult();
		$newInfo['filename'][0] ='http://'.$domainname.'/'.$newInfo['name'][0];
		$newInfo['tmp_name'][0] = $_FILES['photo']['tmp_name'][0];

		return $newInfo;
	}


	/*
	 * 获取文件扩展名
	* @return 返回小写文件名
	*/
	private function _getExtention($fileName)
	{
	    $data = array();

	    foreach($fileName as $key=>$item)
	    {
    		$fileInfo = pathinfo($item);
    		if(!isset($fileInfo['extension']))
    		    throw new \Exception('未选择上传文件');

    		$data[$key] = strtolower($fileInfo['extension']);
	    }
		return $data;
	}

	/*
	 * 检查上传中的错误
	*/
	private function checkUploadErr()
	{
	    $res = true;

	    if(is_array($this->errorNum))
	    {
    	    foreach($this->errorNum as $num)
    	    {
    	        if($num>0)
    		        $res = false;

    	        $this->errorNum = $num;
    	    }
	    }

	    return $res;
	}

	/*
	 * 检查文件大小
	*/
	private function checkSize()
	{
	    $res = true;
	    foreach($this->fileSize as $size)
	    {
	        if($this->maxSize < $size)
	            $res = false;
	    }

		if(!$res){
			$this->errorNum = -3;
			$this->errorInfo['-3'] .= $this->humanReadableFilesize($this->maxSize);
		}
		return $res;
	}

	/*
	 * 检查文件类型
	*/
	private function checkExtention()
	{
	    $res = true;
	    foreach($this->fileType as $extentionType)
	    {
	        if(!in_array($extentionType, $this->allowExts, true))
	            $res = false;
	    }

		if(!$res){
			$this->errorNum = -4;
			$this->errorInfo['-4'] .= implode('|', $this->allowExts);
		}

		return $res;
	}

	public function getFileType()
	{
	    return $this->fileType;
	}

	/*
	 * 生成日期随机文件名
	*/
	private function _randName($name)
	{
	    foreach($name as $key=>$item)
	    {
	        $name[$key] = sha1(sha1($item.time())).'.'. $this->fileType[$key];
	    }

	    return $name;
	}
}