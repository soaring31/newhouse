<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年10月24日
*/
namespace HouseBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class Mob_saleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder     
            ->add('models', 'hidden', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'模型',))     
            ->add('uid', 'hidden', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'会员id',))     
            ->add('thumb', 'image', array('label_attr'=>array('class'=>'w150 rtd txtright',),'info'=>array('model_form_id'=>268,'iswatermark'=>'textFun',),'label'=>'缩略图','attr'=>array('service'=>'house.sale_arc','servicefield'=>'thumb',),))     
            ->add('tujis', 'image', array('label_attr'=>array('class'=>'w150 rtd txtright',),'info'=>array('model_form_id'=>267,),'label'=>'图集','attr'=>array('multiple'=>1,),))     
            ->add('valid', 'hidden', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'上架',))     
            ->add('lpmc', 'text', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'楼盘名称','attr'=>array('placeholder'=>'请输入名称',),))     
            ->add('shi', 'entity', array('label_attr'=>array('class'=>'w150 rtd txtright',),'info'=>array('entitypath'=>'house.category','property'=>'name','model_form_id'=>268,'query_builder'=>'ename=shi',),'label'=>'室',))     
            ->add('ting', 'entity', array('label_attr'=>array('class'=>'w150 rtd txtright',),'info'=>array('entitypath'=>'house.category','property'=>'name','model_form_id'=>268,'query_builder'=>'ename=ting',),'label'=>'厅',))     
            ->add('wei', 'entity', array('label_attr'=>array('class'=>'w150 rtd txtright',),'info'=>array('entitypath'=>'house.category','property'=>'name','model_form_id'=>268,'query_builder'=>'ename=wei',),'label'=>'卫',))     
            ->add('region', 'entity', array('label_attr'=>array('class'=>'w150 rtd txtright',),'info'=>array('entitypath'=>'db.area','property'=>'name','model_form_id'=>268,'query_builder'=>'pid={area}',),'label'=>'区域',))     
            ->add('esflx', 'entity', array('label_attr'=>array('class'=>'w150 rtd txtright',),'info'=>array('entitypath'=>'house.category','property'=>'name','model_form_id'=>267,'query_builder'=>'ename=esflx',),'label'=>'二手房类型','attr'=>array('class'=>'select',),))     
            ->add('zj', 'integer', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'总价','attr'=>array('data-tpl'=>'%x%<span class="tip">万元</span>','placeholder'=>'请输入总价',),))     
            ->add('mj', 'text', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'面积','attr'=>array('data-tpl'=>'%x%<span class="tip">㎡</span>','placeholder'=>'请输入面积',),))     
            ->add('dj', 'text', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'单价','attr'=>array('data-tpl'=>'%x%<span class="tip">元/㎡</span>','placeholder'=>'请输入单价',),))     
            ->add('name', 'text', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'房源标题','attr'=>array('placeholder'=>'请输入房源标题',),))     
            ->add('address', 'text', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'地址','attr'=>array('placeholder'=>'请输入地址',),))     
            ->add('fwjg', 'entity', array('label_attr'=>array('class'=>'w150 rtd txtright',),'info'=>array('entitypath'=>'house.category','property'=>'name','model_form_id'=>267,'query_builder'=>'ename=fwjg',),'label'=>'房屋结构',))     
            ->add('zxcd', 'entity', array('label_attr'=>array('class'=>'w150 rtd txtright',),'info'=>array('entitypath'=>'house.category','property'=>'name','model_form_id'=>268,'query_builder'=>'ename=zxcd',),'label'=>'装修程度',))     
            ->add('cx', 'entity', array('label_attr'=>array('class'=>'w150 rtd txtright',),'info'=>array('entitypath'=>'house.category','property'=>'name','model_form_id'=>268,'query_builder'=>'ename=cx',),'label'=>'朝向',))     
            ->add('fl', 'entity', array('label_attr'=>array('class'=>'w150 rtd txtright',),'info'=>array('entitypath'=>'house.category','property'=>'name','model_form_id'=>268,'query_builder'=>'ename=fl',),'label'=>'房龄',))     
            ->add('szlc', 'text', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'所在楼层','attr'=>array('data-tpl'=>'%x%<div class="tip">层</div>','placeholder'=>'请输入楼层',),))     
            ->add('zlc', 'text', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'总楼层','attr'=>array('data-tpl'=>'%x%<span class="tip">层</span>','placeholder'=>'请输入楼层',),))     
            ->add('content', 'textarea', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'房源描述','attr'=>array('placeholder'=>'请输入详细内容...',),))     
            ->add('tel', 'text', array('label_attr'=>array('class'=>'w150 rtd txtright',),'required'=>'1','label'=>'电话','attr'=>array('placeholder'=>'请输入联系方式',),))     
            ->add('xingming', 'text', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'联系人','attr'=>array('placeholder'=>'请输入联系人姓名',),))        ;
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
        return 'mob_sale';
    }
}
