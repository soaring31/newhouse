<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年5月31日
*/
namespace HouseBundle\Twig\Extension;

use CoreBundle\Twig\Extension\BaseExtension;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FunctionExtension extends BaseExtension
{
    protected $container;
    public function __construct(ContainerInterface $container)
	{
	    $this->container = $container;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getFunctions()
	{
	    return array(
	        new \Twig_SimpleFunction('showBytes', array($this, 'showBytes')),
	        //new \Twig_SimpleFunction('end', array($this, 'endFun')),
	        //new \Twig_SimpleFunction('current', array($this, 'currentFun')),
	    );
	}
	
	public function showBytes($num, $type='Byte')
	{
		$b = 0;
		//$bfix = ''; // Byte
		if(is_numeric($num)){ $b = $num; }
		if($type==='Byte'){
			if($b>pow(1024,4)){
				$b = number_format($b/(pow(1024,4)),2)." (TB) ";
			}else if($b>pow(1024,3)) {
				$b = number_format($b/(pow(1024,3)),2)." (GB) ";
			}else if($b>pow(1024,2)) {
				$b = number_format($b/(pow(1024,2)),2)." (MB) ";
			}else if($b>pow(1024,1)) {
				$b = number_format($b/(pow(1024,1)),2)." (KB) ";
			}else{
				$b = $b." (B) ";
			}
			return $b;
		}else{
			return number_format($b,$type);
		}
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getName()
	{
	    return 'functions';
	}
}