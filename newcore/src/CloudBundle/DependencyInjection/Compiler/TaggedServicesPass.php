<?php
/**
 * @copyright Copyright (c) 2008 – 2017 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2017-05-31
 */
namespace CloudBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * Class TaggedServicesPass.
 */
class TaggedServicesPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $this->databaseCompilerPass($container);
        $this->clientCompilerPass($container);
        $this->processorCompilerPass($container);
    }

    /**
     * @param ContainerBuilder $container
     */
    public function databaseCompilerPass(ContainerBuilder $container)
    {
        $databases = $container->findTaggedServiceIds('cloud.database');
        $dbEnabled = $container->getParameter('cloud.databases');

        $databaseManager = $container->getDefinition('cloud.manager.database');

        foreach (array_keys($databases) as $serviceId)
        {
            // Get the name of the database
            $name = explode('.', $serviceId);
            $name = end($name);

            if (!isset($dbEnabled[$name]))
                continue;

            // if the database is activated in the configuration file, we add it to the DatabaseChain
            $databaseManager->addMethodCall('add', array(new Reference($serviceId)));
        }
    }

    /**
     * @param ContainerBuilder $container
     */
    public function clientCompilerPass(ContainerBuilder $container)
    {
        $clients = $container->findTaggedServiceIds('cloud.client');
        $clientsEnabled = $container->getParameter('cloud.cloud_storages');

        $clientManager = $container->getDefinition('cloud.manager.client');

        foreach (array_keys($clients) as $serviceId)
        {
            // Get the name of the database
            $name = explode('.', $serviceId);
            $name = end($name);

            if (!isset($clientsEnabled[$name])) {
                continue;
            }

            // if the client is activated in the configuration file, we add it to the ClientChain
            $clientManager->addMethodCall('add', array(new Reference($serviceId)));
        }

        // If gaufrette is set, assign automatically the specified filesystem as the cloud client
        $cloudStorages = $container->getParameter('cloud.cloud_storages');
        if (isset($cloudStorages['gaufrette']))
        {
            $filesystem = $cloudStorages['gaufrette']['service_name'];
            foreach($filesystem as $filesystemName)
            {
                $gaufrette = $container->getDefinition('cloud.client.gaufrette');
                $gaufrette->addMethodCall('addFilesystem', array(
                    new Reference($filesystemName)
                ));
            }
        }

        // If flysystem is set, assign automatically the specified filesystem adapters
        if (isset($cloudStorages['flysystem'])) {
            $filesystem = $cloudStorages['flysystem']['service_name'];

            foreach ($filesystem as $filesystemName)
            {
                $flysystem = $container->getDefinition('cloud.client.flysystem');
                $flysystem->addMethodCall('addFilesystem', array(
                    new Reference($filesystemName),
                ));
            }
        }
    }

    /**
     * @param ContainerBuilder $container
     */
    public function processorCompilerPass(ContainerBuilder $container)
    {
        $processors = $container->findTaggedServiceIds('cloud.processor');
        $options = $container->getParameter('cloud.processor');

        foreach (array_keys($processors) as $serviceId) {
            $container->getDefinition($serviceId)->addMethodCall('addOptions', array($options['options']));
        }
    }
}
