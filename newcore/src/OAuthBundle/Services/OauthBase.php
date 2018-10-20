<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年4月12日
*/
namespace OAuthBundle\Services;

use CoreBundle\Services\ServiceBase;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class OauthBase extends ServiceBase
{
    protected $name;
    protected $account;
    protected $container;
    protected $errorCode;
    protected $resourceOwner;
    
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
}