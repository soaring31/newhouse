<?php
/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2016-06-20
 */
namespace OAuthBundle\OAuth\ResourceOwner;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class YahooResourceOwner extends GenericOAuth1ResourceOwner
{
    /**
     * {@inheritDoc}
     */
    protected $paths = array(
        'oauthid' => 'profile.guid',
        'nickname'   => 'profile.nickname',
        'realname'   => 'profile.givenName',
    );

    /**
     * Override to replace {guid} in the infos_url with the authenticating user's yahoo id
     *
     * {@inheritDoc}
     */
    public function getUserInformation(array $accessToken, array $extraParameters = array())
    {
        $this->options['infos_url'] = str_replace('{guid}', $accessToken['xoauth_yahoo_guid'], $this->options['infos_url']);

        return parent::getUserInformation($accessToken, $extraParameters);
    }

    /**
     * Override to set the Accept header as otherwise Yahoo defaults to XML
     *
     * {@inheritDoc}
     */
    protected function doGetUserInformationRequest($url, array $parameters = array())
    {
        return $this->httpRequest($url, null, $parameters, array('Accept: application/json'));
    }

    /**
     * {@inheritDoc}
     */
    protected function configureOptions(OptionsResolverInterface $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults(array(
            'authorization_url' => 'https://api.login.yahoo.com/oauth/v2/request_auth',
            'request_token_url' => 'https://api.login.yahoo.com/oauth/v2/get_request_token',
            'access_token_url'  => 'https://api.login.yahoo.com/oauth/v2/get_token',
            'infos_url'         => 'https://social.yahooapis.com/v1/user/{guid}/profile',

            'realm'             => 'yahooapis.com',
        ));
    }
}
