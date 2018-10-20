<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016-06-20
*/
namespace OAuthBundle\Security\Http;

use Symfony\Component\HttpFoundation\Request;
use OAuthBundle\OAuth\ResourceOwnerInterface;
use Symfony\Component\Security\Http\HttpUtils;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * ResourceOwnerMap. Holds several resource owners for a firewall. Lazy
 * loads the appropriate resource owner when requested.
 *
 * @author Alexander <iam.asm89@gmail.com>
 */
class ResourceOwnerMap implements ContainerAwareInterface
{
    /**
     * @var HttpUtils
     */
    protected $httpUtils;

    /**
     * @var array
     */
    protected $resourceOwners;

    /**
     * @var array
     */
    protected $possibleResourceOwners;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Constructor.
     *
     * @param HttpUtils $httpUtils              HttpUtils
     * @param array     $possibleResourceOwners Array with possible resource owners names.
     * @param array     $resourceOwners         Array with configured resource owners.
     */
    public function __construct(HttpUtils $httpUtils, array $possibleResourceOwners, $resourceOwners)
    {
        $this->httpUtils              = $httpUtils;
        $this->possibleResourceOwners = $possibleResourceOwners;
        $this->resourceOwners         = $resourceOwners;
    }

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Check that resource owner with given name exists.
     *
     * @param string $name
     *
     * @return bool
     */
    public function hasResourceOwnerByName($name)
    {
        return isset($this->resourceOwners[$name], $this->possibleResourceOwners[$name]);
    }

    /**
     * Gets the appropriate resource owner given the name.
     *
     * @param string $name
     *
     * @return null|ResourceOwnerInterface
     */
    public function getResourceOwnerByName($name)
    {
        if (!$this->hasResourceOwnerByName($name)) {
            return null;
        }

        return $this->container->get('oauth.resource_owner.'.$name);
    }

    /**
     * Gets the appropriate resource owner for a request.
     *
     * @param Request $request
     *
     * @return null|array
     */
    public function getResourceOwnerByRequest(Request $request)
    {
        foreach ($this->resourceOwners as $name => $checkPath) {
            if ($this->httpUtils->checkRequestPath($request, $checkPath)) {
                return array($this->getResourceOwnerByName($name), $checkPath);
            }
        }

        return null;
    }

    /**
     * Gets the check path for given resource name.
     *
     * @param string $name
     *
     * @return null|string
     */
    public function getResourceOwnerCheckPath($name)
    {
        if (isset($this->resourceOwners[$name])) {
            return $this->resourceOwners[$name];
        }

        return null;
    }

    /**
     * Get all the resource owners.
     *
     * @return array
     */
    public function getResourceOwners()
    {
        return $this->resourceOwners;
    }
}
