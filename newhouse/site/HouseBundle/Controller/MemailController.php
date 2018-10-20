<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年03月14日
*/
namespace HouseBundle\Controller;

/**
* 邮箱邮件配置
* @author house
*/
class MemailController extends Controller
{



    /**
    * 发送记录
    * house
    */
    public function emaillogsAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 发送邮件
    * house
    */
    public function emailsendAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 接口设置
    * house
    */
    public function emailapiAction()
    {
        
        if($this->get('request')->getMethod() == "POST")
        {
            $filePath = $this->getSiteRoot()."Config".DIRECTORY_SEPARATOR."config_email.yml";
        
            $cnfInfo = $this->get('core.ymlParser')->ymlRead($filePath);
        
            $cnfInfo['parameters'] = array_merge($cnfInfo['parameters'],$_POST);
        
            $this->get('core.ymlParser')->ymlWrite($cnfInfo, $filePath);
            //清空缓存
            $this->cleanCace();
            return $this->success('操作成功');
        }
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 模版设置
    * house
    */
    public function emailtplsAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}