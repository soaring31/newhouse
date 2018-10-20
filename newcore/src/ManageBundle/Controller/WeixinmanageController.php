<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年06月16日
*/
namespace ManageBundle\Controller;
        
/**
* 
* @author admina
*/
class WeixinmanageController extends Controller
{
    
    /**
    * 实现的weixinusers方法
    * admina
    */
    public function weixinusersAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
     * 开关与设置
     */
    public function weixinconfigsAction()
    {
        if($this->get('request')->getMethod() == "POST")
        {

            $post = $_POST;
            // get weixin config and save ... 
            $wx_accsite = $this->get('request')->get('wx_accsite','');
            $ucfg = $this->get('db.wxusers')->getCfgByid($wx_accsite); 
            foreach ($ucfg as $key => $val) {
                $post["wxcfg_$key"] = $val;
            } 
            // save
            $filePath = $this->getSiteRoot()."Config".DIRECTORY_SEPARATOR."config_weixin.yml";
            $cnfInfo = $this->get('core.ymlParser')->ymlRead($filePath);
            $cnfInfo['parameters'] = array_merge($cnfInfo['parameters'],$post);
            $this->get('core.ymlParser')->ymlWrite($cnfInfo, $filePath);
            //清空缓存
            $this->cleanCace();
            return $this->success('操作成功');
        }
        $this->parameters['groups']	= array(); 
        $this->parameters['swcfgs'] = array(
			'login'=>'扫码登录',
			'getpw'=>'找回密码',
			'scsend'=>'发信息',
		);
        $whrkey = $this->get('request')->get('name','');
        $whrarr = empty($whrkey) ? array() : array('name'=>array('like'=>"%$whrkey%")); 
        $this->parameters['ulists'] = $this->get('db.wxusers')->getWxList($whrarr);
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
        $id = $req->get('_id', '');
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
}


/*

*/
