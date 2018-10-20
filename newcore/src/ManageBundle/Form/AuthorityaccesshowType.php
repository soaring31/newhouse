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

class AuthorityaccesshowType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder     
            ->add('username', 'text', array('label_attr'=>array('class'=>'w150 rtd txtright',),'required'=>'1','label'=>'会员帐号','attr'=>array('class'=>'w200',),))     
            ->add('group_id', 'hidden', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'角色组ID',))     
            ->add('submit', 'submit', array('label'=>'submit','attr'=>array('class'=>'btn','data-submit'=>'post','data-close'=>'1','data-role'=>'submit','data-reload'=>'1',),))        ;
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
        return 'authorityaccesshow';
    }
}
