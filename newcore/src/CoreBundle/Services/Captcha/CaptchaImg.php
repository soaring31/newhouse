<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package Tripod
* create date 2015-04-28
*/
namespace CoreBundle\Services\Captcha;

use Symfony\Component\DependencyInjection\ContainerInterface;

class CaptchaImg
{
	/**
	 * 验证码字符数
	 */
	public $length = 4;
	/**
	 * 背景图片所在目录
	 *
	 * @var string  $folder
	 */
	public $folder     = 'captcha/';
	
	/**
	 * 文字大小
	 */
	public $fontsize = 14;
	
	/**
	 * 图片的文件类型
	 *
	 * @var string  $img_type
	 */
	public $img_type   = 'png';
	
	/*------------------------------------------------------ */
	//-- 存在session中的名称
	/*------------------------------------------------------ */
	public $session_word = 'captcha_word';
	
	/**
	 * 背景图片以及背景颜色
	 *
	 * 0 => 背景图片的文件名
	 * 1 => Red, 2 => Green, 3 => Blue
	 * @var array   $themes
	 */
	public $themes_jpg = array(
			1 => array('captcha_bg1.jpg', 202, 215, 145),
			2 => array('captcha_bg2.jpg', 202, 215, 145),
			3 => array('captcha_bg3.jpg', 202, 215, 145),
			4 => array('captcha_bg4.jpg', 202, 215, 145),
			5 => array('captcha_bg5.jpg', 202, 215, 145),
			6 => array('captcha_bg6.jpg', 202, 215, 145),
			7 => array('captcha_bg7.jpg', 202, 215, 145),
			8 => array('captcha_bg8.jpg', 202, 215, 145),
			9 => array('captcha_bg9.jpg', 202, 215, 145),
			10 => array('captcha_bg10.jpg', 202, 215, 145),
	);
	
	public $themes_gif = array(
			1 => array('captcha_bg1.gif', 255, 222, 173),
			2 => array('captcha_bg2.gif', 0, 0, 0),
			3 => array('captcha_bg3.gif', 0, 0, 0),
			4 => array('captcha_bg4.gif', 255, 255, 255),
			5 => array('captcha_bg5.gif', 255, 255, 255),
			6 => array('captcha_bg6.gif', 0, 0, 0),
	);
	
	/**
	 * 图片的宽度
	 *
	 * @var integer $width
	*/
	public $width      = 130;
	
	/**
	 * 图片的高度
	 *
	 * @var integer $height
	 */
	public $height     = 30;
	
	public $font = array(
			1=>'arial.ttf',
			2=>'arialbd.ttf',
			3=>'arialbi.ttf',
			4=>'ariali.ttf',
			5=>'ariblk.ttf'
	);
	
	/**
	 * 构造函数
	 *
	 * @access  public
	 * @param   string  $folder     背景图片所在目录
	 * @param   integer $width      图片宽度
	 * @param   integer $height     图片高度
	 * @return  bool
	 */
	
	public function __construct(ContainerInterface $container)
	{
		@ob_end_clean(); //清除之前出现的多余输入
		$this->container = $container;

		/* 检查是否支持 GD */
		if (PHP_VERSION >= '4.3')
		{		
			return (function_exists('imagecreatetruecolor') || function_exists('imagecreate'));
		}else{
			return (((imagetypes() & IMG_GIF) > 0) || ((imagetypes() & IMG_JPG)) > 0 );
		}
	}
	
	/**
	 * 检查给出的验证码是否和session中的一致
	 *
	 * @access  public
	 * @param   string  $word   验证码
	 * @return  bool
	 */
	public function check_word($word,$flag=true){
		$sessionVal = $this->container->get('request_stack')->getCurrentRequest()->getSession()->get($this->session_word);
		$recorded = isset($sessionVal) ? base64_decode($sessionVal) : '';
		$given    = $this->encrypts_word(strtoupper($word));
		
		if($flag)
		    $this->container->get('request_stack')->getCurrentRequest()->getSession()->remove($this->session_word);
		return preg_match("/$given/", $recorded);
	}
	
	public function setSessionWord($str)
	{
	    $this->session_word = $str?$str:$this->session_word;
	}
	
	/**
	 * 生成图片并输出到浏览器
	 *
	 * @access  public
	 * @param   string  $word   验证码
	 * @return  mix
	 */
	public function generate_image()
	{
		//生成随机数
		$word = $this->generate_word();
		
		/* 记录验证码到session */
		$this->record_word($word);
		
		/* 验证码长度 */
		$letters = mb_strlen($word,'UTF8');
		
		//角度
		//$angle = 0;
		
		//字体路径
		//$fontfile = $this->folder.$this->font[mt_rand(1,count($this->font))];
		
		//想、创建一幅图
		$img = imagecreatetruecolor($this->width,$this->height);
		
		//分配填充颜色,默认黄色背景
		$bgcolor = imagecolorallocate($img,255,255,255);
		
		//填充颜色
		imagefill($img,0,0,$bgcolor);
		
		/* 绘制边框 */
		//imagerectangle($img, 0, 0, $this->width - 1, $this->height - 1, imagecolorallocate($img,rand(0,255),rand(0,255),rand(0,255)));
		$zd = round(($this->width+$this->height)/2,0)+10;
		//生成300个噪点
		for($i=1;$i<=$zd;$i++){
			//画一个点，颜色
			$point_color = imagecolorallocate($img,rand(0,255),rand(0,255),rand(0,255));
			imagesetpixel($img,rand(0,$this->width),rand(0,$this->height),$point_color);
		}
		
		/* 获得验证码的高度和宽度 */
		$x1	= ($this->width - (imagefontwidth(5) * $letters)) / ($this->length);
		$y1	= ($this->height - imagefontheight(5));
		$x2	= $x1;
		//文字颜色		
		//$fontcolor = imagecolorallocate($img,rand(0,255),rand(0,255),rand(0,255));
		
		//加验证码
		for($aai=0;$aai<$letters;$aai++){
			imagettftext($img, rand(12,18), rand(0,35), $x1,rand($y1,25), imagecolorallocate($img, rand(100, 200), rand(100,205), rand(100,200)), $this->folder.$this->font[mt_rand(1,count($this->font))], $word[$aai]);
			$x1 += rand($x2,$x2+12);
			$y1 = $y1+rand(2,5);
		}
		
		//设置header为图片
		if ($this->img_type == 'jpeg' && function_exists('imagecreatefromjpeg'))
		{
			header('Content-type: image/jpeg');
			imageinterlace($img, 1);
			imagejpeg($img, false, 95);
		}else{
			header('Content-type: image/png');
			imagepng($img);
		}
		
		imagedestroy($img);		
		
		//不要删除
		die();
	}
	
	/**
	 * 对需要记录的串进行加密
	 *
	 * @access  private
	 * @param   string  $word   原始字符串
	 * @return  string
	 */
	private function encrypts_word($word){
		return substr(md5($word), 1, 10);
	}
	
	/**
	 * 将验证码保存到session
	 *
	 * @access  private
	 * @param   string  $word   原始字符串
	 * @return  void
	 */
	private function record_word($word){
		$this->container->get('request_stack')->getCurrentRequest()->getSession()->set($this->session_word, base64_encode($this->encrypts_word($word)));
		//保存session
		$this->container->get('request_stack')->getCurrentRequest()->getSession()->save();
	}
	
	/**
	 * 生成随机的验证码
	 *
	 * @access  private
	 * @param   integer $length     验证码长度
	 * @return  string
	 */
	private function generate_word(){
	    $arr = array();
		$chars = '0123456789ABCDEFGHJKLMNPQRSTUVWXYZ';
		//$chars = '0123456789';		
		
		for ($i = 0, $count = strlen($chars); $i < $count; $i++)
		{
			$arr[$i] = $chars[$i];
		}
		
		mt_srand((double) microtime() * 1000000);
		shuffle($arr);
		
		return substr(implode('', $arr), 5, $this->length);
	}
}