<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年11月17日
*/
namespace ManageBundle\Controller;

/**
* 
* @author admina
*/
class TrashController extends Controller
{
    
    /**
     * 实现的undelete方法
     * admina
     */
    public function undeleteAction()
    {
        $id = $this->get('request')->get('id', 0);
        $sid = $this->get('request')->get('sid',0);
        
        if($id && $sid)
        {
            $map = array();
            $map['id'] = $sid;
            $serviceName = $this->get("db.models")->findOneBy($map)->getServiceName();
            
            if(empty($serviceName))
                return $this->error('模型不存在或已被恢复');

            $this->get($serviceName)->undelete($id);
            return $this->success('恢复成功');
        }
        
        return $this->error('恢复失败');
    }

    /**
    * 实现的delete方法
    * admina
    */
    public function deleteAction()
    {
        $id = $this->get('request')->get('id', 0);
        $sid = $this->get('request')->get('sid',0);
        
        if($id && $sid)
        {
            $map = array();
            $map['id'] = $sid;
            $serviceName = $this->get("db.models")->findOneBy($map)->getServiceName();
            
            if(empty($serviceName))
                return $this->error('模型不存在或已被删除');

            $this->get($serviceName)->realDelete($id);
            return $this->success('删除成功');
        }
        
        return $this->error('删除失败');
    }
    
    /**
     * 实现的deleteall方法
     * admina
     */
    public function deleteallAction()
    {
        $sid = $this->get('request')->get('sid',0);
        
        if($sid)
        {
            $map = array();
            $map['id'] = $sid;
            $serviceName = $this->get("db.models")->findOneBy($map)->getServiceName();
        
            if(empty($serviceName))
                return $this->error('模型不存在或已被删除');
            
            $result = $this->get($serviceName)->getTrash();

            foreach ($result['data'] as $re){
                $this->get($serviceName)->realDelete($re->getId(),$re);
            }
            
            return $this->success('删除成功');
        }
        
        return $this->error('删除失败');
    }

    /**
    * 实现的show方法
    * admina
    */
    public function showAction()
    {
        $id = $this->get('request')->get('id', 0);
        $sid = $this->get('request')->get('sid',0);
        
        if($id && $sid)
        {
            $map = array();
            $map['id'] = $sid;
            $serviceName = $this->get("db.models")->findOneBy($map)->getServiceName();
        
            if(empty($serviceName))
                return $this->error('模型不存在或已被删除');
            
            $this->parameters['info'] = $this->get('serializer')->normalize($this->get($serviceName)->getTrashById($id));
        }
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
    
    /**
     * 实现的show方法
     * admina
     */
    public function logAction()
    {
        $id = $this->get('request')->get('id', 0);
        $sid = $this->get('request')->get('sid', 0);

        $str = json_encode(array('id'=>(int)$id));
        $map = array();
        $map['models'] = $this->get('db.models')->findOneBy(array('id'=>$sid))->getName();
        $map['value']['like'] = substr($str, 0, strlen($str)-1).',%';
        $this->parameters['info'] = $this->get('db.system_log')->findBy($map);
    
        return $this->render($this->getBundleName(), $this->parameters);
    }
}