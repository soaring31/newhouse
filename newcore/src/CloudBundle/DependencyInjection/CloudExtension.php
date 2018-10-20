<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017-05-31
*/
namespace CloudBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class CloudExtension extends Extension
{    
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
    
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    
        /* Config output file */
        $container->setParameter('cloud.root_folder', $container->getParameter('kernel.root_dir').'/../');
        $container->setParameter('cloud.output_folder', $container->getParameter('cloud.root_folder').'backup/');
        $container->setParameter('cloud.restore_folder', $container->getParameter('cloud.root_folder').'restore/');
    
        /* Assign all config vars */
        foreach ($config as $k => $v) {
            $container->setParameter('cloud.'.$k, $v);
        }
    
        // When we launch functional tests, there is no DB specified, so skip it if empty
        if (!$container->hasParameter('cloud.databases'))
            $container->setParameter('cloud.databases', array());
    
        if (!$container->hasParameter('cloud.cloud_storages'))
            $container->setParameter('cloud.cloud_storages', array());
    
        $this->setDatabases($config, $container);
        $this->setProcessor($config, $container);
        $this->setSplitter($config, $container);
    }
    
    /**
     * @param array            $config
     * @param ContainerBuilder $container
     */
    private function setProcessor($config, ContainerBuilder $container)
    {
        $processorManager = $container->getDefinition('cloud.manager.processor');
        $processorManager->addMethodCall('setProcessor', array(
            new Reference(
                sprintf('cloud.processor.%s', $config['processor']['type'])
            ),
        ));
    }
    
    /**
     * @param array            $config
     * @param ContainerBuilder $container
     */
    private function setSplitter($config, ContainerBuilder $container)
    {
        if (!$config['processor']['options']['split']['enable'])
            return;
    
        $serviceId=sprintf('cloud.splitter.%s', $config['processor']['type']);
    
        //set the split size
        $splitter = $container->getDefinition($serviceId);
        $splitter->replaceArgument(0, $config['processor']['options']['split']['split_size']);
    
        $processorManager = $container->getDefinition('cloud.manager.processor');
        $processorManager->addMethodCall('setSplitter', array( new Reference($serviceId) ));
    }
    
    /**
     * @param array            $config
     * @param ContainerBuilder $container
     */
    private function setDatabases($config, ContainerBuilder $container)
    {
        $databases = $container->getParameter('cloud.databases');
    
        // Setting mysql values
        if (isset($config['databases']['mysql']))
        {
            $mysql = $databases['mysql'];
    
            if ($mysql['database'] === null)
                $mysql['database'] = $container->getParameter('database_name');
    
            /* if mysql config is not set, we taking from the parameters.yml values */
            if ($mysql['db_host'] === null && $mysql['db_user'] === null)
            {
                $mysql['db_host'] = $container->getParameter('database_host');
    
                if ($container->getParameter('database_port') !== null) {
                    $mysql['db_port'] = $container->getParameter('database_port');
                }
    
                $mysql['db_user']     = $container->getParameter('database_user');
                $mysql['db_password'] = $container->getParameter('database_password');
            }
    
            $databases['mysql'] = $mysql;
        }
    
        // Setting postgresql values
        if (isset($config['databases']['postgresql']))
        {
            $postgresql = $databases['postgresql'];
    
            if ($postgresql['database'] === null)
                $postgresql['database'] = $container->getParameter('database_name');
    
            /* if postgresql config is not set, we taking from the parameters.yml values */
            if ($postgresql['db_host'] === null && $postgresql['db_user'] === null) {
                $postgresql['db_host'] = $container->getParameter('database_host');
    
                if ($container->getParameter('database_port') !== null)
                    $postgresql['db_port'] = $container->getParameter('database_port');

                $postgresql['db_user']     = $container->getParameter('database_user');
                $postgresql['db_password'] = $container->getParameter('database_password');
            }
    
            $databases['postgresql'] = $postgresql;
        }
    
        $container->setParameter('cloud.databases', $databases);
    }
}
