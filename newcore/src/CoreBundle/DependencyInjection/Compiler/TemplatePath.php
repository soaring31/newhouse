<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package Tripod
* create date 2015年8月26日
*/
namespace CoreBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class TemplatePath implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $twigFilesystemLoaderDefinition = $container->getDefinition('twig.loader.filesystem');        
        
        $dir = $container->getParameter('kernel.root_dir').DIRECTORY_SEPARATOR;
        $dir .= "..".DIRECTORY_SEPARATOR."Template";
        if (is_dir($dir))
        {
            $twigFilesystemLoaderDefinition->addMethodCall('addPath', array($dir));
        }
    }
}