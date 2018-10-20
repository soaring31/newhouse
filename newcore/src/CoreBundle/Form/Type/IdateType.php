<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年7月13日
*/
namespace CoreBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class IdateType extends BaseType
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'idate';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'info' => array()
            ,'compound' => false
        ));
    }

    /**
     * 处理规则
     * 注：本方法中针对 $obj不要使用原生 method_exists,要使用 parent::method_exists
     */
    public function handleRule()
    {
        $form = func_get_arg(0);
        $obj = func_get_arg(1);
        $formRequest = func_get_arg(2);

        $nameVal = isset($formRequest[$form['name']])?$formRequest[$form['name']]:'';
        
        $action = "set".$this->get('core.common')->ucWords($form['name']);

        if(is_array($nameVal))
            $nameVal = implode(',', $nameVal);

        //转换成unix时间
        if(!is_numeric($nameVal))
            $nameVal = strtotime($nameVal);

        if(parent::method_exists($obj, $action))
            call_user_func_array(array($obj, $action),array($nameVal));
            //$obj->{"set".$this->get('core.common')->ucWords($form['name'])}($nameVal);
        elseif(is_array($obj))
            $obj[$form['name']] = $nameVal;

        if(is_array($obj))
            return $obj;
    }
}