<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package Tripod
* create date 2015-7-14
*/
namespace CoreBundle\Services\Image;

/**
 * 居中截取算法
 */
class ImageThumbCenterCompute extends ImageThumbCompute
{

	public function compute()
	{
		if ($this->width > 0 && $this->height > 0) {

		} elseif ($this->width > 0) {
			$this->height = $this->width;
		} elseif ($this->height > 0) {
			$this->width = $this->height;
		} else {
			return false;
		}
		if ($this->isSmall()) {
			if (!$this->force) return false;
			$this->srcX = 0;
			$this->srcY = 0;
			$this->srcW = $this->image->width;
			$this->srcH = $this->image->height;
		} elseif ($this->isWider()) {
			$this->srcW = round($this->width/$this->height * $this->image->height);
			$this->srcH = $this->image->height;
			$this->srcX = round(($this->image->width - $this->srcW) / 2);
			$this->srcY = 0;
		} else {
			$this->srcW = $this->image->width;
			$this->srcH = round($this->height/$this->width * $this->image->width);
			$this->srcX = 0;
			$this->srcY = round(($this->image->height - $this->srcH) / 2);
		}
		$this->dstW = min($this->srcW, $this->width);
		$this->dstH = min($this->srcH, $this->height);
		$this->dstX = round(($this->width - $this->dstW) / 2);
		$this->dstY = round(($this->height- $this->dstH) / 2);

		$this->canvasW = $this->width;
		$this->canvasH = $this->height;

		return true;
	}
}