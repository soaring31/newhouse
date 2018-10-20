<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new Cocur\Slugify\Bridge\Symfony\CocurSlugifyBundle(),
            new CoreBundle\CoreBundle(),
            new ManageBundle\ManageBundle(),
            new SphinxBundle\SphinxBundle(),
            new OAuthBundle\OAuthBundle(),
            new WeixinBundle\WeixinBundle(),
            new HouseBundle\HouseBundle(),
            new MemberBundle\MemberBundle(),
            new HousemobileBundle\HousemobileBundle(),
            new CloudBundle\CloudBundle(),
            new FOS\RestBundle\FOSRestBundle(),
//            new JMS\SerializerBundle\JMSSerializerBundle(),
//            new AdminBundle\AdminBundle(),
//            new UyghurBundle\UyghurBundle(),
//			  new DevBundle\DevBundle(),
//            new UyghurmemberBundle\UyghurmemberBundle(),
//            new LhomeBundle\LhomeBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
            $bundles[] = new Nelmio\ApiDocBundle\NelmioApiDocBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__ . '/config/config_' . $this->getEnvironment() . '.yml');
    }
}
