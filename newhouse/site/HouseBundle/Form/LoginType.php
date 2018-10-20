<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年1月11日
*/
namespace HouseBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LoginType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('userName',null, array('label'=>'login.username' ,'attr' => array('maxlength' => 25, 'class'=>'inputUP')))
            ->add('passWord','password', array('label'=>'login.password' ,'attr' => array('maxlength' => 25, 'class'=>'inputUP')))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => true,
            'csrf_field_name' => 'csrf_token',
            'intention'  => 'authenticate'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return '';
    }
}