<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年08月24日
*/
namespace ManageBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AuthorityfirewallshowType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder     
            ->add('name', 'text', array('label_attr'=>array('class'=>'w150 rtd txtright',),'required'=>'1','label'=>'名称','attr'=>array('class'=>'w250','data-tpl'=>'%x%<div class="tip">中文名称</div>',),))     
            ->add('path', 'textarea', array('label_attr'=>array('class'=>'w150 rtd txtright',),'required'=>'1','label'=>'规则','attr'=>array('class'=>'w250','data-tpl'=>'%x%<div class="tip">正则匹配</div>',),))     
            ->add('ips', 'text', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'IP集','attr'=>array('class'=>'w250',),))     
            ->add('remarks', 'textarea', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'备注','attr'=>array('class'=>'w250',),))     
            ->add('sort', 'text', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'排序',))     
            ->add('status', 'choice', array('label_attr'=>array('class'=>'w150 rtd txtright',),'required'=>'1','choices'=>array(1=>'是',),'label'=>'是否启用',))        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'authorityfirewallshow';
    }
}
