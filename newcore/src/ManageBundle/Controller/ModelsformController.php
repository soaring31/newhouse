<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年1月18日
*/
namespace ManageBundle\Controller;

/**
 * 表单管理
 *
 */
class ModelsformController extends Controller
{
    public function showAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }
    
    /**
     * 保存
     */
    public function saveAction()
    {
        if($this->get('request')->getMethod() == "POST")
        {
            $id = (int)$this->get('request')->get('id');
        
            if($id>0)
                $this->get('db.model_form')->update($id, $_POST);
            else
                $this->get('db.model_form')->add($_POST);
        
            return $this->success('操作成功');
        }
        
        return $this->error('操作失败');
    }
    
    public function deleteAction()
    {
        $id = (int)$this->get('request')->get('id');

        $this->get('db.model_form')->delete($id);
        
        return $this->success('操作成功');
    }

    /**
    * 预览
    * admina
    */
    public function previewAction()
    {
        //组装表单数据
        $initInfo = array();
        $formid = $this->get('request')->get('_id', 0);
        
        $formInfo = $this->get('db.model_form')->getData($formid);

        if($formInfo)
            parent::_formType($formInfo, $initInfo, true);
        
        $this->parameters['form'] = isset($initInfo['form'])?$initInfo['form']:'';

        return $this->render($this->getBundleName(), $this->parameters);
    }
    
    /**
     * 生成表单文件
     */
    public function createfileAction()
    {
        $id = (int)$this->get('request')->get('_id', 0);

        $this->get('db.model_form')->createfile($id);

        return parent::success('表单文件生成成功');
    }
    
    /**
     * 处理上级表单信息
     */
    private function _handleParentForm($parentForm, $data)
    {
        if(empty($parentForm))
            return false;

        foreach($parentForm as $formId)
        {
            $formInfo = $this->get('db.model_form')->findOneBy(array('id'=>$formId));
            $parentForm = is_object($formInfo)&&$formInfo->getParentForm()?explode(',',$formInfo->getParentForm()):array();
        }
    }
}