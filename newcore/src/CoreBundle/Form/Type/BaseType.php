<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年4月28日
*/
namespace CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\DependencyInjection\ContainerInterface;
use CoreBundle\Functions\FormEntity;



class BaseType extends AbstractType
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
     * 处理规则
     * 注：本方法中针对 $obj不要使用原生 method_exists,要使用 self::method_exists
     * @param array $form         表单配置参数
     * @param object $obj         写表对象数据
     * @param array $formRequest  表单提交数据集
     * @throws \InvalidArgumentException
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

        if(self::method_exists($obj, $action))
            call_user_func_array(array($obj, $action),array($nameVal));
            //$obj->{"set".$this->get('core.common')->ucWords($form['name'])}($nameVal);
        elseif(is_array($obj))
            $obj[$form['name']] = $nameVal;

        if(is_array($obj))
            return $obj;
    }

    public function getName()
    {
        return '';
    }
    
    // 检查entity是否有某方法
    protected function method_exists($entity, $method)
    {
        if($entity instanceof FormEntity) {
            return $entity->method_exists($method);
        }
        return method_exists($entity, $method);
    }

}