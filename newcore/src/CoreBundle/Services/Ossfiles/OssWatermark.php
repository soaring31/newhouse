<?php
/**
* 缩略水印
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2015-7-13
*/
namespace CoreBundle\Services\Ossfiles;

/**
 * 图片水印管理
 */
class OssWatermark
{
	protected $image;
	protected $water;
	
	protected $dstfile;			//目标对象
	protected $position;		//水印位置 1-9 分别为九宫格的对应位置
	protected $transparency;	//水印透明度
	protected $quality;			//图片质量
	protected $type;			//水印类型 <1.图片水印 2.文字水印>
	
	protected $file;
	
	protected $text;			//水印文字
	protected $fontfamily;		//水印字体
	protected $fontsize;		//字体大小
	protected $fontcolor;		//字体颜色
	protected $fontfile;
	
	public function __construct($image)
	{
		$this->image = $image;
	}
	
	/**
	 * 目标文件
	 * @param string $file
	 */
	public function setDstfile($file)
	{
		$this->dstfile = $file;
		return $this;
	}
	
	/**
	 * 设置位置
	 */
	public function setPosition($position)
	{
		$this->position = intval($position);
		return $this;
	}
	
	/**
	 * 设置水印透明度
	 */
	public function setTransparency($transparency)
	{
		$this->transparency = intval($transparency)*100;
		return $this;
	}
	
	/**
	 * 设置图片质量
	 */
	public function setQuality($quality)
	{
		$this->quality = intval($quality);
		return $this;
	}
	
	/**
	 * 设置缩略方式 <1.图片水印 2.文字水印>
	 */
	public function setType($type)
	{
		$this->type = $type;
		return $this;
	}

	/**
	 * 设置水印图片
	 */
	public function setFile($file)
	{
		$this->file = $file;
		return $this;
	}

	/**
	 * 设置水印文字
	 */
	public function setText($text)
	{
	    //$text = iconv("GB2312", "UTF-8", $text); //将中文字转换为UTF8
		if($text){
			$this->text = 'text_'.$this->urlsafe_b64encode($text);
		}else{
			$this->text = null;
		}
		return $this;
	}
	
	/**
	 * 设置水印字体,字体文件在web根目录下的captcha文件夹
	 */
	public function setFontfamily($fontfamily)
	{
		$this->fontfamily = $fontfamily;
		return $this;
	}
	
	/**
	 * 设置水印字体大小
	 */
	public function setFontsize($fontsize)
	{
		$this->fontsize = $fontsize;
		return $this;
	}

	/**
	 * 设置水印字体颜色
	 */
	public function setFontcolor($fontcolor)
	{
		$this->fontcolor =ltrim($fontcolor,"#");
		return $this;
	}

	/**
	 * 设置九宫格
	 */
	public function getPosition()
	{
		if ($this->position >= 1 && $this->position <= 9) {

			switch ($this->position ) {
				case 1:
					$offset = 'g_nw,x_10,y_10';break;
				case 2:
					$offset = 'g_north,y_10';break;
				case 3:
					$offset = 'g_ne,x_10,y_10';break;
				case 4:
					$offset = 'g_west,x_10,voffset_0';break;
				case 5:
					$offset = 'g_center,voffset_0';break;
				case 6:
					$offset = 'g_east,x_10,voffset_0';break;
				case 7:
					$offset = 'g_sw,x_10,y_10';break;
				case 8:
					$offset = 'g_south,y_10';break;
				default:
					$offset = 'g_se,x_10,y_10';
			}
		}

		return $offset;
	}

	/**
	 * Base64位编码
	 */
	function urlsafe_b64encode($string) {
		$data = base64_encode($string);
		$data = str_replace(array('+','/','='),array('-','_',''),$data);
		return $data;
	}

	public function initWaterWay()
	{
		if ($this->type == 2) {
			if($this->text)
				$style = 'image/auto-orient,1/quality,q_'.$this->quality.'/format,jpg/watermark,size_'.$this->fontsize.','.$this->text.',t_'.$this->transparency.','.$this->getPosition().',color_'.$this->fontcolor;
				return $style;
		} elseif($this->type == 1) {
			$watermark = explode('/',strrev($this->file)) ;
			$watermark = $this->urlsafe_b64encode( strrev($watermark[0]) .'?x-oss-process=image/resize,P_20' );
			$style = 'image/auto-orient,1/quality,q_'.$this->quality.'/format,jpg/watermark,image_'.$watermark.',t_'.$this->transparency.','.$this->getPosition();

			return $style;
		}
	}

}