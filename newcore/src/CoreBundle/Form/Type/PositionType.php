<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年5月25日
*/
namespace CoreBundle\Form\Type;

use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PositionType extends BaseType
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        //$view->vars['full_name'] .= '[]';
        $view->vars['choices'] = $options['choices'];
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $data = array();
        $data[1] = '左上';
        $data[2] = '中上';
        $data[3] = '右上';
        $data[4] = '左中';
        $data[5] = '居中';
        $data[6] = '右中';
        $data[7] = '左下';
        $data[8] = '中下';
        $data[9] = '右下';
        $resolver->setDefaults(array(
            'info' => array()
            ,'required' => false
            ,'choices' => $data
            ,'compound' => false
        ));
    }
    
    public function getName()
    {
        return 'position';
    }
}