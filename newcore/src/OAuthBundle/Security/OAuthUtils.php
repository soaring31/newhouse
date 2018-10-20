<?php
/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2016-06-20
 */
namespace OAuthBundle\Security;

use CoreBundle\Services\ServiceBase;
use OAuthBundle\OAuth\ResourceOwnerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\HttpUtils;
use OAuthBundle\Security\Http\ResourceOwnerMap;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class OAuthUtils extends ServiceBase
{
    const SIGNATURE_METHOD_HMAC      = 'HMAC-SHA1';
    const SIGNATURE_METHOD_RSA       = 'RSA-SHA1';
    const SIGNATURE_METHOD_PLAINTEXT = 'PLAINTEXT';

    /**
     * @var boolean
     */
    protected $connect;

    /**
     * @var HttpUtils
     */
    public $httpUtils;

    /**
     * @var ResourceOwnerMap[]
     */
    protected $ownerMaps = array();

    /**
     * @var SecurityContextInterface
     *
     * @deprecated since 0.4. To be removed in 1.0. Use $authorizationChecker property instead.
     */
    protected $securityContext;

    /**
     * SecurityContextInterface for Symfony <2.6
     * To be removed with all related logic (constructor, configs, extension)
     *
     * @var AuthorizationCheckerInterface|SecurityContextInterface
     */
    protected $authorizationChecker;

    /**
     * @param HttpUtils                                              $httpUtils
     * @param AuthorizationCheckerInterface|SecurityContextInterface $authorizationChecker
     * @param boolean                                                $connect
     */
    public function __construct(HttpUtils $httpUtils, $authorizationChecker, $connect)
    {
        if (!$authorizationChecker instanceof AuthorizationCheckerInterface && !$authorizationChecker instanceof SecurityContextInterface)
            throw new \InvalidArgumentException('Argument 2 should be an instance of Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface or Symfony\Component\Security\Core\SecurityContextInterface');

        $this->httpUtils            = $httpUtils;
        $this->authorizationChecker = $authorizationChecker;
        $this->securityContext      = $this->authorizationChecker;
        $this->connect              = $connect;
    }
    
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param ResourceOwnerMap $ownerMap
     */
    public function addResourceOwnerMap(ResourceOwnerMap $ownerMap)
    {
        $this->ownerMaps[] = $ownerMap;
    }

    /**
     * @return array
     */
    public function getResourceOwners()
    {
        $resourceOwners = array();

        foreach ($this->ownerMaps as $ownerMap)
        {
            $resourceOwners = array_merge($resourceOwners, $ownerMap->getResourceOwners());
        }

        return array_keys($resourceOwners);
    }

    /**
     * 根据ResourceOwner的ID,得到第三方授权页的url
     * @param Request $request
     * @param string  $name
     * @param string  $redirectUrl     Optional 授权完成之后，重定向回本系统的url。
     * @param array   $extraParameters Optional
     *
     * @return string
     */
    public function getAuthorizationUrl(Request $request, $name, $redirectUrl = null, array $extraParameters = array())
    {
        $resourceOwner = $this->getResourceOwner($name);
        if (null === $redirectUrl)
        {
            if (!$this->connect || !$this->authorizationChecker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
                $redirectUrl = $this->get('router.request_context')->getScheme();
                $redirectUrl .= $redirectUrl?'://':"";//
                $redirectUrl .= $this->get('core.common')->C('maindomain');
                $redirectUrl .= $this->get('router')->generate($this->getResourceOwnerCheckPath($name));

                //$redirectUrl = $this->httpUtils->generateUri($request, $this->getResourceOwnerCheckPath($name));
            } else {
                $redirectUrl = $this->get('router.request_context')->getScheme();
                $redirectUrl .= $redirectUrl?'://':"";//
                $redirectUrl .= $this->get('core.common')->C('maindomain');
                $redirectUrl .= $this->get('router')->generate($this->getResourceOwnerCheckPath($name));
                //$redirectUrl = $this->getServiceAuthUrl($request, $resourceOwner);
            }
        }

        return $resourceOwner->getAuthorizationUrl($redirectUrl, $extraParameters);
    }

    /**
     * @param Request                $request
     * @param ResourceOwnerInterface $resourceOwner
     *
     * @return string
     */
    public function getServiceAuthUrl(Request $request, ResourceOwnerInterface $resourceOwner)
    {
        if ($resourceOwner->getOption('auth_with_one_url'))
            return $this->httpUtils->generateUri($request, $this->getResourceOwnerCheckPath($resourceOwner->getName())).'?authenticated=true';

        $request->attributes->set('service', $resourceOwner->getName());

        return $this->httpUtils->generateUri($request, 'oauth_connect_service');
    }

    /**
     * @param Request $request
     * @param string  $name
     *
     * @return string
     */
    public function getLoginUrl(Request $request, $name)
    {
        // Just to check that this resource owner exists
        $this->getResourceOwner($name);

        $request->attributes->set('service', $name);

        return $this->httpUtils->generateUri($request, 'oauth_service_redirect');
    }

    /**
     * Sign the request parameters
     *
     * @param string $method          Request method
     * @param string $url             Request url
     * @param array  $parameters      Parameters for the request
     * @param string $clientSecret    Client secret to use as key part of signing
     * @param string $tokenSecret     Optional token secret to use with signing
     * @param string $signatureMethod Optional signature method used to sign token
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    public static function signRequest($method, $url, $parameters, $clientSecret, $tokenSecret = '', $signatureMethod = self::SIGNATURE_METHOD_HMAC)
    {
        // Validate required parameters
        foreach (array('oauth_consumer_key', 'oauth_timestamp', 'oauth_nonce', 'oauth_version', 'oauth_signature_method') as $parameter) {
            if (!isset($parameters[$parameter]))
                throw new \RuntimeException(sprintf('Parameter "%s" must be set.', $parameter));
        }

        // Remove oauth_signature if present
        // Ref: Spec: 9.1.1 ("The oauth_signature parameter MUST be excluded.")
        if (isset($parameters['oauth_signature']))
            unset($parameters['oauth_signature']);

        // Parse & add query params as base string parameters if they exists
        $url = parse_url($url);

        $queryParams = array();

        if (isset($url['query'])) {
            parse_str($url['query'], $queryParams);
            $parameters += $queryParams;
        }

        // Remove default ports
        // Ref: Spec: 9.1.2
        $explicitPort = isset($url['port']) ? $url['port'] : null;
        if (('https' === $url['scheme'] && 443 === $explicitPort) || ('http' === $url['scheme'] && 80 === $explicitPort))
            $explicitPort = null;

        // Remove query params from URL
        // Ref: Spec: 9.1.2
        $url = sprintf('%s://%s%s%s', $url['scheme'], $url['host'], ($explicitPort ? ':'.$explicitPort : ''), isset($url['path']) ? $url['path'] : '');

        // Parameters are sorted by name, using lexicographical byte value ordering.
        // Ref: Spec: 9.1.1 (1)
        uksort($parameters, 'strcmp');

        // http_build_query should use RFC3986
        $parts = array(
            // HTTP method name must be uppercase
            // Ref: Spec: 9.1.3 (1)
            strtoupper($method),
            rawurlencode($url),
            rawurlencode(str_replace(array('%7E', '+'), array('~', '%20'), http_build_query($parameters, '', '&'))),
        );

        $baseString = implode('&', $parts);

        switch ($signatureMethod)
        {
            case self::SIGNATURE_METHOD_HMAC:
                $keyParts = array(
                    rawurlencode($clientSecret),
                    rawurlencode($tokenSecret),
                );

                $signature = hash_hmac('sha1', $baseString, implode('&', $keyParts), true);
                break;

            case self::SIGNATURE_METHOD_RSA:
                if (!function_exists('openssl_pkey_get_private'))
                    throw new \RuntimeException('RSA-SHA1 signature method requires the OpenSSL extension.');

                $privateKey = openssl_pkey_get_private(file_get_contents($clientSecret), $tokenSecret);
                $signature  = false;

                openssl_sign($baseString, $signature, $privateKey);
                openssl_free_key($privateKey);
                break;

            case self::SIGNATURE_METHOD_PLAINTEXT:
                $signature = $baseString;
                break;

            default:
                throw new \RuntimeException(sprintf('Unknown signature method selected %s.', $signatureMethod));
        }

        return base64_encode($signature);
    }

    /**
     * @param string $name
     *
     * @return ResourceOwnerInterface
     *
     * @throws \RuntimeException
     */
    public function getResourceOwner($name)
    {
        $resourceOwner = null;

        foreach ($this->ownerMaps as $ownerMap) {
            $resourceOwner = $ownerMap->getResourceOwnerByName($name);
            if ($resourceOwner instanceof ResourceOwnerInterface) {
                return $resourceOwner;
            }
        }

        if (!$resourceOwner instanceof ResourceOwnerInterface) {
            throw new \RuntimeException(sprintf("No resource owner with name '%s'.", $name));
        }

        return $resourceOwner;
    }

    /**
     * @param string $name
     *
     * @return null|string
     */
    public function getResourceOwnerCheckPath($name)
    {
        foreach ($this->ownerMaps as $ownerMap)
        {
            $potentialResourceOwnerCheckPath = $ownerMap->getResourceOwnerCheckPath($name);
            if ($potentialResourceOwnerCheckPath)
                return $potentialResourceOwnerCheckPath;
        }

        return null;
    }

    public function id_format(&$id)
    {
        if ( is_float($id) )
            $id = number_format($id, 0, '', '');
        elseif ( is_string($id) )
            $id = trim($id);
    }
}