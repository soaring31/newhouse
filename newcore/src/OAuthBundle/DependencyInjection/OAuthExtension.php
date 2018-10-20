<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016-06-20
*/
namespace OAuthBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

class OAuthExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('twig.yml');
        $loader->load('weixin.yml');
        $loader->load('wechat.yml');
        $loader->load('alipay.yml');
        $loader->load('services.yml');
        $loader->load('http_client.yml');

        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration(), $configs);

        // setup http client settings
        $httpClient = $container->getDefinition('oauth.http_client');

        $httpClient->addMethodCall('setVerifyPeer', array($config['http_client']['verify_peer']));
        $httpClient->addMethodCall('setTimeout', array($config['http_client']['timeout']));
        $httpClient->addMethodCall('setMaxRedirects', array($config['http_client']['max_redirects']));
        $httpClient->addMethodCall('setIgnoreErrors', array($config['http_client']['ignore_errors']));
        if (isset($config['http_client']['proxy']) && $config['http_client']['proxy'] != '')
            $httpClient->addMethodCall('setProxy', array($config['http_client']['proxy']));

        // set current firewall
        if (empty($config['firewall_names']) && !isset($config['firewall_name']))
            throw new InvalidConfigurationException('The child node "firewall_name" or "firewall_names" at path "oauth" must be configured.');
        elseif (!empty($config['firewall_names']) && isset($config['firewall_name']))
            $config['firewall_names'] = array_merge(array($config['firewall_name']), $config['firewall_names']);
        elseif (empty($config['firewall_names']) && isset($config['firewall_name']))
            $config['firewall_names'] = array($config['firewall_name']);

        $container->setParameter('oauth.firewall_names', $config['firewall_names']);

        // set target path parameter
        $container->setParameter('oauth.target_path_parameter', $config['target_path_parameter']);

        // set use referer parameter
        $container->setParameter('oauth.use_referer', $config['use_referer']);

        // set failed auth path
        $container->setParameter('oauth.failed_auth_path', $config['failed_auth_path']);
        
        $container->setParameter('oauth.default_target_path', $config['default_target_path']);
        
        $container->setParameter('oauth.register_target_path', $config['register_target_path']);
        
        // setup services for all configured resource owners
        $resourceOwners = array();
        foreach ($config['resource_owners'] as $name => $options) {
            $resourceOwners[$name] = $name;
            $this->createResourceOwnerService($container, $name, $options);
        }
        $container->setParameter('oauth.resource_owners', $resourceOwners);

        $oauthUtils = $container->getDefinition('oauth.security.oauth_utils');

        foreach ($config['firewall_names'] as $firewallName) {
            $oauthUtils->addMethodCall('addResourceOwnerMap', array(new Reference('oauth.resource_ownermap.'.$firewallName)));
        }

        // Symfony <2.6 BC
        // Go back to basic xml config after
        if (interface_exists('Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface')) {
            $oauthUtils->replaceArgument(1, new Reference('security.authorization_checker'));
        } else {
            $oauthUtils->replaceArgument(1, new Reference('security.context'));
        }

        if (isset($config['fosub'])) {
            $container
                ->setDefinition('oauth.user.provider.fosub_bridge', new DefinitionDecorator('oauth.user.provider.fosub_bridge.def'))
                ->addArgument($config['fosub']['properties'])
            ;
        }

        // check of the connect controllers etc should be enabled
        if (isset($config['connect'])) {
            $container->setParameter('oauth.connect', true);

            if (isset($config['fosub'])) {
                // setup fosub bridge services
                $container->setAlias('oauth.account.connector', 'oauth.user.provider.fosub_bridge');

                $container
                    ->setDefinition('oauth.registration.form.handler.fosub_bridge', new DefinitionDecorator('oauth.registration.form.handler.fosub_bridge.def'))
                    ->addArgument($config['fosub']['username_iterations'])
                    ->setScope('request')
                ;

                $container->setAlias('oauth.registration.form.handler', 'oauth.registration.form.handler.fosub_bridge');

                // enable compatibility with FOSUserBundle 1.3.x and 2.x
                if (interface_exists('FOS\UserBundle\Form\Factory\FactoryInterface')) {
                    $container->setAlias('oauth.registration.form.factory', 'fos_user.registration.form.factory');
                } else {
                    $container->setAlias('oauth.registration.form', 'fos_user.registration.form');
                }
            }

            foreach ($config['connect'] as $key => $serviceId) {
                if ('confirmation' === $key) {
                    $container->setParameter('oauth.connect.confirmation', $config['connect']['confirmation']);

                    continue;
                }

                $container->setAlias('oauth.'.str_replace('_', '.', $key), $serviceId);
            }

            // setup custom services
        } else {
            $container->setParameter('oauth.connect', false);
        }

        $container->setParameter('oauth.templating.engine', $config['templating_engine']);

        $container->setAlias('oauth.user_checker', 'security.user_checker');
    }

    /**
     * Creates a resource owner service.
     *
     * @param ContainerBuilder $container The container builder
     * @param string           $name      The name of the service
     * @param array            $options   Additional options of the service
     */
    public function createResourceOwnerService(ContainerBuilder $container, $name, array $options)
    {      
        // alias services
        if (isset($options['service'])) {
            // set the appropriate name for aliased services, compiler pass depends on it
            $container->setAlias('oauth.resource_owner.'.$name, $options['service']);
        } else {
            $type = $options['type'];
            unset($options['type']);

            $definition = new DefinitionDecorator('oauth.abstract_resource_owner.'.Configuration::getResourceOwnerType($type));
            $definition->setClass("%oauth.resource_owner.$type.class%");
            $container->setDefinition('oauth.resource_owner.'.$name, $definition);
            $definition
                ->replaceArgument(2, $options)
                ->replaceArgument(3, $name)
            ;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'oauth';
    }
}
