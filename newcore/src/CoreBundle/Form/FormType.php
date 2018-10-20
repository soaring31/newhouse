<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年1月28日
*/
namespace CoreBundle\Form;

use CoreBundle\Form\Type\BaseType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FormType extends BaseType
{
    //容器
    protected $container;

    //前缀
    protected $prefix = "";

    protected $formId = 0;

    //预览模式
    protected $preview = false;

    protected $limitType = array('integer', 'email', 'file', 'textarea', 'text', 'password');
    protected $limitType1 = array('entity', 'integer', 'file', 'textarea', 'text', 'password');

    public function __construct(ContainerInterface $container, $formId, $prefix='', $preview=false)
    {
        $this->prefix = $prefix;
        $this->formId = $formId;
        $this->preview = $preview;
        $this->container = $container;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Add listeners
        $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPreSetData'));
    }

    public function onPreSetData(FormEvent $event)
    {
        //adding the name field if needed
        $info = $event->getData();
        $form = $event->getForm();

        $this->_bindform($form, $info, $this->formId);
    }
    
    // $info 为数组，非对象
    private function _bindform($form, $info, $formId)
    {
        $map = array();
        $map['_multi'] = false;
        $map['status'] = 1;
        $map['findType'] = 1;

        $order = array();
        $order['sort'] = 'asc';

        $map['model_form_id']['in'] = array($formId);

        //获取表单数据
        $modelForm = self::getFormInfo($formId);

        if(empty($modelForm))
            return false;

        //继承表单id
        if($modelForm['parent_form'])
            $map['model_form_id']['in'] = array_merge(array($formId),explode(',', $modelForm['parent_form']));

        //获得模型表单属性
        $map['bundle']['orX'][]['bundle']['like'] = "%".$this->container->get('core.common')->getThemeName()."%";
        $map['bundle']['orX'][]['bundle'] = "";

        $modelFormAttr = $this->get('db.model_form_attr')->findBy($map, $order);

        $mAttr = array();

        //取继承表单字段
        foreach($modelFormAttr['data'] as $item)
        {
            if($item['model_form_id']!=$formId)
                continue;

            $mAttr[$item['name']] = $item;
        }

        //过滤表单字段(非继承)
        foreach($modelFormAttr['data'] as $key=>$item)
        {
            if($item['model_form_id']!=$formId&&isset($mAttr[$item['name']]))
                unset($modelFormAttr['data'][$key]);
            
            //开关标识
            if(isset($item['validate_rule'])&&$item['validate_rule'])
            {
                $validateRule = explode('|', $item['validate_rule']);

                if(count($validateRule)<2)
                    continue;
                
                $mconfig = $this->get('db.mconfig')->getData(array('ename'=>$validateRule[1]));
                
                if(!isset($mconfig[$validateRule[0]]['value']))
                    continue;
                
                $validateValue = (bool)$mconfig[$validateRule[0]]['value'];
                
                if($validateValue)
                    continue;
                
                unset($modelFormAttr['data'][$key]);
            }
        }

        unset($map);
        unset($item);
        unset($order);
        unset($mAttr);

        if(!isset($modelFormAttr['data']))
            return false;

        //初始化分组校验参数
        $groupRule = array();
        $initcondition = $modelForm['initcondition'];
        $initcondition = $initcondition?$this->get('core.common')->getQueryArray($initcondition):array(); 

        if(isset($initcondition['data-groupfield']))
        {
            foreach(array_keys($initcondition) as $kinit)
            {
                if (preg_match('/data-formgroup/', $kinit))
                    $groupRule[end($initcondition['data-groupfield'])][$kinit] = end($initcondition[$kinit]);
            }
        }

        self::_createform($form, $info, $modelFormAttr['data'], $groupRule);

        unset($groupRule);
        unset($modelFormAttr);

        //处理表单关联
        $bindFormId = (int)$modelForm['bindform']>0&&(int)$modelForm['bindform']!=(int)$modelForm['id']?(int)$modelForm['bindform']:0;
        $bindfield = $modelForm['bindfield'];

        if($bindFormId>0)
        {
            $modelForm = self::getFormInfo($bindFormId);

            if(empty($modelForm))
                return false;

            if((int)$modelForm['model_id']<=0)
                return false;

            $models = $this->get('db.models')->getData((int)$modelForm['model_id']);

            if(empty($models))
                return false;

            $id = (int)$this->get('request')->get('id', 0);

            //formType=0横表，1纵表
            if((int)$modelForm['type']==1)
            {
                //已将属性表的字段信息全部合并 
            }else {
                $info1 = $this->get($models['service'])->findOneBy(array($bindfield=>$id, 'findType'=>1), array(), false);
                $info = array_merge($info, $info1);                
            }
            
            //属性表的字段会覆盖主表的同名字段
            self::_bindform($form, $info, $bindFormId);
        }
    }

    /**
     * 生成表单字段属性
     * @param object $form
     * @param array $info
     * @param array $data
     */
    private function _createform($form, $info=array(), array $data, array $groupRule=array())
    {
        //遍历字段
        foreach($data as $v)
        {
            if($form->has($v['name'])) continue;

            $rule = $v['type']?$v['type']:'text';

            $flag = $this->preview ?: false;

            /**
             * 验证时间
             * 3 始终, 2 编辑, 1 新增
             */
            switch((int)$v['validate_time'])
            {
                case 1:
                    if(empty($info))
                        $flag = true;
                    break;
                case 2:
                    if(!empty($info))
                        $flag = true;
                    break;
                default:
                    $flag = true;
                    break;
            }
            if(!$flag) continue;
                
            $_options = array();

            //添加默认样式
            if($rule!='submit'&&$rule!='button'&&!isset($_options['attr']['data-compound']))
                $_options['label_attr'] = array('class'=>'w150 rtd txtright');

            switch($rule)
            {
                case 'integer':
                    $_options['required'] = (bool)$v['required'];
                    $_options['data'] = (int)$this->get('core.common')->methodExists($info, $v['name'], $v['value']);
                    break;
                case 'hidden':
                case 'textarea':
                case 'email':
                case 'text':
                case 'choice':
                case 'password':
                    $_options['required'] = $rule!='hidden'?(bool)$v['required']:false;
                    $_options['data'] = $rule!='password'?$this->get('core.common')->methodExists($info, $v['name'], $v['value']):'';

                    if($rule=="choice")
                        $_options['choices'] = $this->get('core.common')->getQueryParam($v['choices']);

                    break;
                case 'image':
                case 'file':
                case 'abstracttext':
                case 'ueditor':
                    $_options['required'] = (bool)$v['required'];
                    $_options['info'] = $v;
                    $_options['data'] = $this->get('core.common')->methodExists($info, $v['name'], $v['value']);
                    break;
                case 'submit':
                case 'button':
                    break;
                case 'date':

                    $vle = $this->get('core.common')->methodExists($info, $v['name'], $v['value']);
                    
                    if($vle instanceof \DateTime)
                    {
                        $_options['data'] = $vle;
                    }else{

                        $vle = is_numeric($vle)?$vle:(isset($vle['timestamp'])?$vle['timestamp']:time());
    
                        $vle = date('Y-m-d',$vle);
                        $_options['data'] = new \DateTime($vle);
                    }
                    
                    break;
                case 'datetime':
                    $vle = $this->get('core.common')->methodExists($info, $v['name'], $v['value']);
                    if($vle instanceof \DateTime)
                        $_options['data'] = $vle;
                    else
                        $_options['data'] = new \DateTime(date('Y-m-d H:i:s',isset($vle['timestamp'])?$vle['timestamp']:($vle?$vle:time())));
                    break;
                case 'idatess':
                    $rule = 'integer';
                    $vle = $this->get('core.common')->methodExists($info, $v['name'], $v['value']);
                    $_options['data'] = $vle?strtotime($vle):0;
                    break;
                case 'ajaxbind':
                    $_options['intention'] = $info; 
                    
                case 'checkbox':
                    $_options['required'] = (bool)$v['required'];
                default:
                    $_options['info'] = $v;
                    $_options['data'] = $this->get('core.common')->methodExists($info, $v['name'], $v['value']);
                    break;
            }

            $_options['label'] = $v['label'];
            $_options['attr'] = $this->get('core.common')->getQueryParam($v['attr']);

            $limitType2 = array('entity', 'ymlfile', 'push', 'bundle', 'ajaxbind', 'choice');

            if(in_array($rule, $limitType2, true))
            {
                $_options['placeholder'] = isset($_options['attr']['placeholder'])?$_options['attr']['placeholder']:'';
                unset($_options['attr']['placeholder']);
            }

            if((empty($rule)||in_array($rule, $this->limitType, true)&&!isset($_options['attr']['data-compound'])))
            {
                //嵌入正则检测
                //$_options['attr']['pattern'] = '\d{6}-\d{4}';
                //$_options['attr']['rev'] = $v['label'];
                //$_options['attr']['rule'] = isset($_options['attr']['rule'])?$_options['attr']['rule']:$rule;
                //$_options['attr']['minlength'] = isset($_options['attr']['minlength'])?(int)$_options['attr']['minlength']:1;
                // $_options['attr']['maxlength'] = isset($_options['attr']['maxlength'])?(int)$_options['attr']['maxlength']:200;
                //$_options['attr']['init'] = isset($_options['attr']['init'])?$_options['attr']['init']:("请输".$_options['attr']['minlength']."到".$_options['attr']['maxlength']."字符");
            }

            //开启表单分组
            if(isset($_options['attr']['data-compound']))
                $_options['compound'] = true;

            if(isset($_options['attr']['label_attr']))
                $_options['label_attr'] = array('class'=>$_options['attr']['label_attr']);

            if(isset($_options['attr']['label-attr']))
                $_options['label_attr'] = array('class'=>$_options['attr']['label-attr']);

            unset($_options['attr']['label_attr']);
            unset($_options['attr']['label-attr']);
            
            if(isset($_options['label_attr']))
                $_options['label_attr']['base-data'] = $info;
            
            //嵌入模版(供JS使用)
            if(isset($groupRule[$v['name']]))
                $_options['attr'] = array_merge($_options['attr'], $groupRule[$v['name']]);

            //如果开启预览模式
            if($this->preview)
            {
                //获取表单数据
                $modelForm = self::getFormInfo($v['model_form_id']);
                if($modelForm)
                    $_options['attr']['data-preview'] = $modelForm['name'].($modelForm['title']?("|".$modelForm['title']):"");
                else
                    $_options['attr']['data-preview'] = "";
            }


            $form->add($v['name'], ($rule?$rule:null), $_options);
        }
    }
    
    public function getFormInfo($modelId)
    {
        return $this->get('db.model_form')->getData($modelId);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->prefix;
    }
}