<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年6月23日
*/
namespace OAuthBundle\Services\WeiXin;

class WeiXinMenu extends WeiXin
{
    private function _arrFormat($mcfg=array())
    {
        $data = array(); 
        for($i=1;$i<=3;$i++){
            if(empty($mcfg["{$i}0"]['title']))
                continue;

            $rei = array();
            $rei['name'] = urlencode($mcfg["{$i}0"]['title']);
            $subs = array();
            for($j=1;$j<=5;$j++){
                if(empty($mcfg["{$i}{$j}"]['title']))
                    continue;
                $name = urlencode($mcfg["{$i}{$j}"]['title']);
                $type = $mcfg["{$i}{$j}"]['type'];
                $keyword = $mcfg["{$i}{$j}"]['keyword']; //$this->fmtUrl($mcfg["{$i}{$j}"]['keyword'] //);
                $type = strpos($keyword,'://') ? 'view' : $type; //其它操作?! 
                $key = $type=='view' ? 'url' : 'key';
                $keyword = urlencode($keyword);
                $subs[] = array('type'=>$type, 'name'=>$name, $key=>$keyword, );
            }

            if(empty($subs)){
                $keyword = $mcfg["{$i}0"]['keyword']; // $keyword = $this->fmtUrl($mcfg["{$i}0"]['keyword']);
                $type = $rei['type'] = strpos($keyword,'://') ? 'view' : 'click'; //其它操作?! 
                $key = $type=='view' ? 'url' : 'key';
                $rei[$key] = $keyword;
            }else{
                $rei['sub_button'] = $subs;
            }
            $data['button'][] = $rei; 
        } 
        return $data;
        /*
        $data['button'][0]['type'] = urlencode('click');
        $data['button'][0]['name'] = urlencode('扫码s');
        $data['button'][0]['sub_button'][0]['type'] = 'scancode_waitmsg';
        $data['button'][0]['sub_button'][0]['name'] = urlencode('扫码带提示');
        $data['button'][0]['sub_button'][0]['key'] = 'rselfmenu_0_0';
        $data['button'][0]['sub_button'][0]['sub_button'] =  array();
        $data['button'][0]['sub_button'][1]['type'] = 'scancode_push';
        $data['button'][0]['sub_button'][1]['name'] = urlencode('扫码推事件');
        $data['button'][0]['sub_button'][1]['key'] = 'rselfmenu_0_1';
        $data['button'][0]['sub_button'][1]['sub_button'] =  array();
        
        $data['button'][1]['type'] = 'click';
        $data['button'][1]['name'] = urlencode('发图d');
        $data['button'][1]['sub_button'][0]['type'] = 'pic_sysphoto';
        $data['button'][1]['sub_button'][0]['name'] = urlencode('系统拍照发图');
        $data['button'][1]['sub_button'][0]['key'] = 'rselfmenu_1_0';
        $data['button'][1]['sub_button'][0]['sub_button'] =  array();
        $data['button'][1]['sub_button'][1]['type'] = 'pic_photo_or_album';
        $data['button'][1]['sub_button'][1]['name'] = urlencode('拍照或者相册发图');
        $data['button'][1]['sub_button'][1]['key'] = 'rselfmenu_1_1';
        $data['button'][1]['sub_button'][1]['sub_button'] =  array();
        $data['button'][1]['sub_button'][2]['type'] = 'pic_weixin';
        $data['button'][1]['sub_button'][2]['name'] = urlencode('微信相册发图');
        $data['button'][1]['sub_button'][2]['key'] = 'rselfmenu_1_2';
        $data['button'][1]['sub_button'][2]['sub_button'] =  array();
        */
    }

    /**
     * 菜单创建接口
     * @throws \LogicException
     * @return boolean
     */
    public function create()
    {
        $mcfg = $this->get('db.wxmenus')->getMenuCfgs($this->appId); 
        $data = $this->_arrFormat($mcfg);
        $data = urldecode(json_encode($data));

        //删除原菜单
        self::delete();
        //创建菜单
        $options = array();
        $options['me_url'] = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.parent::getAccessToken();
        $this->resourceOwner->setOption($options);
        
        $info = $this->resourceOwner->getMeUrl($data);

        if(isset($info['errcode'])&&$info['errcode']==0)
            return true;
            
        parent::getError($info);

        return false;
    }
    
    
    /*
     * 菜单查询接口
     */
    public function lists()
    {
        $options = array();
        $options['me_url'] = 'https://api.weixin.qq.com/cgi-bin/menu/get?access_token='.parent::getAccessToken();
        $this->resourceOwner->setOption($options);
        
        $info = $this->resourceOwner->getMeUrl(array());

        parent::getError($info);

        return $info['menu'];
    }
    
    /**
     * 菜单删除接口
     */
    public function delete()
    {
        $options = array();
        $options['me_url'] = 'https://api.weixin.qq.com/cgi-bin/menu/delete?access_token='.parent::getAccessToken();
        $this->resourceOwner->setOption($options);
        $this->resourceOwner->getMeUrl(array());
    }
    
    /**
     * 获取自定义菜单配置接口
     */
    public function getCurrentSelfMenuInfo()
    {
        $options = array();
        $options['me_url'] = 'https://api.weixin.qq.com/cgi-bin/get_current_selfmenu_info?access_token='.parent::getAccessToken();
        $this->resourceOwner->setOption($options);
        
        $info = $this->resourceOwner->getMeUrl(array());
        
        parent::getError($info);
        
        return $info['selfmenu_info'];
    }
}