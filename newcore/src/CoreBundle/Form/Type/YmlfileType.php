<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年5月31日
*/
namespace CoreBundle\Form\Type;

use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Exception\LogicException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class YmlfileType extends BaseType
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (!isset($options['info'])||!is_array($options['info'])||empty($options['info']))
            throw new LogicException('未定义info参数');
        
        if (!isset($options['info']['entitypath'])||empty($options['info']['entitypath']))
            throw new LogicException('未定义查询服务参数');
    }
    
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if(isset($options['attr']['multiple']))
        {
            $view->vars['full_name'] .= '[]';
            if(!isset($options['attr']['style']))
                $options['attr']['style'] = "height:100px !important;";
        }
        $view->vars['attr'] = $options['attr'];
        $view->vars['required'] = (bool)$options['info']['required'];
        $view->vars['choices'] = $this->_ymlAttr($options['info']);
    }
    
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'info' => array()
            ,'empty_value'=>''
            ,'placeholder'=>''
            ,'compound'=>false
        ));
    }
    
    private function _ymlAttr($vo)
    {
        $choices = array();
     
        if(!is_array($vo))
            return $choices;
        
        if(!isset($vo['entitypath'])||empty($vo['entitypath']))
            return $choices;
        
        $file = $vo['entitypath'];
        
        //关联字段
        $property = $vo['property'];
        
        if($property)
            $property = explode(',', $property);
        
        $filePath = $this->get('core.common')->getSiteRoot()."Config".DIRECTORY_SEPARATOR.$file;
        $info = $this->get('core.ymlParser')->ymlRead($filePath);
        
        $field = "id";

        if(count($property)>1)
            $field = array_shift($property);
        
        foreach($info as $key=>$item)
        {
            //主键为ID或第一个设定值
            $id = isset($item[$field])?$item[$field]:$key;
            
            $_poo = "";
            foreach($property as $poo)
            {
                if(isset($item[$poo]))
                    $_poo .= "|".$item[$poo];
            }
            $choices[$id] = trim($_poo, "|");
        }
        return $choices;
    }
    
    public function getName()
    {
        return 'ymlfile';
    }
}