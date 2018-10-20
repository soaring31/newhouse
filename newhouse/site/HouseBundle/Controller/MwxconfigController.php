<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年02月07日
*/
namespace HouseBundle\Controller;

/**
* 微信管理
* @author house
*/
class MwxconfigController extends Controller
{



    /**
    * 微信管理
    * house
    */
    public function mwxmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
     /**
    * 微信菜单项
    * admina
    */
    public function weixinmenuAction()
    {
        $req = $this->get('request'); 
        $appid = $req->get('appid', '');
        $id = $req->get('id', '');
        if(!empty($id)){
            $ucfg = $this->get('db.wxusers')->getCfgByid($id); 
            if(empty($ucfg)) return $this->error("操作失败：id=[$id]错误。");
            $appid = $ucfg['appid'];
            //$umod = $ucfg['infotype'];
        }else{
            //$umod = '';
        }
        $umod = '';
        $this->parameters['data'] = $this->get('db.wxmenus')->getMenuCfgs($appid,$umod,1);
        $this->parameters['appid'] = $appid; 
        // 得到type配置
        $data = array();
        $modelForm = $this->get('db.model_form')->findOneBy(array('name'=>'wxmenus')); 
        //if(!is_object($modelForm)) return $data;
        $map = array();
        $map['model_form_id'] = $modelForm->getId();
        $map['name'] = 'type';
        $modelFormAttr = $this->get('db.model_form_attr')->findOneBy($map);
        $choices = $modelFormAttr->getChoices(); 
        $info = $this->get('core.common')->getQueryParam($choices); 
        foreach($info as $k=>$v)
        {
            $data[$k]['title'] = $v;
            $data[$k]['value'] = $k;
        }
        unset($data['system']);
        $this->parameters['typecfgs'] = $data; 
        //dump($data); die();
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 微信菜单方案
    * house
    */
    public function mwxplanlistAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
    /**
    * 微信菜单方案
    * house
    */
    public function mwxplanmenusAction()
    {
        $aid = $this->get('request')->get('id',0);
        $umod = '';
        $this->parameters['data'] = $this->get('db.wxplanmenus')->getMenuCfgs((int)$aid,$umod,1);
        
        // 得到type配置
        $data = array();
        $modelForm = $this->get('db.model_form')->findOneBy(array('name'=>'wxmenus'));
        $map = array();
        $map['model_form_id'] = $modelForm->getId();
        $map['name'] = 'type';
        $modelFormAttr = $this->get('db.model_form_attr')->findOneBy($map);
        $choices = $modelFormAttr->getChoices();
        $info = $this->get('core.common')->getQueryParam($choices);
        foreach($info as $k=>$v)
        {
            $data[$k]['title'] = $v;
            $data[$k]['value'] = $k;
        }
        unset($data['system']);
        $this->parameters['typecfgs'] = $data;

        return $this->render($this->getBundleName(), $this->parameters);
    }
}