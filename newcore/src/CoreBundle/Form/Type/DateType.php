<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年7月13日
*/
namespace CoreBundle\Form\Type;

use Doctrine\DBAL\Types\Type;
use Symfony\Component\DependencyInjection\ContainerInterface;
// use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType as baseDateType;

class DateType extends baseDateType
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    /**
     * 获得服务
     * @param int $id
     * @throws \InvalidArgumentException
     */
    protected function get($id)
    {
        /**
         * 兼容3.0之前的版本request服务
         */
        if($id=='request')
            return $this->container->get('request_stack')->getCurrentRequest();
    
        if (!$this->container->has($id))
            throw new \InvalidArgumentException("[".$id."]服务未注册。");
    
        return $this->container->get($id);
    }
    
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return Type::DATE;
    }

//     public function setDefaultOptions(OptionsResolverInterface $resolver)
//     {
//         $resolver->setDefaults(array(
//             'info' => array()
//             ,'compound' => false
//         ));
//     }

    /**
     * 处理规则
     * 注：本方法中针对 $obj不要使用原生 method_exists,要使用 parent::method_exists
     */
    public function handleRule()
    {
        $form = func_get_arg(0);
        $obj = func_get_arg(1);
        $formRequest = func_get_arg(2);
        
        //如果没定义或者为空，取默认值
        if (!array_key_exists($form['name'], $formRequest) || isset($formRequest[$form['name']]) && $formRequest[$form['name']] == '')
        {
            if (isset($form['value']) && $form['value'] != '')
                $obj->{'set'.$this->get('core.common')->ucWords($form['name'])}($form['value']);
            else 
                $obj->{'set'.$this->get('core.common')->ucWords($form['name'])}('1970-01-01');
        } else {
            $obj->{'set'.$this->get('core.common')->ucWords($form['name'])}($formRequest[$form['name']]);
        }
    }
}