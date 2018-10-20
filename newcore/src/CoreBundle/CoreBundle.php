<?php

namespace CoreBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use CoreBundle\DependencyInjection\Compiler\TemplatePath;
use CoreBundle\DependencyInjection\Compiler\ValidatorPath;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class CoreBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
    
        $container->addCompilerPass(new ValidatorPath());
    
        $container->addCompilerPass(new TemplatePath());
    }

    public function getCompany()
    {
        return '08core';
    }
}