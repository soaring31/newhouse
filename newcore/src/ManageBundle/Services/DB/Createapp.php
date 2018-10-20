<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年06月13日
*/
namespace ManageBundle\Services\DB;

use Symfony\Component\Filesystem\Filesystem;
use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

/**
* 创建APP
* 
*/
class Createapp extends AbstractServiceManager
{
    protected $appPath;
    protected $table = 'Createapp';
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
        $this->filesystem = new Filesystem();
        $this->appPath = $this->get('core.common')->getSiteRoot().DIRECTORY_SEPARATOR."mobileapp".DIRECTORY_SEPARATOR;
    }
    
    public function generate($info=null)
    {
        $info = $info?$info:parent::findBy(array());
        
        if(!isset($info['data'])||empty($info['data']))
            return false;
        
        $tempArr = array();
        
        foreach($info['data'] as $key=>$item)
        {
            //去掉bundle后缀
            if (preg_match('/Bundle$/', $item->getBundle()))
                $bundle = substr($item->getBundle(), 0, -6)."mobileBundle";
            
            $template = $bundle.':'.ucfirst($item->getController()).':'.strtolower($item->getAction()).'.html.twig';
            
            if ($this->get('templating')->exists($template))
            {
                $tempArr[$key]['bundle'] = $item->getBundle();
                $tempArr[$key]['controller'] = ucfirst($item->getController());
                $tempArr[$key]['action'] = strtolower($item->getAction());
                $tempArr[$key]['template'] = $template;
                $tempArr[$key]['tplbundle'] = $bundle;
            }
        }
  
        //强制app访问模式
        $this->get('request')->request->set('_isApp', 1);
        
        // 将house\site\HousemobileBundle\Resources\public 复制到 house\mobileapp\housemobile
        $src_dir = str_replace('HouseBundle', 'HousemobileBundle', $this->get('core.common')->getBundlePath('HouseBundle')."Resources".DIRECTORY_SEPARATOR."public");
        $dst_dir = $this->appPath.'housemobile';

        $filesystem = new Filesystem();
        //判断文件夹是否存在，不存在则创建
        if(!$filesystem->exists($src_dir))
            $filesystem->mkdir($src_dir);

        //判断文件夹是否存在，不存在则创建
        if(!$filesystem->exists($dst_dir))
            $filesystem->mkdir($dst_dir);
        
        $this->get('core.common')->copyDir($src_dir, $dst_dir);

        //判断文件夹是否存在，不存在则创建
        if(!$this->filesystem->exists($this->appPath)){
            try {
                //创建文件夹
                $this->filesystem->mkdir($this->appPath);
            } catch (IOExceptionInterface $e) {
                throw new \LogicException("An error occurred while creating your directory at ".$e->getPath());
            }
        }

        foreach($tempArr as $item)
        {
            if(!$this->filesystem->exists($this->appPath.strtolower($item['controller']))){
                try {
                    //创建文件夹
                    $this->filesystem->mkdir($this->appPath.strtolower($item['controller']));
                } catch (IOExceptionInterface $e) {
                    throw new \LogicException("An error occurred while creating your directory at ".$e->getPath());
                }
            }
            
            $result = $this->get('templating')->renderResponse($item['template'], $this->get('core.common')->getViewInit($item['template'], $item['bundle'], $item['controller'], $item['action'], $item['tplbundle']), null);
            file_put_contents($this->appPath.strtolower($item['controller']).DIRECTORY_SEPARATOR.$item['action'].".html", $result->getContent());
        }
        
        return true;
    }
}