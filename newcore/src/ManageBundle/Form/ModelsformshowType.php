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

class ModelsformshowType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder     
            ->add('name', 'text', array('label_attr'=>array('class'=>'w150 rtd txtright',),'required'=>'1','label'=>'表单英文名称','attr'=>array('class'=>'w150','data-tpl'=>'%x% <div class=tip>只能输入英文字母</div>',),))     
            ->add('title', 'text', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'描述','attr'=>array('class'=>'w150','data-tpl'=>'%x% <div class=tip>中文名称</div>',),))     
            ->add('submit', 'submit', array('label'=>'submit','attr'=>array('class'=>'search-btn ajax-post reload','target-form'=>'form',),))     
            ->add('model_id', 'entity', array('label_attr'=>array('class'=>'w150 rtd txtright',),'info'=>array('entitypath'=>'db.models','property'=>'name','model_form_id'=>3,'query_builder'=>'order=name
orderBy=asc',),'label'=>'所属模型','attr'=>array('class'=>'w150','data-tpl'=>'%x% <div class=tip>必须选择所属模型</div>',),))     
            ->add('parent_form', 'entity', array('label_attr'=>array('class'=>'w150 rtd txtright',),'info'=>array('choices'=>'1=是
0=否','entitypath'=>'db.model_form','property'=>'id,name,title','model_form_id'=>3,'query_builder'=>'order=name
fmgroup=1
orderBy=desc','dealhtml'=>'clearhtml',),'label'=>'继承表单','attr'=>array('data-tpl'=>'%x% <div class=tip>文本框输入关键字可直接搜索</div>',),))     
            ->add('bindform', 'entity', array('label_attr'=>array('class'=>'w150 rtd txtright',),'info'=>array('entitypath'=>'db.model_form','property'=>'name','model_form_id'=>3,'query_builder'=>'order=name',),'label'=>'关联表单',))     
            ->add('bindfield', 'text', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'关联字段','attr'=>array('class'=>'w150',),))     
            ->add('initcondition', 'textarea', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'初始化条件','attr'=>array('maxlength'=>10000,'cols'=>60,),))     
            ->add('initmodel', 'entity', array('label_attr'=>array('class'=>'w150 rtd txtright',),'info'=>array('entitypath'=>'db.models','property'=>'service_name,name','model_form_id'=>3,'query_builder'=>'order=name
orderBy=asc',),'label'=>'初始化模型',))     
            ->add('url', 'text', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'url','attr'=>array('class'=>'w150','data-tpl'=>'%x% <div class=tip>默认控制器的show方法绑定表单,若不是show方法,请在此填写对应的控制器方法</div>',),))     
            ->add('type', 'choice', array('label_attr'=>array('class'=>'w150 rtd txtright',),'required'=>'1','choices'=>array(1=>'纵表',),'label'=>'表类型','attr'=>array('chosen-opt'=>'{width:"150px",disable_search:true}',),))     
            ->add('remark', 'textarea', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'备注','attr'=>array('class'=>'w150',),))     
            ->add('fmgroup', 'choice', array('label_attr'=>array('class'=>'w150 rtd txtright',),'choices'=>array(1=>'继承',2=>'区块文档',3=>'文档',4=>'推送',5=>'会员认证',6=>'会员表单',7=>'分类方案',),'label'=>'表单组','attr'=>array('class'=>'w80',),))     
            ->add('status', 'choice', array('label_attr'=>array('class'=>'w150 rtd txtright',),'required'=>'1','choices'=>array(1=>'是',),'label'=>'是否启用','attr'=>array('chosen-opt'=>'{width:"150px",disable_search:true}',),))        ;
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
        return 'modelsformshow';
    }
}
