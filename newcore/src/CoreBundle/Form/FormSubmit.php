<?php
/**
 * @copyright Copyright (c) 2012 – 2020 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2017年10月28日
 */
namespace CoreBundle\Form;

use CoreBundle\Services\ServiceBase;
use CoreBundle\Form\SubFormSubmit;
use CoreBundle\Functions\FormEntity;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * 表单提交端的服务类，表单显示端不在本类范围
 * 基于传入的数据$data，而非 $request
 * 用于解决关联多表单中的不同子表单之间的互通
 */
class FormSubmit extends ServiceBase
{
    /**
     * 所有表单处理器
     * @var array(SubFormSubmit)
     */
    protected $forms;
    
    /**
     * 虚拟多表单entity，用于跨多表单提交的处理需求
     * @var FormEntity
     */
    protected $formEntity;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    // 包含初始化表单，及提交
    //做到可以切换表单，也可以模拟数据进行多次提交。
    public function defaultSubmit($id, $data, $entity, $service)
    {
        $_form_id = self::_findDefaultEntryForm();

        foreach($data as $k => $v) {//??
            $this->get('request')->request->set($k, $v);
        }
        
        self::configForm($_form_id,$service);
        
        $entity = self::submit($id, $data, $entity);
        
        return $entity;
    }
    
    //外部切换入口表单
    public function configForm($formId = 0,$service = null)
    {
        if(!(int)$formId) throw new \LogicException('请配置主表单id');
        self::_attachOneForm($formId, false, $service);
    }
    

    // 已初始化入口表单之后的真实提交，与configForm配合使用，外部使用入口
    public function submit($id, $data, $entity)
    {
        if(!is_object($this->forms[0]) || !$this->forms[0]->getFormId()) {
            throw new \LogicException('提交数据前请初始化表单!');
        }
        
        self::assignData($id, $data, $entity);
    
        //表单验证
        self::_validate();
         
        //数据入库
        $entity = self::_persist();
    
        return $entity;
    }

  
    /**
     * 找到入口表单
     */
    protected function _findDefaultEntryForm()
    {
        $_form_id = (int)$this->get('request')->get('_form_id',0);
    
        //使用当前控制器的 show 方法的模板所绑定的表单
        if(0 == $_form_id) {
    
            $template = $this->get('cc')->getBundleName().':'.ucfirst($this->get('cc')->getControllerName()).':show.html.twig';
            $viewsInfo = $this->get('db.views')->findOneBy(array('name'=>$template), array(), false);
            if((int)$viewsInfo->getUseform()) {
                $_form_id = (int)$viewsInfo->getUseform();
                $this->get('request')->request->set('_form_id', $_form_id);//??//!!
                $this->get('request')->request->set('_is_appointed', $this->get('request')->get('_is_appointed',1));////是否指定验证
            }
        }
        
        return $_form_id;
    }


    protected function assignData($id = 0, array $data = array(), $entity = null)
    {
        foreach($this->forms as $_form) {
            if(is_object($_form) && $_form->getFormId()) {
                $_form->assignData($id, $data, $entity);
            }
        }
    }

    
    /**
     * 进行表单验证，得到经过验证的完整的formEntity
     */
    protected function _validate()
    {
        self::_assembleFormEntity();

        self::_validCsrfToken();
      
        self::_doValidate();

        self::_feedBackEntity();//将数据返回给各自子表单的entity

    }


    // 组装一个包含主副表单的entity，用于字段验证与处理(如：主表thumb，需要从副表content，images中得出)。
    // 所以不管是导入formEntity，还是导出，都要按一个规则，表单字段是谁的，数据就属于谁的，其它的使用主模型的。
    protected function _assembleFormEntity()//assemble
    {
        $this->formEntity = new FormEntity();

        foreach($this->forms as $_form) {
            if(!is_object($_form) || !$_form->getFormId()) continue;
            $_form->assembleFormEntity($this->formEntity);
        }     
        return true;
    }

    /**
     * csrf_token 校验
     *
     * @return boolean
     * @throws InvalidArgumentException
     */
    protected function _validCsrfToken()
    {
        $csrf_token = $this->get('request')->get('csrf_token','');
    
        //$isNotoken =  $this->get('request')->get('_stoken','');
        $checkToken =  $this->get('request')->get('check_csrf', true);
        if($checkToken== 'false')
            return;

        $isNotoken = false;

        if(!$isNotoken&!$this->get('form.csrf_provider')->isCsrfTokenValid($this->get('cc')->getIntention(), $csrf_token)) {
            throw new \InvalidArgumentException('CSRF token 已失效,请重新刷新页面');
        }
    }
    
    //在这个过程中，formEntity 会写入一些与 表单字段及data不对应的新值
    protected function _doValidate()
    {
        foreach($this->forms as $_form) {
            if(is_object($_form) && $_form->getFormId()) {
                $_form->doValidate($this->formEntity);
            }
        }
    }
    //将验证更新后的数据返回给每个表单的entity
    //这个一定要在两个表单的验证都完成之后执行
    // 所以不管是导入formEntity，还是导出，都要按一个规则，表单字段是谁的，数据就属于谁的，其它的使用主模型的。
    // 表单字段中不存在的两表共有字段，该如何分配，只给主表，还是主副一起设置??
    // 跳过在另一方表单字段中有的字段。
    protected function _feedBackEntity()
    {
        foreach($this->forms as $k => $_form) {
            if(is_object($_form) && $_form->getFormId()) {
                $keepAttrKeys = self::_getKeepAttrKeys($k);
                $_form->feedBackEntity($this->formEntity, $keepAttrKeys);
            }
        }
    }


    //持久化到库
    // 新增，需要主表选入库，得到id之后，才可以进行副表的入库，就是说，验证可以是
    protected function _persist()
    {
        $previousEntity = null;
    
        foreach($this->forms as $_form) {
            if(is_object($_form) && $_form->getFormId()) {
                $_form->doPersist($previousEntity);
                $previousEntity = $_form->getEntity();//关联关系是递进的，不总是主表单的id
            }
        }
    
        // 只返回主表单的entity,写库已经完成，返回结果只是为了后续引用，不会改变写库结果。
        $entity = $this->forms[0]->getEntity();
        if($entity instanceof FormEntity) {//主表为纵号时，只返回boolean值
            return true;
        }
        return $entity;
    }
    

    protected function _attachOneForm($formId = 0, $isBind = false, $service = null)
    {
        if(!(int)$formId) throw new \LogicException('请配置表单id');
    
        $this->forms[] = $_form = new SubFormSubmit((int)$formId, $isBind, $service, $this->container);
    
        if($formId = $_form->getModelForm()->getBindForm()) {
            self::_attachOneForm($formId, true);
        }
    }

    //取得排除某个子表单的所有表单字段
    protected function _getKeepAttrKeys($index = 0)
    {
        $keys = array();
        foreach($this->forms as $k => $_form) {
            if($k == $index) continue;
            if(is_object($_form) && $_form->getFormId()) {
                foreach($_form->getFormAttrs() as $kk => $vv) {
                    $keys[$kk] = $kk;
                }
            }
        }
        return $keys;
    }
    


}