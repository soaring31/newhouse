<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年5月23日
*/
namespace CoreBundle\Form\Type;

use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Exception\LogicException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EntityType extends BaseType
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
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if(isset($options['attr']['multiple']))
            $view->vars['full_name'] .= '[]';
        if(isset($options['attr']['type'])&&$options['attr']['type']=='checkbox')
            $view->vars['full_name'] .= '[]';

        $type = isset($options['attr']['type'])?$options['attr']['type']:'';

        if(!isset($options['attr']['style']))
        {
            if(isset($options['attr']['multiple']))
                $options['attr']['style'] = "height:100px !important;";
            // else
            //     $options['attr']['style'] = "height:25px !important;";
        }

        unset($options['attr']['type']);


        $view->vars['type'] = $type;
        $view->vars['attr'] = $options['attr'];
        $view->vars['required'] = isset($options['info']['required'])?(bool)$options['info']['required']:false;
        $view->vars['choices'] = $this->_entityAttr($options['info']);
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
        return 'entity';
    }

    /**
     * entity
     * @param object $vo
     * @return multitype:|multitype:mixed unknown
     */
    private function _entityAttr($vo)
    {
        $choices = array();
        $field = "";

        if(!is_array($vo))
            return $choices;

        //关联字段
        $property = $vo['property'];

        //模型服务名称
        $serviceName = $vo['entitypath'];

        //查询条件
        $query = $this->get('core.common')->getQueryParam($vo['query_builder']);

        if(empty($serviceName))
            return array();

        if($property)
            $property = explode(',', $property);

        $order = array();
        $order['sort'] = 'asc';

        if(isset($query['order'])&&$query['order'])
            $order[$query['order']] = isset($query['orderBy'])?$query['orderBy']:'';

        unset($query['order']);
        unset($query['orderBy']);

        //findType=0为对象结果集，1为数组结果集
        $query['findType'] = 1;
        $query['checked'] = isset($query['checked'])?$query['checked']:1;

        foreach($query as $k=>$v)
        {
            if(is_numeric($v))
                continue;

            if($v=="")
                unset($query[$k]);
        }

        $result = $this->get($serviceName)->findBy($query, $order);

        if(isset($result['data']))
        {
            $field = "id";
            if(count($property)>1)
                $field = array_shift($property);

            foreach($result['data'] as $item)
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
        return $choices;
    }
}