<?php
/**
* 用于门户的缩略/截取算法
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package Tripod
* create date 2015-7-13
*/
namespace CoreBundle\Services\Image;

/**
 * 图片切割
 */
class ImageCut
{
	protected $image;
	protected $dstfile;
	protected $width;
	protected $height;
	protected $type;
	protected $quality = 90;
	protected $forcemode = 0;
	protected $rotate = 0;
	
	/**
	 * 设置缩略图目标地址
	 */
	public function setDstFile($dstfile)
	{
		$this->dstfile = $dstfile;
	}
	
	/**
	 * 设置缩略图宽度
	 */
	public function setDstWidth($dstWidth)
	{
		$this->dstWidth = intval($dstWidth);
	}
	
	/**
	 * 设置缩略图高度
	 */
	public function setDstHeight($dstHeight)
	{
		$this->dstHeight = intval($dstHeight);
	}
	
	/**
	 * 设置缩略图X坐标
	 */
	public function setDstX($width)
	{
		$this->x1 = intval($width);
	}
	
	/**
	 * 设置缩略图Y坐标
	 */
	public function setDstY($height)
	{
		$this->y1 = intval($height);
	}
	
	/**
	 * 设置缩略图旋转
	 */
	public function setDstRotate($rotate)
	{
		$this->rotate = intval($rotate);
	}
	
	public function __construct(Image $image)
	{
		$this->image = $image;
	}
	
	public function execute()
	{
	    if (!$this->dstfile)
	        return -1;
	    
	    //根据图片格式创建新画布
	    switch($this->image->_type)
	    {
	        case "image/gif":
	            $srcImg = imagecreatefromgif($this->image->filename);
	            break;
	        case "image/pjpeg":
	        case "image/jpeg":
	        case "image/jpg":
	            $srcImg = imagecreatefromjpeg($this->image->filename);
	            break;
	        case "image/png":
	        case "image/x-png":
	            $srcImg = imagecreatefrompng($this->image->filename);
	            break;
	    }
	    
	    //源图的宽跟高度
	    $src_img_w = $this->image->width;
	    $src_img_h = $this->image->height;
	    
	    //图片旋转参数
        if(is_numeric($this->rotate)&&$this->rotate!=0)
        {           
            $newImg = imagerotate( $srcImg, $this->rotate, imagecolorallocatealpha($srcImg, 0, 0, 0, 127) );
             
            imagedestroy($srcImg);
             
            $srcImg = $newImg;
             
            $deg = abs($this->rotate) % 180;
            $arc = ($this->rotate > 90 ? (180 - $deg) : $deg) * M_PI / 180;
             
            $src_img_w = $this->image->width * cos($arc) + $this->image->height * sin($arc);
            $src_img_h = $this->image->width * sin($arc) + $this->image->height * cos($arc);
             
            // Fix rotated image miss 1px issue when degrees < 0
            $src_img_w -= 1;
            $src_img_h -= 1;
        }
        
        //源图高、宽度
        $tmp_img_w = $this->image->width;
        $tmp_img_h = $this->image->height;
        
        //目标图高、宽度
        $dst_img_w = $this->dstWidth;
        $dst_img_h = $this->dstHeight;

        $src_x = $this->x1;
        $src_y = $this->y1;
        
        if ($src_x <= -$tmp_img_w || $src_x > $src_img_w) {
            $src_x = $src_w = $dst_x = $dst_w = 0;
        } else if ($src_x <= 0) {
            $dst_x = -$src_x;
            $src_x = 0;
            $src_w = $dst_w = min($src_img_w, $tmp_img_w + $src_x);
        } else if ($src_x <= $src_img_w) {
            $dst_x = 0;
            $src_w = $dst_w = min($tmp_img_w, $src_img_w - $src_x);
        }
        
        if ($src_w <= 0 || $src_y <= -$tmp_img_h || $src_y > $src_img_h) {
            $src_y = $src_h = $dst_y = $dst_h = 0;
        } else if ($src_y <= 0) {
            $dst_y = -$src_y;
            $src_y = 0;
            $src_h = $dst_h = min($src_img_h, $tmp_img_h + $src_y);
        } else if ($src_y <= $src_img_h) {
            $dst_y = 0;
            $src_h = $dst_h = min($tmp_img_h, $src_img_h - $src_y);
        }

        $ratio = 1;
        $dst_x /= $ratio;
        $dst_y /= $ratio;
        $dst_w /= $ratio;
        $dst_h /= $ratio;
        
        $dst_img = imagecreatetruecolor($dst_img_w, $dst_img_h);
        
        //给目标图添加一个透明背景
        imagefill($dst_img, 0, 0, imagecolorallocatealpha($dst_img, 0, 0, 0, 127));
        imagesavealpha($dst_img, true);

        
        $result = imagecopyresampled($dst_img, $srcImg, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
        
        if(empty($result)) return -10;
        
        if ($result&&!imagepng($dst_img, $this->dstfile)) return -10;
        
        imagedestroy($srcImg);
        imagedestroy($dst_img);
        
        return $this->dstfile;
	}
}