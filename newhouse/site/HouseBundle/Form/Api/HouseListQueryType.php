<?php
/**
 * Created by PhpStorm.
 * User: zhangshuzhen
 * Date: 2018/4/22
 * Time: 12:52
 */

namespace HouseBundle\Form\Api;

use HouseBundle\Form\BaseFromType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class HouseListQueryType extends BaseFromType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('area', null, array('label' => '地区'))
            ->add('city', null, array('label' => '地区简称'))
            ->add('keyword', null, array('label' => '关键词'))
            ->add('region', null, array('label' => '区域'))
            ->add('cate_circle', null, array('label' => '商圈'))
            ->add('cate_metro', null, array('label' => '地铁站'))
            ->add('cate_line', null, array('label' => '地铁线'))
            ->add('price', 'text', array('label' => '价格 1000|2000'))
            ->add('room', 'text', array('label' => '户型'))
            ->add('tslp', 'text', array('label' => '特色'))
            ->add('cate_type', 'text', array('label' => '楼盘类型'))
            ->add('tag', 'text', array('label' => '标签'))
            ->add('order', 'text', array('label' => '排序 kpdate|desc,dj|asc'))
            ->add('page', 'text', array('label' => '页数'))
            ->add('limit', 'text', array('label' => '每页数量'));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false
        ]);
    }
}