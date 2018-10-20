<?php
/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2016-06-20
 */
namespace OAuthBundle\OAuth\Response;

use OAuthBundle\OAuth\ResourceOwnerInterface;
use OAuthBundle\Security\Core\Authentication\Token\OAuthToken;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

abstract class AbstractUserResponse implements UserResponseInterface
{
    /**
     * @var array
     */
    protected $response;

    /**
     * @var ResourceOwnerInterface
     */
    protected $resourceOwner;

    /**
     * @var OAuthToken
     */
    protected $oAuthToken;

    /**
     * {@inheritdoc}
     */
    public function getEmail()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getProfilePicture()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getAccessToken()
    {
        return $this->oAuthToken->getAccessToken();
    }

    /**
     * {@inheritdoc}
     */
    public function getRefreshToken()
    {
        return $this->oAuthToken->getRefreshToken();
    }

    /**
     * {@inheritdoc}
     */
    public function getTokenSecret()
    {
        return $this->oAuthToken->getTokenSecret();
    }

    /**
     * {@inheritdoc}
     */
    public function getExpiresIn()
    {
        return $this->oAuthToken->getExpiresIn();
    }

    /**
     * {@inheritdoc}
     */
    public function setOAuthToken(OAuthToken $token)
    {
        $this->oAuthToken = $token;
    }

    /**
     * {@inheritdoc}
     */
    public function getOAuthToken()
    {
        return $this->oAuthToken;
    }

    /**
     * {@inheritdoc}
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * {@inheritdoc}
     */
    public function setResponse($response)
    {
        if (is_array($response)) {
            $this->response = $response;
        } else {
            // First check that response exists, due too bug: https://bugs.php.net/bug.php?id=54484
            if (!$response) {
                $this->response = array();
            } else {
                $this->response = json_decode($response, true);

                if (JSON_ERROR_NONE !== json_last_error()) {
                    throw new AuthenticationException('Response is not a valid JSON code.');
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getResourceOwner()
    {
        return $this->resourceOwner;
    }

    /**
     * {@inheritdoc}
     */
    public function setResourceOwner(ResourceOwnerInterface $resourceOwner)
    {
        $this->resourceOwner = $resourceOwner;
    }
}
