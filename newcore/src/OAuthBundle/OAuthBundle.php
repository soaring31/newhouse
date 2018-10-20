<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016-06-20
*/
namespace OAuthBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use OAuthBundle\DependencyInjection\Security\Factory\OAuthFactory;
use OAuthBundle\DependencyInjection\CompilerPass\SetResourceOwnerServiceNameCompilerPass;

class OAuthBundle extends Bundle
{
    /**
     * {@inheritDoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
    
        /** @var $extension SecurityExtension */
        $extension = $container->getExtension('security');
        $extension->addSecurityListenerFactory(new OAuthFactory());
    
        $container->addCompilerPass(new SetResourceOwnerServiceNameCompilerPass());
    }
    
    /**
     * {@inheritdoc}
     */
    public function getContainerExtension()
    {
        // return the right extension instead of "auto-registering" it. Now the
        // alias can be hwi_oauth instead of hwi_o_auth..
        if (null === $this->extension) {
            return new \OAuthBundle\DependencyInjection\OAuthExtension();
        }
    
        return $this->extension;
    }
    
    public function getCompany()
    {
        return '08cms';
    }
}
