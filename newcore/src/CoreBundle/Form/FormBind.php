<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年1月28日
*/
namespace CoreBundle\Form;

use CoreBundle\Services\ServiceBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FormBind extends ServiceBase
{
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * 获取表单字段属性
     * @param string $name      表单名称
     * @param array  $map       表单提交参数(如id>0为编辑，id=0的为新增)
     * @param string $method    表单提交方法{POST,GET,PUT,DELETE}
     * @param array  $info      表单值(编辑时用)
     * @param string $action    表单的ACTION地址
     * @param string $prefix    前缀
     * @param string $preview   预览模式
     */
    public function getForm($name, array $map, $method='POST', $info=null, $action='save', $prefix='', $preview=false)
    {
        $dirPath = "";

        //获得表单信息
        $modelForm = is_object($name)?$name:$this->get('db.model_form')->getData($name,'name');

        if(empty($modelForm))
            throw new \InvalidArgumentException('表单配置不存在或已被删除');
        
        $info = is_object($info)?self::handleObject($info):$info;

        //获取模型数据
        $models = $this->get('db.models')->getData(isset($modelForm['model_id'])?(int)$modelForm['model_id']:0);

        if($models&&isset($models['bundle']))
        {
            //获取模型所属Bundle信息
            $bundleInfo = $this->get('core.common')->getBundle($models['bundle']);
    
            //命名空间名称
            $dirPath = $bundleInfo->getNamespace()."\\Forms\\".ucfirst($modelForm['name']).'Type';
        }

        //判断类是否存在,存在则用文件表单，不存在则用数据表单
        if (class_exists($dirPath))
            $formType = new $dirPath();
        else
            $formType = new FormType($this->container, $modelForm['id'] ,$prefix, $preview);

        $map['_form_id'] = $modelForm['id'];

        if(isset($modelForm['url'])&&$modelForm['url'])
            $action = $modelForm['url'];

        if(isset($map['id'])&&is_numeric($map['id'])&&$map['id']<=0)
            unset($map['id']);

        if(isset($map['initid'])&&$map['initid']<=0)
            unset($map['initid']);

        if(isset($map['_copy']))
            unset($map['id']);

        //生成表单数据
        $form = $this->createForm($formType, $info, array(
            'action' => $this->get('core.common')->U($action, $map),
            'method' => $method,
            'intention' =>$this->get('core.common')->getIntention(),
            'csrf_message'=>'CSRF token 已失效.'
        ))
        ;

        unset($map);
        unset($info);
        unset($modelForm);

        return $form;
    }

        
    private function handleObject($info)
    {
        return $this->get('core.common')->handleObject($info);
    }

    public function createForm($type, $data = null, array $options = array())
    {
        return $this->get('form.factory')->create($type, $data, $options);
    }
}