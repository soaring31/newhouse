<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年6月15日
*/
namespace CoreBundle\Services\SiteMap;

use CoreBundle\Services\ServiceBase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SiteMap extends ServiceBase implements SiteMapInterface
{
    private $info;
    protected $container;
    
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    /*public function init($name)
    {
        $map = array();
        $map['name'] = $name;
        $map['status'] = 1;
        $this->get('core.table_manage')->setTables('sitemap');
        $this->info = $this->get('core.table_manage')->findOneBy($map);
        
        if(empty($this->info))
            throw new \InvalidArgumentException(sprintf("%s不存在或已被删除。", $name));
    }*/

    public function exec($sitemapObj, array $list)
    {
        $this->info = $sitemapObj;
        $view = $this->info->getTpl();
        $sconfig = $this->container->getParameter('sitemap');
        $target = $this->get('core.common')->getWebRoot();
        $target .= $this->info->getXmlUrl();

        //$limit = $sconfig['limit'];
        if (isset($sconfig['gzip'])&&$sconfig['gzip']&&function_exists('gzencode'))
        {
            $response = new Response();
            $sitemap = $this->get('templating')->render($view, array('list' => $list));
            $sitemapGz = gzencode($sitemap);
            $response->headers->set('Content-Type', 'application/x-gzip');
            $response->headers->set('Content-Disposition', 'attachment; filename="sitemap.xml.gz"');
            $response->headers->set('Content-Length', strlen($sitemapGz));
            $response->setContent($sitemapGz);
            $response->send();
        }        
        /*$list = array();
        $list['url'] = $this->get('router')->generate('manage_main',array(),true);
        $list['info'] = array();*/
        
        return $this->get('core.controller.command')->createFile($this->info->getTpl(), $target, $list);
        //return file_put_contents($target, $this->get('templating')->render($view, $parameters));
    }
}