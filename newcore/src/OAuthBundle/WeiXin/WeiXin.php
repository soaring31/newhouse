<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年5月12日
*/
namespace OAuthBundle\WeiXin;

use CoreBundle\Services\ServiceBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * 微信公众号
 * @author Administrator
 *
 */
class WeiXin extends ServiceBase
{
    protected $resourceOwner;
    protected $name = 'weixin';
    
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->resourceOwner = $this->get('oauth.security.oauth_utils')->getResourceOwner($this->name);
    }
}