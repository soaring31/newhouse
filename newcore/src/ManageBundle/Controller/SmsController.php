<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年06月29日
*/
namespace ManageBundle\Controller;
#use CoreBundle\Services\DataTrans\DataTrans;
        
/**
* 
* @author admina
*/
class SmsController extends Controller
{
    
    public $cfgPath = ''; // 配置路径

    /**
    * 发送记录
    * admina
    */
    public function smslogsAction()
    {
        $this->_readCfogs();
        $api = $this->parameters['sms_api'];
        $this->parameters['errInfo'] = $api ? $this->get('core.'.$api)->errInfo(0,1) : array(0=>'(接口关闭-无对照表)');
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 短信发送
    * admina
    */
    public function smssendAction()
    {
        $this->_readCfogs();
        if($this->get('request')->getMethod() == "POST")
        {
            $tel = $this->get('request')->get('tel');
            $msg = $this->get('request')->get('msg');
            $res = $this->get('core.sms')->send($tel,$msg); 
            if($res[0]==1)
                return $this->success('发送成功');
            else
                return $this->error('发送失败:'.$res[1]);
        }
        
        $map = array();
        $map['ename'] = 'mwebset';

        $mconfig = $this->get('db.mconfig')->getData($map);

        $siteName = isset($mconfig['hostname']['value'])?$mconfig['hostname']['value']:'';

        if($this->get('core.sms')->isClosed())
            return $this->error('接口关闭');

        $this->parameters['api'] = $api = $this->parameters['sms_api'];
        $this->parameters['name'] = $name = $this->parameters['sms_cfgs'][$api];
        $this->parameters['site'] = empty($this->parameters['sms_urls'][$api]) ? '' : "；<a href='{$this->parameters['sms_urls'][$api]}' target='b'>接口官网</a>";
        $balance = $this->get('core.sms')->balance();
        $blstr = $balance[0]==1 ? $balance[1] : "0:《".$balance[1].'》';
        $this->parameters['blstr'] = $blstr;
        $this->parameters['def_msg'] = "$name($api)余额:{$blstr}条(".date('H:i:s').")【{$siteName}】";
        $this->parameters['errInfo'] = $this->get('core.'.$api)->errInfo(0,1);
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 接口设置
    * admina
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
    * 实现的smsulistAction方法
    * admina
    */
    public function smsulistAction()
    { 
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 短信模版
    * admina
    */
    public function smstplsAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

}

