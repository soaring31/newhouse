<?php
/**
* 缩略图生成方式
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package Tripod
* create date 2015-7-13
*/
namespace CoreBundle\Services\Image;

/**
 * 图片缩略图处理
 */
class ImageThumb
{
	const TYPE_INTACT = 1; //等比缩略
	const TYPE_CENTER = 2; //居中截取
	const TYPE_DENGBI = 3; //等比填充
	
	protected $image;
	
	protected $dstfile;
	protected $width;
	protected $height;
	protected $type;
	protected $quality = 90;
	protected $forcemode = 0;
	
	protected $thumbWidth;
	protected $thumbHeight;
	
	protected $imageCreateFunc;
	protected $imageCopyFunc;
	protected $imageFunc;
	
	public function __construct(Image $image) {
		$this->image = $image;
	}
	
	/**
	 * 设置缩略图目标地址
	 */
	public function setDstFile($dstfile) {
		$this->dstfile = $dstfile;
	}
	
	/**
	 * 设置宽度
	 */
	public function setWidth($width) {
		$this->width = intval($width);
	}
	
	/**
	 * 设置高度
	 */
	public function setHeight($height) {
		$this->height = intval($height);
	}
	
	/**
	 * 设置缩略方式 <1.等比缩略 2.居中截取 3.等比填充>
	 */
	public function setType($type) {
		$this->type = $type;
	}
	
	/**
	 * 设置图片质量
	 */
	public function setQuality($quality) {
		$quality > 0 && $this->quality = $quality;
	}
	
	/**
	 * 是否启用强制模式 <0.当文件尺寸小于缩略要求时，不生成 1.都生成>
	 */
	public function setForceMode($forcemode) {
		$this->forcemode = $forcemode;
	}
	
	/**
	 * 生成缩略图
	 */
	public function execute() {
		if (!$this->dstfile) {
			return -1;
		}
		if ($this->width <= 0 && $this->height <= 0) {
			return -2;
		}
		if (!$this->checkEnv()) {
			return -3;
		}
		if (($compute = $this->compute()) === false) {
			return -4;
		}
		$this->thumbWidth = $compute->canvasW;
		$this->thumbHeight = $compute->canvasH;
	
		$thumb = call_user_func($this->imageCreateFunc, $compute->canvasW, $compute->canvasH);
		if (function_exists('ImageColorAllocate')) {
			$black = ImageColorAllocate($thumb,255,255,255);
			if ($this->imageCreateFunc == 'imagecreatetruecolor' && function_exists('imagefilledrectangle')) {
				imagefilledrectangle($thumb, 0, 0, $compute->canvasW, $compute->canvasH, $black);
			} elseif ($this->imageCreateFunc == 'imagecreate' && function_exists('ImageColorTransparent')) {
				ImageColorTransparent($thumb, $black);
			}
		}
		call_user_func($this->imageCopyFunc, $thumb, $this->image->getSource(), $compute->dstX, $compute->dstY, $compute->srcX, $compute->srcY, $compute->dstW, $compute->dstH, $compute->srcW, $compute->srcH);
		$this->makeImage($thumb, $this->dstfile, $this->quality);
		imagedestroy($thumb);
	
		return true;
	}
	
	/**
	 * 选用缩略图生成策略
	 */
	public function compute() {
		switch ($this->type) {
			case self::TYPE_CENTER:
				$compute = new ImageThumbCenterCompute($this->image, $this->width, $this->height, $this->forcemode);
				break;
			default:
				$compute = new ImageThumbIntactCompute($this->image, $this->width, $this->height, $this->forcemode);
		}

		if ($compute->compute() === true) {
			return $compute;
		}
		return false;
	}
	
	/**
	 * 生成图片
	 *
	 * @param resource $image 图片内容
	 * @param string $filename 图片地址
	 * @param int $quality 图片质量
	 * return void
	 */
	public function makeImage($image, $filename, $quality = '90') {
		if ($this->image->type == 'jpeg') {
			call_user_func($this->imageFunc, $image, $filename, $quality);
		} else {
			call_user_func($this->imageFunc, $image, $filename);
		}
	}
	
	/**
	 * 检测缩略图环境要求是否满足
	 *
	 * return bool
	 */
	public function checkEnv() {
		if (!$this->image->getSource()) {
			return false;
		}
		$this->imageFunc = 'image' . $this->image->type;
		if (!function_exists($this->imageFunc)) {
			return false;
		}
		if ($this->image->type != 'gif' && function_exists('imagecreatetruecolor') && function_exists('imagecopyresampled')) {
			$this->imageCreateFunc = 'imagecreatetruecolor';
			$this->imageCopyFunc = 'imagecopyresampled';
		} elseif (function_exists('imagecreate') && function_exists('imagecopyresized')) {
			$this->imageCreateFunc = 'imagecreate';
			$this->imageCopyFunc = 'imagecopyresized';
		} else {
			return false;
		}
		return true;
	}
	
	public function getThumbWidth() {
		return $this->thumbWidth;
	}
	
	public function getThumbHeight() {
		return $this->thumbHeight;
	}
}