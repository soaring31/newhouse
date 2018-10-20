<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016-06-20
*/
namespace OAuthBundle\OAuth\RequestDataStorage;

use OAuthBundle\OAuth\ResourceOwnerInterface;
use OAuthBundle\OAuth\RequestDataStorageInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionStorage implements RequestDataStorageInterface
{
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * {@inheritDoc}
     */
    public function fetch(ResourceOwnerInterface $resourceOwner, $key, $type = 'token')
    {
        $key = $this->generateKey($resourceOwner, $key, $type);
        if (null === $token = $this->session->get($key)) {
            throw new \InvalidArgumentException('No data available in storage.');
        }

        // request tokens are one time use only
        $this->session->remove($key);

        return $token;
    }

    /**
     * {@inheritDoc}
     */
    public function save(ResourceOwnerInterface $resourceOwner, $value, $type = 'token')
    {
        if ('token' === $type) {
            if (!is_array($value) || !isset($value['oauth_token'])) {
                throw new \InvalidArgumentException('Invalid request token.');
            }

            $key = $this->generateKey($resourceOwner, $value['oauth_token'], 'token');
        } else {
            $key = $this->generateKey($resourceOwner, is_array($value) ? reset($value) : $value, $type);
        }

        $this->session->set($key, $value);
    }

    /**
     * {@inheritDoc}
     */
    public function get_csrf(ResourceOwnerInterface $resourceOwner, $type = 'token')
    {
        $key = $this->generateKey($resourceOwner, '', $type);
        return $this->session->get($key);
    }

    /**
     * Key to for fetching or saving a token.
     *
     * @param ResourceOwnerInterface $resourceOwner
     * @param string                 $key
     * @param string                 $type
     *
     * @return string
     */
    protected function generateKey(ResourceOwnerInterface $resourceOwner, $key, $type)
    {
        return sprintf('_oauth.%s.%s.%s', $resourceOwner->getName(), $resourceOwner->getOption('client_id'), $type);
    }
}
