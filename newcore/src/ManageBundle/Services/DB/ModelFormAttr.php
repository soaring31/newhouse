<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年1月18日
*/
namespace ManageBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ModelFormAttr extends AbstractServiceManager
{
    protected  $table = 'ModelFormAttr';

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
    }
    
    public function add(array $data,  $info=null, $isValid=true)
    {
        $name = $this->get('request')->get('name', '');
        $attr = $this->get('request')->get('attr', '');
        $choices = $this->get('request')->get('choices', '');
        $model_form_id = $this->get('request')->get('model_form_id', 0);
        
        //把空格、换行符、英文分号、中文分号、中文逗号等替换成英文逗号的正则表达式(\n)|(\\s)|(\t)|
        if($attr)
            $data['attr'] = preg_replace("/(，)|(;)|(；)/" ,',' , $attr);
        
        if($choices)
            $data['choices'] = preg_replace("/(，)|(;)|(；)/" ,',' , $choices);

        //判断字段是否已存在
        $map = array();
        $map['name'] = $name;
        $map['model_form_id'] = is_array($model_form_id)?implode(',', $model_form_id):$model_form_id;
        
        $count=$this->count($map);
    
        if($count>0)
            throw new \InvalidArgumentException(sprintf('[%s]字段已存在', $name));

        return parent::add($data, $info, $isValid);
    }
    
    public function update($id, array $data, $info=null, $isValid=true)
    {
        $name = $this->get('request')->get('name', '');
        $model_form_id = $this->get('request')->get('model_form_id', 0);
        $attr = $this->get('request')->get('attr', '');
        $choices = $this->get('request')->get('choices', '');
        
        //把空格、换行符、英文分号、中文分号、中文逗号等替换成英文逗号的正则表达式(\n)|(\\s)|(\t)|
        if($attr)
            $data['attr'] = preg_replace("/(，)|(;)|(；)/" ,',' , $attr);

        if($choices)
            $data['choices'] = preg_replace("/(，)|(;)|(；)/" ,',' , $choices);

        if($name)
        {
            //判断字段是否已存在
            $map = array();
            $map['name'] = $name;
            $map['model_form_id'] = is_array($model_form_id)?implode(',', $model_form_id):$model_form_id;
            $map['id']['neq'] = $id;
    
            $count = $this->count($map);

            if($count>0)
                throw new \InvalidArgumentException(sprintf('[%s]字段已存在', $name));
        }
        return parent::update($id, $data, $info);
    }
    
    
    /**
     * 根据表单id，取得表单字段，包含所有继承表单的字段
     * @param integer $formid 表单字段
     * @param array $params 其它附加查询条件
     * @return array 表单字段的数据(非对象)数组，数组结果集,以 name 为健值
     */
    public function findByFormid($formId, array $params = array())
    {
        if(!($formId = (int)$formId) || $formId < 0) {
            return false;
        }
        
        $modelForm = $this->get('db.model_form')->findOneBy(array('id' => $formId), array(), false);
        if(!is_object($modelForm))
            return false;
         
        $parentForm = $modelForm->getParentForm() ? explode(',',$modelForm->getParentForm()) : array();
        $parentForm[] = $formId;
         
        $map = array();
        $map['status'] = 1;
        $map['findType'] = 1;//获得查询结果集,true为数组结果集，false为对象结果集
        $map['_multi'] = false;
        $map['model_form_id']['in'] = $parentForm;
        $map += $params;

        $modelFormAttr = $this->findBy($map);
         
        if(!isset($modelFormAttr['data']) || empty($modelFormAttr['data'])) {
            return false;
        }
         
        $_modelFormAttr = array();
         
        foreach($modelFormAttr['data'] as $v) {
            $_modelFormAttr[$v['name']] = $v;
        }
    
        return $_modelFormAttr;
    }    
    
}