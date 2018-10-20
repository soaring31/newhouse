<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年3月2日
*/
namespace CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RegisterType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('name', null, array('label'=>'models.name', 'attr'=>array('class'=>'w120')))
        ->add('title', null, array('label'=>'models.title', 'attr'=>array('class'=>'w120')))
        ->add('bundle', null, array('label'=>'models.bundle', 'attr'=>array('class'=>'w120')))
        ->add('submit','submit', array('label'=>'submit' ,'attr' => array('class'=>'search-btn ajax-post', 'target-form'=>'form')))
        ;
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return '';
    }
}