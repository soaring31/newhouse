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

class AreathemeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder     
            ->add('name', 'ajaxbind', array('label_attr'=>array('class'=>'w150 rtd txtright',),'required'=>'1','label'=>'地区',))     
            ->add('themes', 'entity', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'主题',))     
            ->add('type', 'hidden', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'是否分站',))     
            ->add('submit', 'submit', array('label'=>'提交','attr'=>array('class'=>'btn font-ico-22','data-role'=>'submit','data-reload'=>'1','data-close'=>'1',),))        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ManageBundle\Entity\Area'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'areatheme';
    }
}
