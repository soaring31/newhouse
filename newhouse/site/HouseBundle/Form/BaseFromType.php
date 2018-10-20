<?php
/**
 * Created by PhpStorm.
 * User: zhangshuzhen
 * Date: 2018/4/22
 * Time: 12:53
 */

namespace HouseBundle\Form;



use Symfony\Component\Form\AbstractType;

abstract class BaseFromType extends AbstractType
{
    /**
     * @return string
     */
    public function getName()
    {
        return '';
    }
}