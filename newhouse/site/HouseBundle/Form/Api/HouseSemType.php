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
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class HouseSemType extends BaseFromType
{
    const CHANNELS = [
        1 => 'baidu(百度)',
        2 => '360(360)',
        3 => 'sogo(搜狗)',
        4 => 'shenma(神马)',
    ];

    const CHANNEL_ITEMS = [
        1 => 'baidu',
        2 => '360',
        3 => 'sogo',
        4 => 'shenma',
    ];

    const TYPES = [
        1 => 'sem',
        2 => 'dsp'
    ];

    const SEM_CHANNELS = [
        1 => 'baidu',
        2 => '360',
        3 => 'sogo',
        4 => 'shenma',
    ];

    const DSP_CHANNELS = [
        1 => 'qtt',
        2 => 'baidu',
        3 => 'wangyi',
        4 => 'ydzx',
        5 => 'gdt',
    ];

    const PLATFORMS = [
        0 => '',    // dsp 没有这个字段
        1 => 'wap',
        2 => 'pc'
    ];

    const ADD_TYPE = [
        1 => '添加',
        2 => '删除'
    ];

    const STATUS_OK = 1;
    const STATUS_NOT = 2;
    const STATUS_UNBIND = 3;

    const STATUSES = [
        self::STATUS_OK     => '已绑定',
        self::STATUS_NOT    => '未绑定',
        self::STATUS_UNBIND => '已解绑',
    ];

    /**
     * @param array $a
     *
     * @return string
     */
    private function arrayToString(array $a)
    {
        $str = '';
        foreach ($a as $k => $v) {
            $str .= $k . ':' . $v . ' ';
        }

        return $str;
    }

    private function filterArray(array $a)
    {
        return array_unique(array_filter($a));
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $channels  = $this->arrayToString(self::CHANNELS);
        $types     = $this->arrayToString(self::TYPES);
        $platforms = $this->arrayToString(self::PLATFORMS);
        $addTypes  = $this->arrayToString(self::ADD_TYPE);

        $builder
            ->add('house_ids', null, array('label' => '楼盘 id集合, 逗号分割(1,2,3,4)'))
            ->add('channel_ids', null, array('label' => '推广渠道 id集合, 逗号分割' . "($channels)"))
            ->add('add_type', null, array('label' => '添加类型 单选' . "($addTypes)"))
            ->add('type', null, array('label' => '推广类型 单选' . "($types)"))
            ->add('platform', null, array('label' => '平台端 单选' . "($platforms)"));

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            $data = $form->getData();

            $platform   = $data['platform'];
            $channelIds = $data['channel_ids'];

            // 验证 platform
            if (!array_key_exists($platform, self::PLATFORMS))
                $form->addError(new FormError('平台类型不支持'));

            $channelIds = $this->filterArray(explode(',', $channelIds));
            switch ($platform) {
                // sem
                case 1:
                    foreach ($channelIds as $channelId) {
                        if (empty(self::SEM_CHANNELS[$channelId]))
                            $form->addError(new FormError('sem投放渠道错误'));
                    }
                    break;
                // dsp
                case 2:
                    foreach ($channelIds as $channelId) {
                        if (empty(self::DSP_CHANNELS[$channelId]))
                            $form->addError(new FormError('dsp投放渠道错误'));
                    }
                    break;
            }
        });
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