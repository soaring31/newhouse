<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年7月13日
*/
namespace CoreBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DatetimeType extends BaseType
{
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'info' => array()
            ,'compound' => false
        ));
    }

    public function getName()
    {
        return 'datetime';
    }
}