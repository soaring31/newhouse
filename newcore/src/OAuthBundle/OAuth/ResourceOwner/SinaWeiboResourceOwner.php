<?php
/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2016-06-20
 */
namespace OAuthBundle\OAuth\ResourceOwner;

use OAuthBundle\Security\Core\Authentication\Token\OAuthToken;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SinaWeiboResourceOwner extends GenericOAuth2ResourceOwner
{
    /**
     * {@inheritDoc}2996276163 m
     */
    protected $paths = array(
        'oauthid'   => 'id',
        'nickname'  => 'screen_name',
        'realname'  => 'screen_name',
        'email'     => 'email',
        'headimage' => 'profile_image_url',
        'location'  => 'location',
    );

    /**
     * {@inheritDoc}
     */
    public function getUserInformation(array $accessToken = null, array $extraParameters = array())
    {
        $url = $this->normalizeUrl($this->options['infos_url'], array(
            'access_token' => $accessToken['access_token'],
            'uid'          => $accessToken['uid'],
        ));

        $content = $this->doGetUserInformationRequest($url)->getContent();

        $response = $this->getUserResponse();
        $response->setResponse($content);
        $response->setResourceOwner($this);
        $response->setOAuthToken(new OAuthToken($accessToken));

        return $response;
    }

    /**
     * {@inheritDoc}
     */
    protected function configureOptions(OptionsResolverInterface $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults(array(
            'authorization_url' => 'https://api.weibo.com/oauth2/authorize',
            'access_token_url'  => 'https://api.weibo.com/oauth2/access_token',
            'infos_url'         => 'https://api.weibo.com/2/users/show.json',
        ));
    }
}
