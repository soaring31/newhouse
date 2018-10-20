<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年02月10日
*/
namespace HouseBundle\Controller;

/**
* 手机短信
* @author house
*/
class MsmsController extends Controller
{

	public $cfgPath = ''; // 配置路径


	/**
    * 测试方法（用于测试短信各逻辑）
    * admina
    */
    public function smstestAction()
    {
        echo 'test';
        die();
    }

    
    /**
    * 手机短信记录
    * house
    */
    public function smslogsAction()
    {
        $this->_readCfogs();
        $api = $this->parameters['sms_api'];

        $this->parameters['errInfo'] = $api ? $this->get('core.'.$api)->errInfo(0,1) : array(0=>'(接口关闭-无对照表)');

        return $this->render($this->getBundleName(), $this->parameters);
    }
    
    /**
    * 读取设置参数
    * admina
    */
    public function _readCfogs()
    {
        $this->cfgPath = $this->getSiteRoot()."Config".DIRECTORY_SEPARATOR."config_sms.yml";
        $cnfInfo = $this->get('core.ymlParser')->ymlRead($this->cfgPath); 
        $this->parameters = $this->paramback = $cnfInfo['parameters'];
    } 

    /**
    * 短信发送
    * house
    */
    public function smssendAction()
    {
        
        $this->_readCfogs();
        if($this->get('request')->getMethod() == "POST")
        {
            $option = array();
            $option['name'] = $this->get('request')->get('tel','');
            $option['tpl'] = $this->get('request')->get('tpl', '');
            $option['msg'] = $this->get('request')->get('msg', '');

            $msg = $this->get('request')->get('msg', '');

            $res = $this->get('core.sms')->send($option,$msg); 
           
            if($res[0]==1)
                return $this->success('发送成功');
            else
                return $this->error('发送失败:'.$res[1]);

        }
        
        $mconfig = $this->get('db.mconfig')->getData(array('ename'=>'mwebset'));
        $site_name = isset($mconfig['hostname']['value'])?$mconfig['hostname']['value']:'';

        if($this->get('core.sms')->isClosed())
            return $this->error('接口关闭');

        $this->parameters['api'] = $api = $this->parameters['sms_api'];
        $this->parameters['name'] = $name = $this->parameters['sms_cfgs'][$api];
        $this->parameters['site'] = empty($this->parameters['sms_urls'][$api]) ? '' : "；<a href='{$this->parameters['sms_urls'][$api]}' target='b'>接口官网</a>";
        $balance = $this->get('core.sms')->balance();
        
        $timeStr = date('H:i:s');
        $blstr = $balance[0]==1 ? $balance[1] : "0:《".$balance[1].'》';
        $this->parameters['blstr'] = $blstr;
        $this->parameters['def_msg'] = "$name($api)余额:{$blstr}条({$timeStr})【{$site_name}】";
        $this->parameters['errInfo'] = $this->get('core.'.$api)->errInfo(0,1);
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 接口设置
    * house
    */
    public function smsapiAction()
    {
        
        $cnfInfo = array();
        $this->_readCfogs();

        if($this->get('request')->getMethod() == "POST")
        {
            $post = $_POST;
            // save
            $cnfInfo['parameters'] = array_merge($this->paramback,$post);
            $this->get('core.ymlParser')->ymlWrite($cnfInfo, $this->cfgPath);
            //清空缓存
            $this->get('core.common')->cleanCache();
            return $this->success('操作成功');
        }
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 模板设置
    * house
    */
    public function smstplsAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 会员余额
    * house
    */
    public function smsulistAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

}