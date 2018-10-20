<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年6月7日
*/
namespace CoreBundle\Form\Type;

use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BaidumapType extends BaseType
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'info' => array()
            ,'compound' => false
        ));
    }
    
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        //是否必填
        $view->vars['required'] = (bool)$options['info']['required'];
    }

    /**
     * 处理规则
     * 注：本方法中针对 $obj不要使用原生 method_exists,要使用 parent::method_exists
     * @param array $form     当前表单某字段的配置
     * @param unknown $obj    写表对象数据
     * @throws \InvalidArgumentException
     */
    public function handleRule()
    {
        $form = func_get_arg(0);
        $obj = func_get_arg(1);
        $formRequest = func_get_arg(2);

        $nameVal = isset($formRequest[$form['name']])?$formRequest[$form['name']]:'';

        // dump($nameVal);die();
        if(is_array($nameVal))
            $nameVal = implode(',', $nameVal);
        if(parent::method_exists($obj, "set".$this->get('core.common')->ucWords($form['name'])))
        {
            
            if(parent::method_exists($obj, "setLng"))
                $obj->setLng($this->get('request')->get('lng', ''));

            if(parent::method_exists($obj, "setLat"))
                $obj->setLat($this->get('request')->get('lat', ''));
            $obj->{"set".$this->get('core.common')->ucWords($form['name'])}($nameVal);
        }elseif(is_array($obj)){
            $obj[$form['name']] = $nameVal;
            $obj['lng'] = $obj->setLng($this->get('request')->get('lng', ''));
            $obj['lat'] = $obj->setLat($this->get('request')->get('lat', ''));            
        }

        if(is_array($obj))
            return $obj;
    }
    
    public function getName()
    {
        return 'baidumap';
    }
}