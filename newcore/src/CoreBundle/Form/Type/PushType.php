<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年1月13日
*/
namespace CoreBundle\Form\Type;

use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Exception\LogicException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PushType extends BaseType
{
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    public function getParent()
    {
        return 'choice';
    }
    
    public function getName()
    {
        return 'push';
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'info' => array()
            ,'empty_value'=>''
            ,'compound' => false
        ));
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (!isset($options['info'])||!is_array($options['info'])||empty($options['info']))
            throw new LogicException('未定义info参数');
    }
    
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['attr'] = $options['attr'];
        $view->vars['required'] = (bool)$options['info']['required'];

        $view->vars['choices'] = self::pushHandle($options['info']);
    }
    
    private function pushHandle($options)
    {
        $params = array();
        $params['pageIndex'] = 1;
        $params['pageSize'] = 8;
 
        $_property = array('id');

        //关联字段        
        $property = explode(',',$options['property']);
        
        if(count($property)<2)
            $property = array_merge($_property,$property);
        
        if(count($property)<2)
            $property[] = 'name';
        
        $property[0] = $this->get('core.common')->ucWords($property[0]);
        $property[1] = $this->get('core.common')->ucWords($property[1]);
        
        $setkField = "set".$property[0];
        $getkField = "get".$property[0];
        $setvField = "set".$property[1];
        $getvField = "get".$property[1];
        
        //模型服务名称
        $serviceName = $options['entitypath'];

        $validateRule = $options['validate_rule'];

        //查询条件
        $params = array_merge($params, $this->get('core.common')->getQueryParam($options['query_builder']));

        $info = array();

        $validateRule = $validateRule?$this->get('core.common')->getQueryParam($validateRule):array();

        $catePushs = $this->get($serviceName)->findBy($params);

        if(!isset($catePushs['data'])||empty($catePushs['data']))
            return array();
        
        foreach($catePushs['data'] as $item)
        {
            if(method_exists($item, $getkField)&&method_exists($item, $getvField)&&method_exists($item, 'getModels')&&method_exists($item, 'getFromid')&&$item->getModels()&&$item->getFromid()>0)
            {
                if(!$item->$getkField()||!$item->$getvField())
                {
                    $modelInfo = $this->get('db.models')->getData($item->getModels(),'name');
                    
                    if($modelInfo)
                    {
                        $map = array();
                        $map['id'] = $item->getFromid();
                        $subPush = $this->get($modelInfo['service'])->findOneBy($map);
                        
                        if($subPush&&method_exists($subPush, $getkField)&&!$item->$getkField())
                            $item->$setkField($subPush->$getkField());
                        
                        if($subPush&&method_exists($subPush, $getvField)&&!$item->$getvField())
                            $item->$setvField($subPush->$getvField());
                    }
                }
            }
            
            $info[$item->$getkField()] = $item->$getvField();
        }
        
        return $info;
    }
}