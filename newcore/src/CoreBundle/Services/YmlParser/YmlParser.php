<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package Tripod
* create date 2015-8-5
*/
namespace CoreBundle\Services\YmlParser;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Filesystem\Filesystem;

/**
 * yml配置文件操作类
 */
class YmlParser
{
	protected $ymlPath;
	
	public function __construct($rootDir) {
		$this->rootDir = $rootDir;
		$this->fs = new Filesystem;
	}
	
	/**
	 * 读取yml文件
	 */
	public function ymlRead($ymlPath)
	{
		if ($this->fs->exists($ymlPath))
			return Yaml::parse(file_get_contents($ymlPath));
		else
			throw new \InvalidArgumentException("{$ymlPath} Do not exsist a");

		return Yaml::parse($ymlPath);
	}
	
	/**
	 * 写入yml文件
	 */
	public function ymlWrite(array $ymlDefinitions, $ymlPath=null)
	{
		if (!$this->fs->exists($ymlPath))
		    $this->fs->touch($ymlPath);

		
		//写入yml文件
		$this->fs->dumpFile($ymlPath, Yaml::dump($ymlDefinitions, self::arrayLevel($ymlDefinitions)));
	}
	
	/**
	 * 返回数组的维度
	 * @param  array $arr
	 */
	private function arrayLevel(array $arr){
	    $al = array(0);
	    self::_aL($arr,$al);
	    return (int)max($al);
	}

    private function _aL($arr,&$al,$level=0){
        if(is_array($arr)){
            $level++;
            $al[] = $level;
            foreach($arr as $v){
                self::_aL($v,$al,$level);
            }
        }
    }

}

