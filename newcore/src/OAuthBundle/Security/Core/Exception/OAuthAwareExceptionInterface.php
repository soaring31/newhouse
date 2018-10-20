<?php
/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2016-06-20
 */
namespace OAuthBundle\Security\Core\Exception;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

interface OAuthAwareExceptionInterface
{
    /**
     * Get the access token information.
     *
     * @return string
     */
    public function getAccessToken();

    /**
     * Get the raw version of received token.
     *
     * @return array
     */
    public function getRawToken();

    /**
     * Get the refresh token information.
     *
     * @return null|string
     */
    public function getRefreshToken();

    /**
     * Get the info when token will expire.
     *
     * @return null|integer
     */
    public function getExpiresIn();

    /**
     * Get the oauth secret token 
     * 
     * @return null|string
     */
    public function getTokenSecret();

    /**
     * Set the token.
     *
     * @param TokenInterface $token
     */
    public function setToken(TokenInterface $token);

    /**
     * Set the name of the resource owner responsible for the oauth authentication.
     *
     * @param string $resourceOwnerName
     */
    public function setResourceOwnerName($resourceOwnerName);

    /**
     * Get the name of resource owner.
     *
     * @return string
     */
    public function getResourceOwnerName();
}
