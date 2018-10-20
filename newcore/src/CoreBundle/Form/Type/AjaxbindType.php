<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年2月29日
*/
namespace CoreBundle\Form\Type;

use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Exception\LogicException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AjaxbindType extends BaseType
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

        if(!isset($options['info']['entitypath'])||empty($options['info']['entitypath']))
            throw new LogicException('entity服务 必须设置');

        if(!isset($options['info']['property'])||empty($options['info']['property']))
            throw new LogicException('entity关联字段 必须定义');
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        //关联字段
        $property = $this->get('core.common')->ucWords($options['info']['property']);

        $attr = $this->get("core.common")->getQueryParam($options['info']['attr']);

        $value = isset($view->vars['value'])?$view->vars['value']:"";

        $data = isset($options['intention'])?$options['intention']:array();


        //模型服务名称
        $service_name = $options['info']['entitypath'];

        //查询条件
        $query_builder = $this->get('core.common')->getQueryParam($options['info']['query_builder']);

        $valuetext = "";

        if($value)
        {
            //获取结果
            $sinfo = $this->get($service_name)->findOneBy(array('id'=>(int)$value), array(), false);

            if(isset($attr['src-service'])&&$attr['src-service']&&isset($attr['src-field'])&&$attr['src-field'])
            {
                $srcInfo = $this->get($attr['src-service'])->findBy(array($attr['src-field']=>(int)$value));

                
            }

            $valuetext = method_exists($sinfo, "get".$property)?$sinfo->{"get{$property}"}():'';

            if(isset($attr['src-name']))
            {
                $srcName = lcfirst($this->get('core.common')->ucWords($attr['src-name']));

                if(isset($view->parent->vars['value'][$srcName])&&isset($srcInfo['data']))
                {
                    foreach($srcInfo['data'] as $item)
                    {
                        if($item->getId()==$view->parent->vars['value'][$srcName])
                            $valuetext .= " ".$item->getName();
                    }
                }
            }
        }
        //是否必填
        $view->vars['required'] = (bool)$options['info']['required'];

        // unset($view->vars['attr']['src-service']);
        // unset($view->vars['attr']['src-field']);

        $order = array();

        if(is_array($query_builder))
        {
            if(empty($query_builder))
                $query_builder['pid'] = 0;

            if(isset($query_builder['order'])&&$query_builder['order'])
                $order[$query_builder['order']] = isset($query_builder['orderBy'])?$query_builder['orderBy']:'asc';

            unset($query_builder['order']);
            unset($query_builder['orderBy']);
        }

        //findType=0为对象结果集，1为数组结果集
        $query_builder['findType'] = 1;
        //获取结果
        $info = $this->get($service_name)->findBy($query_builder, $order, 100);

        $choices = array();
        
        if(isset($info['data']))
        {
            $field = "id";

            $property = isset($options['info']['property'])&&$options['info']['property']?explode(',', $options['info']['property']):$options['info']['property'];

            if(count($property)>1)
                $field = array_shift($property);

            foreach($info['data'] as $item)
            {
                //主键为ID或第一个设定值
                $id = isset($item[$field])?$item[$field]:$item['id'];

                //匹配是存有关链字段
                if(is_array($property)&&$property)
                {
                    $_poo = "";
                    foreach($property as $poo)
                    {
                        if(isset($item[$poo]))
                            $_poo .= "|".$item[$poo];
                    }

                    $choices[$id] = trim($_poo, "|");
                }else{
                    array_shift($item);
                    $choices[$id] = array_shift($item);
                }
            }
        }

        $field = isset($attr['src-name'])?$attr['src-name']:'';
        if (!isset($data[$field]))
            $field = lcfirst($this->get('core.common')->ucWords(isset($attr['src-name'])?$attr['src-name']:''));
        $ajaxParam = isset($attr['src-service'])?$attr['src-service']:$service_name;
        $ajaxParam .= "|".time();
        $ajaxParam .= isset($attr['src-field'])?"|".$attr['src-field']:'';
        $view->vars['srcinfo'] = isset($srcInfo['data'])?$srcInfo['data']:array();
        $view->vars['srcname'] = $view->vars['name'];
        $view->vars['secname'] = isset($attr['src-name'])?$attr['src-name']:'';
        $view->vars['srcvalue'] = isset($data[$field ])?$data[$field ]:"";
        $view->vars['srcservice'] = isset($attr['src-service'])?$attr['src-service']:'';
        $view->vars['valuetext'] = $valuetext;
        $view->vars['attr']['data-ajaxurl']  = $this->get('core.common')->U(isset($attr['data-url'])?$attr['data-url']:"getjson");
        $view->vars['attr']['data-ajaxparam']  = $this->get('core.common')->encode($ajaxParam);
        $view->vars['choices'] = $choices;//isset($info['data'])?$info['data']:array();
        $view->vars['entitypath'] = $service_name;
        $view->vars['query_builder'] = $options['info']['query_builder'];
    }

    //注：本方法中针对 $obj不要使用原生 method_exists,要使用 parent::method_exists
    public function handleRule()
    {
        $obj = func_get_arg(1);
        $form = func_get_arg(0);
        $formRequest = func_get_arg(2);

        $attr = $this->get('core.common')->getQueryParam($form['attr']);

        if(isset($attr['src-service']) and $attr['src-service'])
        {
            $srcName = isset($attr['src-name'])?$attr['src-name']:'';
            $field = $this->get('core.common')->ucWords($srcName);

            if(is_object($obj)&& parent::method_exists($obj, "set{$field}"))
                $obj->{"set".$field}($this->get('request')->get($srcName,''));
        }

        $nameVal = isset($formRequest[$form['name']])?$formRequest[$form['name']]:'';
        
        $action = "set".$this->get('core.common')->ucWords($form['name']);

        if(is_array($nameVal))
            $nameVal = implode(',', $nameVal);

        if(parent::method_exists($obj, $action))
            call_user_func_array(array($obj, $action),array($nameVal));
        elseif(is_array($obj))
            $obj[$form['name']] = $nameVal;

        if(is_array($obj))
            return $obj;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'info' => array()
            ,'empty_value'=>''
            ,'compound' => false
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'choice';
    }

    public function getName()
    {
        return 'ajaxbind';
    }
}