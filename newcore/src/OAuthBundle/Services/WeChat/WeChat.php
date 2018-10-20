<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年4月11日
*/
namespace OAuthBundle\Services\WeChat;

use OAuthBundle\Services\ErrorCode;
use CoreBundle\Services\ServiceBase;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class WeChat extends ServiceBase
{
    protected $appId;
    protected $appSecret;
    protected $easkey;
    protected $component_access_token;
    protected $component_verify_ticket;
    protected $error;
    protected $container;
    protected $account;
    protected $errorCode;
    protected $resourceOwner;
    protected $name = 'wechat';
    
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    
        $this->resourceOwner = $this->get('oauth.security.oauth_utils')->getResourceOwner($this->name);
    
        //错误代码转义
        $errorCode = new ErrorCode();
        $this->errorCode = $errorCode->getText();
    
        self::init($this->resourceOwner->getOption('client_id'));
    }
    
    /**
     * 初始化
     * @param UserInterface $user
     * @param array $extraParameters
     * @return boolean
     */
    public function init($appId)
    {
        //读缓存
        $this->account = $this->get('core.common')->S('oauth_account_'.$appId);
    
        if(!is_object($this->account))
        {
            $map = array();
            $map['appid'] = $appId;
    
            $this->account = $this->get('db.wxusers')->findOneBy($map);
    
            if(!is_object($this->account))
                throw new \InvalidArgumentException('无效的appid'.$appId);
    
            //写缓存,access_token的有效期目前为2个小时也就是7200秒
            $this->get('core.common')->S('oauth_account_'.$appId, $this->account, 7100);
        }
        $this->appId = $this->account->getAppId();
        $this->appSecret = $this->account->getAppsecret();
        $this->easkey = $this->account->getEaskey();
        $this->component_access_token = $this->account->getActoken();
        //$this->component_verify_ticket = $this->account->getComponentVerifyTicket();
    
        $options = array();
        $options['client_id'] = $this->appId;
        $options['client_secret'] = $this->appSecret;
        $options['client_easkey'] = $this->easkey;
        $this->resourceOwner->setOption($options);
    }
    
    /**
     * 网页授权
     * @param unknown $info
     * @param string $scope
     * @param string $fansInfo
     * @param string $code
     * @param string $state
     * @throws \LogicException
     * @return multitype:unknown
     */
    public function webOauth($info, $scope = '', $fansInfo = '', $code='', $state='')
    {
        $request = $this->get('request');
        $redirect_uri = "http://".$request->server->get('HTTP_HOST').$request->getRequestUri();
    
        $codeUrl = $this->getCodeUrl($info, $redirect_uri, $scope, '', $fansInfo);
        if (empty($code) && empty($state)) {
            header('Location: ' . $codeUrl);
            exit();
        }else {
            if (!empty($code)) {
                $res = $this->get_web_access_token($info, $code);
    
                if (!isset($res['errcode']))
                    return array('access_token' => $res['access_token'], 'openid' => $res['openid']);
                else
                    throw new \LogicException("授权错误，请检查公众号权限和设置");
            }
        }
    }
    
    public function getCodeUrl($info, $redirect_uri = '', $scope = '', $state = 'oauth', $fansInfo)
    {
        if (empty($scope)) {
            if ($info['oauthinfo'] && (MODULE_NAME != 'Index')) {
                $scope = 'snsapi_userinfo';
            }
            else {
                $scope = 'snsapi_base';
            }
        }
    
        $redirect_uri = urlencode($redirect_uri);
        $url = '';
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $info['appid'] . '&redirect_uri=' . $redirect_uri . '&response_type=code&scope=' . $scope . '&state=' . $state;
        if (($info['type'] == 1) && ($info['winxintype'] == 3) && ($info['oauth'] == 1)) {
            $url .= '&component_appid=' . $this->appId;
        }
    
        $url .= '#wechat_redirect';
        return $url;
    }
    
    /**
     * 获取微信AccessToken（公众平台）
     */
    public function getAccessToken()
    {
        $map = array();
        $map['appid'] = $this->resourceOwner->getOption('client_id');
        $map['appsecret'] = $this->resourceOwner->getOption('client_secret');
    
        $wxuser = $this->get('core.common')->S('oauth_account_'.$map['appid']);
    
        $wxuser = $wxuser?$wxuser:$this->get('db.wxusers')->findOneBy($map);
    
        if($wxuser->getAcexp()-7000<time())
        {
            $info = $this->resourceOwner->getAccessToken($this->get('request'), '', array('code'=>'a123'));
    
            if(isset($info['errcode']))
                throw new \LogicException(isset($this->errorCode[$info['errcode']])?$this->errorCode[$info['errcode']].$info['errmsg']:$info['errmsg']);
    
            $wxuser->setActoken($info['access_token']);
            $wxuser->setAcexp(time()+$info['expires_in']);
            $wxuser->setAppid($map['appid']);
    
            $this->get('db.wxusers')->update($wxuser->getId(),array(), $wxuser, false);
    
            //写缓存,access_token的有效期目前为2个小时也就是7200秒
            $this->get('core.common')->S('oauth_account_'.$map['appid'], $wxuser, 7100);
        }
        //dump($wxuser->getActoken());die();
        //$aa = $this->resourceOwner->getPreAuthCode($wxuser->getActoken());
        //dump($aa);die();
        return $wxuser->getActoken();
    }
}