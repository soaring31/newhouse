<?php
/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2016-06-20
 */
namespace OAuthBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Set the appropriate name for aliased services
 *
 * @author Tomas Pecserke <tomas.pecserke@gmail.com>
 */
class SetResourceOwnerServiceNameCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        foreach (array_keys($container->getAliases()) as $alias) {
            if (strpos($alias, 'hwi_oauth.resource_owner.') !== 0) {
                continue;
            }

            $aliasIdParts = explode('.', $alias);
            $resourceOwnerDefinition = $container->findDefinition($alias);
            $resourceOwnerDefinition->addMethodCall('setName', array(end($aliasIdParts)));
        }
    }
}
