<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017-05-31
*/
namespace CloudBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use CloudBundle\DependencyInjection\Compiler\TaggedServicesPass;

class CloudBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new TaggedServicesPass());
    }

    public function getCompany()
    {
        return '08core';
    }
}
