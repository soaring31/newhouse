<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016-06-20
*/
namespace OAuthBundle\Controller;

use CoreBundle\Controller\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * 生成图形验证码
     */
    public function captchaAction()
    {
        //加载验证码服务
        $captchaImg = $this->container->get('core.captcha');
        $captchaImg->length = 5;
        $captchaImg->width  = 150;
        $captchaImg->height = 33;
    
        $captchaImg->generate_image();
        return true;
    }
    
    /**
     * Generates a route.
     *
     * @param string  $route    Route name
     * @param array   $params   Route parameters
     * @param boolean $absolute Absolute url or note.
     *
     * @return string
     */
    protected function generate($route, $params = array(), $absolute = false)
    {
        return $this->get('router')->generate($route, $params, $absolute);
    }
    
    /**
     * Get a resource owner by name.
     *
     * @param string $name
     *
     * @return ResourceOwnerInterface
     *
     * @throws \RuntimeException if there is no resource owner with the given name.
     */
    protected function getResourceOwnerByName($name)
    {
        foreach ($this->container->getParameter('oauth.firewall_names') as $firewall) {
            $id = 'oauth.resource_ownermap.'.$firewall;
            if (!$this->container->has($id)) {
                continue;
            }
    
            $ownerMap = $this->container->get($id);
    
            $resourceOwner = $ownerMap->getResourceOwnerByName($name);
    
            if ($resourceOwner)
                return $resourceOwner;
        }
    
        throw new \RuntimeException(sprintf("No resource owner with name '%s'.", $name));
    }
}
