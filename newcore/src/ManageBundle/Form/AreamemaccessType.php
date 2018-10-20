<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年08月16日
*/
namespace ManageBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AreamemaccessType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder     
            ->add('group_id', 'hidden', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'角色组ID',))     
            ->add('uid', 'hidden', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'会员id',))     
            ->add('username', 'text', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'会员帐号',))     
            ->add('aid', 'entity', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'地区',))     
            ->add('title', 'text', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'备注',))        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ManageBundle\Entity\AuthAccess'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'areamemaccess';
    }
}
