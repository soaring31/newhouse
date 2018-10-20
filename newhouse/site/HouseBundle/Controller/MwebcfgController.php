<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年08月05日
*/
namespace HouseBundle\Controller;
        
/**
* 网站参数
* @author house
*/
class MwebcfgController extends Controller
{
    /**
    * 站点设置
    * house
    */
    public function mwebsetAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
            
    /**
    * 实现的save方法
    * house
    */
    public function saveAction()
    {
        if($this->get('request')->getMethod() == "POST")
        {
            $ids = $this->get('request')->get('id', '');
            $data = $this->get('request')->request->all();

            if($ids)
            {
                $ids = explode(',', $ids);
                foreach($ids as $id)
                {
                    $this->get('db.mconfig')->update($id, $data);
                }
            }else
                $this->get('db.mconfig')->add($data);
        
            return $this->success('操作成功');
        }
        
        return $this->error('操作失败');
    }            
    /**
    * 实现的delete方法
    * house
    */
    public function deleteAction()
    {
        $ids = $this->get('request')->get('id', '');
        
        if($ids)
        {
            $ids = explode(',', $ids);
            foreach($ids as $id)
            {
                $this->get('db.mconfig')->delete($id);
            }
        }
        return $this->success('操作成功');
    }

    /**
    * 访问注册
    * house
    */
    public function mvisitAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 页面设置
    * house
    */
    public function mpagesetAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 快捷登录
    * house
    */
    public function mloginsetAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

     /**
    * 附件设置
    * house
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
    * 水印设置
    * house
    */
    public function webwatermarkAction()
    {
        
        $filePath = $this->getSiteRoot()."Config".DIRECTORY_SEPARATOR."config_watermark.yml";
        $this->parameters['info'] = $this->get('core.ymlParser')->ymlRead($filePath);
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 平台支付设置
    * house
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
    * 在线支付设置
    * house
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
    * 网站设置
    * house
    */
    public function webconfigbaseAction()
    {
        
        if($this->get('request')->getMethod() == "POST")
        {
            $filePath = $this->getSiteRoot()."Config".DIRECTORY_SEPARATOR."config_info.yml";
             
            $cnfInfo = $this->get('core.ymlParser')->ymlRead($filePath);
             
            $cnfInfo['parameters'] = array_merge($cnfInfo['parameters'],$_POST);

            $maindomain = isset($cnfInfo['parameters']['maindomain'])?explode('.',$cnfInfo['parameters']['maindomain']):'';

            if($maindomain)
            {
                if(count($maindomain)<=2)
                    return $this->error('请输入有效的域名');

                unset($maindomain[0]);
                
                $cnfInfo['parameters']['server_topdomain'] = implode('.', $maindomain);
            }

            $this->get('core.ymlParser')->ymlWrite($cnfInfo, $filePath);

            //清空缓存
            $this->cleanCace();
            return $this->success('操作成功');
        }
        $this->parameters['groups'] = array();//$this->get('db.user_models')->findBy(array('autocheck'=>1));
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 邮箱设置
    * house
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
    * 主题设置
    * house
    */
    public function webconfigthemesAction()
    {
        
        if($this->get('request')->getMethod() == "POST")
        {
            $filePath = $this->getSiteRoot()."Config".DIRECTORY_SEPARATOR."config_themes.yml";
        
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
    * house
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
    * 模板变量
    * house
    */
    public function mvariablesAction()
    {

        return $this->render($this->getBundleName(), $this->parameters);
    }
    
    /**
    * 自定义变量
    * house
    */
    public function mvariables_userAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 实现的savet方法
    * house
    */
    public function savetAction()
    {
        if($this->get('request')->getMethod() == "POST")
        {
            $ids = $this->get('request')->get('id', '');
            $data = $this->get('request')->request->all();
    
            if($ids)
            {
                $ids = explode(',', $ids);
                foreach($ids as $id)
                {
                    $this->get('db.mconfig')->update($id, $data);
                }
            }else
                $this->get('db.mconfig')->add($data);
    
            return $this->success('操作成功');
        }
    
        return $this->error('操作失败');
    }

    /**
    * 基础设置
    * house
    */
    public function mbasecfgAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 语言设置
    * house
    */
    public function webconfiglanguageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}