<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年02月08日
*/
namespace HouseBundle\Controller;

/**
* 水印设置
* @author house
*/
class WebwatermarkController extends Controller
{
	/**
    * 实现的show方法
    * admina
    */
    public function showAction()
    {
        $map = array();
        $map['id'] = $this->get('request')->get('id');
        $info = $map['id']?$this->get('db.watermark')->findOneBy($map):array();
        $form = $this->getFormFieldAttr('webwatermarkshow', $map, 'POST', $info, 'save');
        $this->parameters['form'] = $form->createView();
        return $this->render($this->getBundleName(), $this->parameters);
    }
            
    /**
    * 实现的save方法
    * admina
    */
    public function saveAction()
    {
        if($this->get('request')->getMethod() == "POST")
        {
            $id = $this->get('request')->get('id', '');
        
            if($id)
                $this->get('db.watermark')->update($id, $_POST);
            else
                $this->get('db.watermark')->add($_POST);
        
            return $this->success('操作成功');
        }
        
        return $this->error('操作失败');
    }

    /**
    * 实现的delete方法
    * admina
    */
    public function deleteAction()
    {
        $id = $this->get('request')->get('id', 0);
        $this->get('db.watermark')->delete($id);
        return $this->success('操作成功');
    }
}