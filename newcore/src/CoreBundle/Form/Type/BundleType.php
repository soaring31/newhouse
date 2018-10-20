<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年2月22日
*/
namespace CoreBundle\Form\Type;

use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BundleType extends BaseType
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'info' => array()
            //,'choices' => $this->get('core.common')->getBundles()
            ,'compound' => false
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if(isset($options['attr']['multiple']))
            $view->vars['full_name'] .= '[]';
        if(isset($options['attr']['type'])&&$options['attr']['type']=='checkbox')
            $view->vars['full_name'] .= '[]';

        $type = isset($options['attr']['type'])?$options['attr']['type']:'';

        if(!isset($options['attr']['style']))
        {
            if(isset($options['attr']['multiple']))
                $options['attr']['style'] = "height:100px !important;";
            // else
            //     $options['attr']['style'] = "height:25px !important;";
        }

        unset($options['attr']['type']);


        $view->vars['type'] = $type;
        $view->vars['attr'] = $options['attr'];
        $view->vars['required'] = (bool)$options['info']['required'];

        //查询条件
        $query_builder = $this->get('core.common')->getQueryParam($options['info']['query_builder']);

        $data = array();
        $bundles = $this->get('core.common')->getBundles(isset($query_builder['name'])?$query_builder['name']:'08cms');
        foreach (array_keys($bundles) as $value) {
            $data[$value] = $value;
        }

        $view->vars['choices'] = $data;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'choice';
    }

    public function getName()
    {
        return 'bundle';
    }
}