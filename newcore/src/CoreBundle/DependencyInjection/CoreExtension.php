<?php

namespace CoreBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class CoreExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('db.yml');
        $loader->load('form.yml');
        $loader->load('auth.yml');
        $loader->load('twig.yml');
        $loader->load('events.yml');
        $loader->load('command.yml');
        $loader->load('services.yml');
        $loader->load('fileupload.yml');        
        
        $defenition = $container->getDefinition('fileuploader');
        
        $defenition->addMethodCall('setRootDir', array(
            $config['root_dir'],
        ));
        
        foreach ($config['endpoints'] as $endpoint_name => $endpoint_dir_name) {
            $defenition->addMethodCall('addEndpoint', array(
                array($endpoint_name, $endpoint_dir_name),
            ));
        }
    }
}
