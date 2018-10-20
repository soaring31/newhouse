<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年3月15日
*/
namespace CoreBundle\Twig\Extension;

class ApplyFilterExtension extends \Twig_Extension
{
    public function getName()
    {
        return 'apply_filter';
    }

    public function getFilters()
    {
        return array(
            'apply_filter' =>
                new \Twig_SimpleFilter(
                    'apply_filter',
                    array($this, 'applyFilter'),
                    array(
                        'needs_environment' => true,
                        'needs_context' => true,
                    )
                )
        );
    }

    public function applyFilter(\Twig_Environment $env, $context = array(), $value, $filters)
    {
        $name = 'apply_filter_' . md5($filters);

        $template = sprintf('{{ %s|%s }}', $name, $filters);
        $template = $env->loadTemplate($template);

        $context[$name] = $value;

        return $template->render($context);
    }

}