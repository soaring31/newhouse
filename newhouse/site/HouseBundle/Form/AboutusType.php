<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年08月16日
*/
namespace HouseBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AboutusType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder     
            ->add('checked', 'hidden', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'审核',))     
            ->add('uid', 'hidden', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'会员id',))     
            ->add('category', 'hidden', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'栏目',))     
            ->add('name', 'text', array('label_attr'=>array('class'=>'w150 rtd txtright',),'required'=>'1','label'=>'标题',))     
            ->add('content', 'ueditor', array('label_attr'=>array('class'=>'w150 rtd txtright',),'required'=>'1','label'=>'内容','attr'=>array('data-ueditor-opt'=>'{initialFrameHeight:"400"}',),))     
            ->add('submit', 'submit', array('label'=>'提交','attr'=>array('data-role'=>'submit','class'=>'btn font-ico-22 fz12',),))        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HouseBundle\Entity\Service'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'aboutus';
    }
}
