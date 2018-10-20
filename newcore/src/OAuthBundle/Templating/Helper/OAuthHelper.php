<?php
/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2016-06-20
 */
namespace OAuthBundle\Templating\Helper;

use OAuthBundle\Security\OAuthUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Templating\Helper\Helper;

class OAuthHelper extends Helper
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var OAuthUtils
     */
    private $oauthUtils;

    /**
     * @param OAuthUtils $oauthUtils
     */
    public function __construct(OAuthUtils $oauthUtils)
    {
        $this->oauthUtils = $oauthUtils;
    }

    /**
     * @param null|Request $request
     */
    public function setRequest(Request $request = null)
    {
        $this->request = $request;
    }

    /**
     * @return array
     */
    public function getResourceOwners()
    {
        return $this->oauthUtils->getResourceOwners();
    }

    /**
     * @param string $name
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    public function getLoginUrl($name)
    {
        return $this->oauthUtils->getLoginUrl($this->request, $name);
    }

    /**
     * @param string $name
     * @param string $redirectUrl     Optional
     * @param array  $extraParameters Optional
     *
     * @return string
     */
    public function getAuthorizationUrl($name, $redirectUrl = null, array $extraParameters = array())
    {
        return $this->oauthUtils->getAuthorizationUrl($this->request, $name, $redirectUrl, $extraParameters);
    }

    /**
     * Returns the name of the helper.
     *
     * @return string The helper name
     */
    public function getName()
    {
        return 'oauth';
    }
}
