<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年6月4日
*/
namespace CoreBundle\Form\Type;

use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Exception\LogicException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AbstracttextType extends BaseType
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (!isset($options['info'])||!is_array($options['info'])||empty($options['info']))
            throw new LogicException('未定义info参数');

        if (!isset($options['attr']['property'])||empty($options['attr']['property']))
            throw new LogicException('未定义property参数');
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        //$view->vars['full_name'] .= '[]';

        $urlParam = array();
        $type = isset($options['attr']['type'])?$options['attr']['type']:'image';

        unset($options['attr']['width']);
        unset($options['attr']['height']);
        unset($options['attr']['multiple']);

        switch($type)
        {
            case "image":
                $urlParam['multiple'] = 0;
                $urlParam['width'] = isset($options['attr']['width'])?$options['attr']['width']:100;
                $urlParam['height'] = isset($options['attr']['height'])?$options['attr']['height']:100;

                $view->vars['multiple'] = $urlParam['multiple'];
                $view->vars['width'] = $urlParam['width'];
                $view->vars['height'] = $urlParam['height'];
                $view->vars['upparam'] = $options['info']['name'].",".$type;
                $view->vars['url'] = $this->get('core.common')->U('attachment/upload', $urlParam);
                if(isset($options['data'])&&$options['data'])
                {
                    $view->vars['value'] = array();
                    $view->vars['value'][0]['href'] = $options['data'];
                    $view->vars['value'][0]['source'] = $options['data'];
                    $view->vars['value'][0]['desc'] = "";
                    $view->vars['value'][0]['link'] = "";
                }

                $view->vars['display'] = "style=display:none;";
                break;
            default:
                break;
        }
    }

    /**
     * 处理规则
     * 注：本方法中针对 $obj不要使用原生 method_exists,要使用 parent::method_exists
     * @param array $form     表单配置参数
     * @param unknown $obj    写表对象数据
     * @throws \InvalidArgumentException
     */
    public function handleRule()
    {
        $form = func_get_arg(0);
        $obj = func_get_arg(1);

        $typeAttr = array('text', 'image', 'video');
        $attr = isset($form['attr'])&&$form['attr']?$this->get('core.common')->getQueryParam($form['attr']):array();

        if(!$attr)
            throw new LogicException('未定义attr参数');

        if(!isset($attr['type'])||empty($attr['type']))
            throw new LogicException('未定义type参数');

        if(!in_array($attr['type'], $typeAttr, true))
            throw new LogicException('type参数为text|image|video');

        if(!isset($attr['property'])||empty($attr['property']))
            throw new LogicException('未定义property参数');

        $type = $attr['type'];
        $limit = isset($attr['limit'])?(int)$attr['limit']:0;
        $getProperty = 'get'.$this->get('core.common')->ucWords($attr['property']);
        $setName = 'set'.$this->get('core.common')->ucWords($form['name']);
        //$getName = 'get'.$this->get('core.common')->ucWords($form['name']);

        if(parent::method_exists($obj, $getProperty))
        {
            $info = $obj->{$getProperty}();
            $nameValue = $this->get('request')->get($form['name'], '');//$obj->{$getName}();

            if($nameValue)
            {
                $obj->{$setName}($nameValue);
                return $obj;
            }

            switch($type)
            {
                case 'video':
                case 'image':
                    if(is_null(json_decode($info)))
                    {
                        $info = explode(',',$info);
                        $obj->{$setName}(isset($info[$limit])?ltrim($info[$limit], '/'):'');
                    }else{
                        $info = json_decode($info, true);
                        $obj->{$setName}(isset($info[$limit]['href'])?ltrim($info[$limit]['href'],'/'):'');
                    }
                    break;
                case 'text':
                    $info = $this->get('core.common')->htmlClear($info);
                    $obj->{$setName}($limit>0?mb_substr($info, 0, $limit ,'utf-8'):$info);
                    break;
            }
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'info' => array()
            ,'compound' => false
        ));
    }

    public function getName()
    {
        return 'abstracttext';
    }
}