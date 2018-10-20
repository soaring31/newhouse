<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年08月21日
*/
namespace HouseBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class Houses_doorType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder     
            ->add('models', 'hidden', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'模型',))     
            ->add('abstract', 'textarea', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'说明',))     
            ->add('thumb', 'image', array('label_attr'=>array('class'=>'w150 rtd txtright',),'required'=>'1','label'=>'户型图','attr'=>array('thumbType'=>'1','width'=>'150','height'=>'150',),))     
            ->add('mj', 'integer', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'面积','attr'=>array('data-tpl'=>'%x%<div class="tip">单位：M²</div>',),))     
            ->add('dj', 'integer', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'单价','attr'=>array('data-tpl'=>'%x%<div class="tip">单位：元/M²</div>',),))     
            ->add('zlhx', 'switch', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'主力户型','attr'=>array('data-switch-opt'=>'{onText: "是", offText: "否"}',),))     
            ->add('tujis', 'image', array('label_attr'=>array('class'=>'w150 rtd txtright',),'required'=>'1','label'=>'图集','attr'=>array('multiple'=>'1',),))     
            ->add('aid', 'hidden', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'楼盘id',))     
            ->add('form', 'hidden', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'表单',))     
            ->add('cate_pushs', 'hidden', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'标识',))     
            ->add('ting', 'entity', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'0厅','placeholder'=>'0厅',))     
            ->add('chu', 'entity', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'0厨','placeholder'=>'0厨',))     
            ->add('wei', 'entity', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'0卫','placeholder'=>'0卫',))     
            ->add('yangtai', 'entity', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'0阳台','placeholder'=>'0阳台',))     
            ->add('group_huxing', 'text', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'户型','attr'=>array('data-compound'=>'shi,ting,chu,wei,yangtai',),'compound'=>'1',))     
            ->add('ss_build', 'entity', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'所属栋楼',))     
            ->add('cate_type', 'entity', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'物业类型','attr'=>array('type'=>'radio',),))     
            ->add('checked', 'hidden', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'审核',))     
            ->add('uid', 'hidden', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'会员id',))     
            ->add('name', 'text', array('label_attr'=>array('class'=>'w150 rtd txtright',),'required'=>'1','label'=>'标题',))     
            ->add('shi', 'entity', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'室',))     
            ->add('submit', 'submit', array('label'=>'提交','attr'=>array('class'=>'btn font-ico-22','data-role'=>'submit','data-reload'=>'1','data-close'=>'1',),))     
            ->add('cate_status', 'entity', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'销售状态','attr'=>array('type'=>'radio',),))     
            ->add('group_attr', 'text', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'户型-基本属性','attr'=>array('data-compound'=>'name,group_area,zlhx,dj,mj,group_huxing,cate_type,cate_status,ss_build',),'compound'=>'1',))     
            ->add('group_set', 'text', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'详情设置','attr'=>array('data-compound'=>'thumb,tujis,abstract',),'compound'=>'1',))        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HouseBundle\Entity\HousesArc'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'houses_door';
    }
}
