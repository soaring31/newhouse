<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package Tripod
* create date 2015-7-14
*/
namespace CoreBundle\Services\Image;

abstract class ImageThumbCompute
{

	public $width;	//缩略限制宽
	public $height;	//缩略限制高

	public $srcX;	//源图像起始x坐标
	public $srcY;	//源图像起始y坐标
	public $srcW;	//源图像选中宽度
	public $srcH;	//源图像选中高度

	public $dstX;	//目标图像起始x坐标
	public $dstY;	//目标图像起始y坐标
	public $dstW;	//目标图像绘制宽度
	public $dstH;	//目标图像绘制高度

	public $canvasW;	//画布宽度
	public $canvasH;	//画布高度

	protected $image;
	protected $force = 0;

	public function __construct($image, $width, $height, $force = 0) {
		$this->image = $image;
		$this->width = $width;
		$this->height = $height;
		$this->force = $force;
	}

	public function isSmall() {
		return ($this->image->width <= $this->width && $this->image->height <= $this->height);
	}

	public function isWider() {
		return ($this->image->width/$this->width > $this->image->height/$this->height);
	}

	abstract public function compute();
}