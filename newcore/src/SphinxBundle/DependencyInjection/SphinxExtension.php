<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016-03-28
*/
namespace SphinxBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;


/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class SphinxExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $configuration = new Configuration();
    
        $config = $processor->processConfiguration($configuration, $configs);
    
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        
        $loader->load('db.yml');
        $loader->load('twig.yml');
        $loader->load('parameters.yml');
        $loader->load('services.yml');
    
        if (isset($config['searchd'])) {
            $container->setParameter('sphinx.searchd.host', $config['searchd']['host']);
            $container->setParameter('sphinx.searchd.port', $config['searchd']['port']);
            $container->setParameter('sphinx.searchd.socket', $config['searchd']['socket']);
        }
    
        if (isset($config['sphinx_api'])) {
            $container->setParameter('sphinx.sphinx_api.file', $config['sphinx_api']['file']);
        }
    
        if (isset($config['indexes'])) {
            $container->setParameter('sphinx.indexes', $config['indexes']);
        }
    
        if (isset($config['doctrine'])) {
            $container->setParameter('sphinx.doctrine.entity_manager', $config['doctrine']['entity_manager']);
        }
    }
    
    public function getAlias()
    {
        return 'sphinx';
    }
}
