<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年08月21日
*/
namespace ManageBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ModelsfieldshowType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder     
            ->add('name', 'text', array('label_attr'=>array('class'=>'w150 rtd txtright',),'required'=>'1','label'=>'字段名','attr'=>array('placeholder'=>'请输入英文字母开头字段名','class'=>'w200',),))     
            ->add('title', 'text', array('label_attr'=>array('class'=>'w150 rtd txtright',),'required'=>'1','label'=>'字段标题','attr'=>array('class'=>'w200','placeholder'=>'请输入表单显示标题',),))     
            ->add('type', 'choice', array('label_attr'=>array('class'=>'w150 rtd txtright',),'required'=>'1','choices'=>array('string'=>'字符串','numeric'=>'数字','smallint'=>'短数字','float'=>'浮点','textarea'=>'文本框','date'=>'日期','time'=>'时间','bool'=>'布尔','select'=>'枚举','radio'=>'单选','blob'=>'ueditor','file'=>'上传附件',),'label'=>'字段类型','attr'=>array('class'=>'w100',),))     
            ->add('length', 'text', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'字段长度','attr'=>array('class'=>'w100',),))     
            ->add('extra', 'textarea', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'参数','attr'=>array('class'=>'w200','placeholder'=>'字段类型为布尔、单选、多选、枚举和级联选择时的定义数据,其它字段类型为空',),))     
            ->add('value', 'text', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'默认值','attr'=>array('class'=>'w100',),))     
            ->add('remark', 'text', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'字段备注','attr'=>array('class'=>'w200',),))     
            ->add('status', 'choice', array('label_attr'=>array('class'=>'w150 rtd txtright',),'required'=>'1','choices'=>array('1'=>'是',),'label'=>'是否启用','attr'=>array('class'=>'w100',),))     
            ->add('submit', 'submit', array('label'=>'submit','attr'=>array('class'=>'search-btn ajax-post reload','target-form'=>'form',),))     
            ->add('model_id', 'hidden', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'模型ID',))     
            ->add('isindex', 'choice', array('label_attr'=>array('class'=>'w150 rtd txtright',),'choices'=>array('1'=>'是',),'label'=>'是否索引','attr'=>array('class'=>'w100',),))        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ManageBundle\Entity\ModelAttribute'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'modelsfieldshow';
    }
}
