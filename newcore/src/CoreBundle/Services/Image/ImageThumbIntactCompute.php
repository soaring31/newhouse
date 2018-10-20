<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package Tripod
* create date 2015-7-14
*/
namespace CoreBundle\Services\Image;

/**
 * 等比缩略算法
 */
class ImageThumbIntactCompute extends ImageThumbCompute
{
	public function compute()
	{
	
		$this->srcX = 0;
		$this->srcY = 0;
		$this->srcW = $this->image->width;
		$this->srcH = $this->image->height;
	
		$this->dstX = 0;
		$this->dstY = 0;
	
		if ($this->width > 0 && $this->height > 0) {
			if ($this->isSmall()) {
				if (!$this->force) return false;
				$this->dstW = $this->image->width;
				$this->dstH = $this->image->height;
			} elseif ($this->isWider()) {
				$this->dstW = $this->width;
				$this->dstH = $this->getThumbHeight();
			} else {
				$this->dstH = $this->height;
				$this->dstW = $this->getThumbWidth();
			}
		} elseif ($this->width > 0 && $this->image->width > $this->width) {
			$this->dstW = $this->width;
			$this->dstH = $this->getThumbHeight();
		} elseif ($this->height > 0 && $this->image->height > $this->height) {
			$this->dstH = $this->height;
			$this->dstW = $this->getThumbWidth();
		} else {
			if (!$this->force)
			    return false;
			$this->dstW = $this->image->width;
			$this->dstH = $this->image->height;
		}
		$this->canvasW = $this->dstW;
		$this->canvasH = $this->dstH;
	
		return true;
	}
	
	public function getThumbWidth()
	{
		return round($this->image->width/$this->image->height * $this->height);
	}
	
	public function getThumbHeight()
	{
		return round($this->image->height/$this->image->width * $this->width);
	}
}