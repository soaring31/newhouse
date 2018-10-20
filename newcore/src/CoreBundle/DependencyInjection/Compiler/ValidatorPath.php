<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package Tripod
* create date 2015年8月26日
*/
namespace CoreBundle\DependencyInjection\Compiler;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Config\Resource\DirectoryResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class ValidatorPath implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $validatorBuilder = $container->getDefinition('validator.builder');
        $validatorFiles = array();
        
        $bundles = $container->getParameter('kernel.bundles');
        
        $defaultBundle = ucfirst($container->getParameter('_defaultbundle'));

        if($defaultBundle&&isset($bundles[$defaultBundle]))
        {
            $bundle = $bundles[$defaultBundle];
            
            $reflection = new \ReflectionClass($bundle);
            
            $dir = dirname($reflection->getFileName());
            $dir .= DIRECTORY_SEPARATOR.'Resources'.DIRECTORY_SEPARATOR.'validation';

            if (is_dir($dir))
            {
                $files = Finder::create()->files()->in($dir)->name('*.yml');
                foreach ( $files as $file ) {
                    $validatorFiles[] = $file->getRealpath();
                }
            
                $container->addResource(new DirectoryResource($dir));
            }
        }
        
        if(count($validatorFiles)>0)
            $validatorBuilder->addMethodCall('addYamlMappings', array($validatorFiles));
    }
}