<?php
/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2016-06-20
 */
namespace OAuthBundle\OAuth\Response;

use OAuthBundle\OAuth\ResponseInterface;
use OAuthBundle\Security\Core\Authentication\Token\OAuthToken;

interface UserResponseInterface extends ResponseInterface
{
    /**
     * Get the unique user identifier.
     *
     * Note that this is not always common known "username" because of implementation
     * in Symfony2 framework. For more details follow link below.
     * @link https://github.com/symfony/symfony/blob/2.1/src/Symfony/Component/Security/Core/User/UserProviderInterface.php#L20-L28
     *
     * @return string
     */
    public function getUsername();

    /**
     * Get the username to display.
     *
     * @return string
     */
    public function getNickname();

    /**
     * Get the first name of user.
     *
     * @return null|string
     */
    public function getFirstName();

    /**
     * Get the last name of user.
     *
     * @return null|string
     */
    public function getLastName();

    /**
     * Get the real name of user.
     *
     * @return null|string
     */
    public function getRealName();

    /**
     * Get the email address.
     *
     * @return null|string
     */
    public function getEmail();

    /**
     * Get the url to the profile picture.
     *
     * @return null|string
     */
    public function getProfilePicture();

    /**
     * Get the access token used for the request.
     *
     * @return string
     */
    public function getAccessToken();

    /**
     * Get the access token used for the request.
     *
     * @return null|string
     */
    public function getRefreshToken();

    /**
     * Get oauth token secret used for the request.
     *
     * @return null|string
     */
    public function getTokenSecret();

    /**
     * Get the info when token will expire.
     *
     * @return null|string
     */
    public function getExpiresIn();

    /**
     * Set the raw token data from the request.
     *
     * @param OAuthToken $token
     */
    public function setOAuthToken(OAuthToken $token);

    /**
     * Get the raw token data from the request.
     *
     * @return OAuthToken
     */
    public function getOAuthToken();
}
