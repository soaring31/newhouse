<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年3月14日
*/
namespace CoreBundle\Twig\Extension;

use CoreBundle\Services\Image\Image;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FunctionExtension extends BaseExtension
{
    protected $container;
    public function __construct(ContainerInterface $container)
	{
	    $this->container = $container;
	    $this->filesystem = new Filesystem();
	}

	/**
	 * {@inheritdoc}
	 */
	public function getFunctions()
	{
	    return array(
	        new \Twig_SimpleFunction('assetImg', array($this, 'assetImg')),
	        new \Twig_SimpleFunction('end', array($this, 'endFun')),
	        new \Twig_SimpleFunction('current', array($this, 'currentFun')),
	        new \Twig_SimpleFunction('img', array($this, 'img'))
	    );
	}

	public function assetImg($path, $param=array())
	{
	    //原图片路径
	    $oldPath = $path;
	    //是否有后缀
	    $hasSuffix = true;
        $path = ltrim($path, '//');
        
        if($this->get('core.common')->C('upload_type','')=='remote')
        {
            $upRemoteDomainname = $this->get('core.common')->C('up_remote_domainname','');
            
            if($upRemoteDomainname)
            {
                $path = str_replace("\\", "/", $path);
                
                return $upRemoteDomainname."/".$path;
            }
        }
        
	    $params = array();
	    if(is_string($param))
	        parse_str($param, $params);

	    $info = parse_url($path);

	    if(!isset($info['path'])||empty($info['path']))
	        return $path;

	    $paths1 = explode("/", $info['path']);
	    array_pop($paths1);
	    //判断是否有带http头,有则存入webpic目录
	    if (false !== strpos($path, '://') || 0 === strpos($path, '//'))
	        $info['path'] = "webpic".DIRECTORY_SEPARATOR.$info['path'];

	    $webRoot = $this->get('core.common')->getWebRoot();

	    $paths = explode("/", $info['path']);
	    $filename = array_pop($paths);

	    //无后缀的图片，比如 http://pic4.58cdn.com.cn/anjuke_58/68a8eee6336c88c2e4345e1177b0f5b9
	    if (strstr($filename, '.')===false)
	    {
	        $hasSuffix = false;
	        $suffix = self::_getRemoteSuffix($oldPath);//获取图片信息
	    }
	    
	    $fileArr = explode('.', $filename);//去掉后缀

	    $suffix = !$hasSuffix?$suffix:array_pop($fileArr);

	    $filename = implode('.', $fileArr);

	    $fileArr = explode('_', $filename);

	    if(count($fileArr)>=2)
	    {
	        //判断是否有带http头
	        if (false !== strpos($path, '://') || 0 === strpos($path, '//'))
	            $paths[] = reset($fileArr);

	        if(count($fileArr)>=3)
	        {
    	        $height = array_pop($fileArr);
    	        $width = array_pop($fileArr);
    	        $param['height'] = isset($param['height'])?$param['height']:(int)$height;
    	        $param['width'] = isset($param['width'])?$param['width']:(int)$width;
	        }
	    }

	    $filename = implode('_', $fileArr);

	    $filename .= ".".$suffix;

	    $paths[] = $filename;
	    $paths1[] = $filename;

	    $info['path'] = implode("/", $paths1);

	    $picPath = $webRoot.implode("/", $paths);
	    
	    $picPath = str_replace('\\','/',$picPath);

	    $paths1 = self::ensureUrlIsAbsolute($info['path'], $info);

	    $info['path'] = implode("/", $paths);

        //如何不存在则创建目录
        if(!$this->filesystem->exists($picPath))
        {
            if($this->get('request')->get('datatype') == 'jsonp')
                return $info['path'];
            
            try {
                //创建目录
                $this->filesystem->mkdir(dirname($picPath));

                //下载源图
                $this->get('core.common')->downImage($hasSuffix?$paths1:$oldPath, $picPath);
            } catch (\Exception $e) {
                return $info['path'];
            }
        }

        $path = $info['path'];
        $param['width'] = isset($param['width'])?$param['width']:0;
        $param['height'] = isset($param['height'])?$param['height']:0;
        $param['type'] = isset($param['type'])?$param['type']:0;
        //只读源图
        if($param['type']==0)
            $param['width'] = $param['height'] = 0;
        
        //生成缩略图后的目录
        $thumbPath = '';

	    //按尺寸生成缩略图
	    if($param['width']>0||$param['height']>0)
	    {
	        array_pop($paths);

	        $fileArr[] = $param['width'];
	        $fileArr[] = $param['height'];
	        $filename = implode('_', $fileArr);

	        $filename .= ".".$suffix;

	        $paths[] = $filename;

	        $path = implode("/", $paths);
	        $path = str_replace('\\','/',$path);
	        $thumbPath = $webRoot.$path;

	        //如何不存在则创建目录
	        if(!$this->filesystem->exists($webRoot.$path))
	        {
    	        //加载图片处理服务
    	        $img = new Image($picPath);

    	        //生成缩略图片
    	        $img->makeThumb($webRoot.$path, $param['width'], $param['height'],0,isset($param['type'])?$param['type']:1);
	        }
	    }
	    
	    //添加水印
	    if (isset($param['watermark']) && $param['watermark']!='')
	    {
	        //加载水印配置
	        $config = $this->get('core.ymlParser')->ymlRead($this->get('core.common')->getSiteRoot()."Config".DIRECTORY_SEPARATOR."config_watermark.yml");
	        
	        if(isset($config[$param['watermark']]))
	        {
	            $config = $config[$param['watermark']];
	            //水印配置开启
	            if ($config['status']==1)
	            {
        	        $noWaterPath = $path;
        	        array_pop($paths);
        	        
        	        //后缀
        	        $fileArr[] = $param['watermark'];
        	        $filename = implode('_', $fileArr);
        	        
        	        $filename .= '.'.$suffix;
        	        
        	        $paths[] = $filename;
        
        	        $path = implode("/", $paths);
        	        $path = str_replace('\\','/',$path);
        
        	        //如果不存在则创建
        	        if(!$this->filesystem->exists($webRoot.$path))
        	        {
        	            //加载图片处理服务
        	            $img = new Image($thumbPath!=''?$thumbPath:$picPath);
        	            
        	            if ($img->height>$config['minheight'] && $img->width>$config['minwidth'])
        	            {
            	            if(isset($config['type'])&&$config['type']==1)
            	                $imgurl = isset($config['img'])?($webRoot.$config['img']):null;
            	            else
            	                $imgurl = isset($config['img'])?$config['img']:null;
        
            	            $img->makeThumbByWater($webRoot.$path
                                , isset($config['type'])?$config['type']:1
                                , isset($config['position'])?$config['position']:0
                                , isset($config['trans'])?$config['trans']:0
                                , isset($config['textcontent'])?$config['textcontent']:null
                                , $imgurl
                                , isset($config['fontcolor'])?$config['fontcolor']:'#666666'
                                , isset($config['quality'])?$config['quality']:0
                                , isset($config['fontsize'])?$config['fontsize']:12);
        	            } else {
        	                $path = $noWaterPath;
        	            }
        	        }
	            }
	        } else {
                throw new \InvalidArgumentException(sprintf('[%s]水印方法不存在！', $param['watermark']));
            }
	    }

	    $absolute = isset($param['absolute'])?(bool)$param['absolute']:false;
	    
	    

	    $url = $this->get('templating.helper.assets')->getUrl($path, null, null);

	    if (!$absolute)
	        return $url;

	    return $this->ensureUrlIsAbsolute($url);
	}

	/**
	 * 对图片的裁切和打水印
	 * @param string $content
	 * @param array $param
	 */
	public function img($content, $param=array())
	{
	    $imgpreg = "/<img(.*)src=\"([^\"]+)\"[^>]+>/isU";
	    
	    $img = array();
	    // 匹配图片路径，$img[2]为图片路径
	    preg_match_all($imgpreg,$content,$img);
	    
	    foreach ($img[2] as $item)
	    {
	        $newurl = $this->assetImg($item, $param);
	        $content = preg_replace_callback('#'.$item.'#', function($ms)use($newurl){
	            return $newurl;
	        } ,$content,1);
	    }
	    
	    return $content;
	}

	public function endFun($str)
	{
	    if(!is_array($str))
	        return array();

	    return end($str);
	}

	public function currentFun($str)
	{
	    if(!is_array($str))
	        return array();

	    return current($str);
	}

	private function ensureUrlIsAbsolute($url, array $info=array())
	{
	    return $this->get('core.common')->ensureUrlIsAbsolute($url, $info);
	}
	
	/**
	 * 获取远程图片后缀，有一些远程图片是没有后缀名的，要获取它的详细信息才可以得出它的后缀名
	 * 比如：http://pic4.58cdn.com.cn/anjuke_58/68a8eee6336c88c2e4345e1177b0f5b9
	 * @param unknown $path
	 */
	private function _getRemoteSuffix($path)
	{
	    $result = @getimagesize($path);
	    
	    if ($result !== false)
	    {
	        if (isset($result['mime']))
	        {
	            $types = explode('/', $result['mime']);
	            if (count($types)==2 && $types[0]=='image')
	                return $types[1];
	        }
	    }
	    return '';
	}

	/**
	 * {@inheritdoc}
	 */
	public function getName()
	{
	    return 'functions';
	}
}