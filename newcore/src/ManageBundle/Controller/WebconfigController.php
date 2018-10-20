<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年1月15日
*/
namespace ManageBundle\Controller;

/**
 * 网站设置
 */
class WebconfigController extends Controller
{
    /**
     * 站点设置
     */
    public function webconfigbaseAction()
    {
        if($this->get('request')->getMethod() == "POST")
        {
            $filePath = $this->getSiteRoot()."Config".DIRECTORY_SEPARATOR."config_info.yml";
             
            $cnfInfo = $this->get('core.ymlParser')->ymlRead($filePath);
             
            $cnfInfo['parameters'] = array_merge($cnfInfo['parameters'],$_POST);
             
            $this->get('core.ymlParser')->ymlWrite($cnfInfo, $filePath);

            //清空缓存
            $this->cleanCace();
            return $this->success('操作成功');
        }
        $this->parameters['groups']	= array();//$this->get('db.user_models')->findBy(array('autocheck'=>1));
        return $this->render($this->getBundleName(), $this->parameters);
    }
    
    /**
     * 邮箱设置
     */
    public function webconfigmailAction()
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
     * 附件设置
     */
    public function webconfigupfileAction()
    {
        if($this->get('request')->getMethod() == "POST")
        {
            $filePath = $this->getSiteRoot()."Config".DIRECTORY_SEPARATOR."config_upfile.yml";
        
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
     * 在线支付设置
     */
    public function webconfigalipayAction()
    {
        if($this->get('request')->getMethod() == "POST")
        {
            $filePath = $this->getSiteRoot()."Config".DIRECTORY_SEPARATOR."config_alipay.yml";
             
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
     * 主题设置
     */
    public function webconfigthemesAction()
    {
        if($this->get('request')->getMethod() == "POST")
        {
            $filePath = $this->getSiteRoot()."Config".DIRECTORY_SEPARATOR."config_alipay.yml";
        
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
     * 微WIFI设置
     */
    public function webconfigweiwifiAction()
    {
        if($this->get('request')->getMethod() == "POST")
        {
            $filePath = $this->getSiteRoot()."Config".DIRECTORY_SEPARATOR."config_weiwifi.yml";
             
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
     * 平台支付设置
     */
    public function webconfigplatformAction()
    {
        if($this->get('request')->getMethod() == "POST")
        {
            $filePath = $this->getSiteRoot()."Config".DIRECTORY_SEPARATOR."config_platform.yml";
             
            $cnfInfo = $this->get('core.ymlParser')->ymlRead($filePath);
            $cnfInfo['parameters'] = array_merge($cnfInfo['parameters'],$_POST);
             
            $this->get('core.ymlParser')->ymlWrite($cnfInfo, $filePath);
             
            return $this->success('操作成功');
        }
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 水印设置
    * admina
    */
    public function webwatermarkAction()
    {
        $filePath = $this->getSiteRoot()."Config".DIRECTORY_SEPARATOR."config_watermark.yml";
        $this->parameters['info'] = $this->get('core.ymlParser')->ymlRead($filePath);
        return $this->render($this->getBundleName(), $this->parameters);
    }
}