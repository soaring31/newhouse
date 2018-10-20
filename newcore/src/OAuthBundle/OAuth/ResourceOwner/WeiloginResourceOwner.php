<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年5月11日
*/
namespace OAuthBundle\OAuth\ResourceOwner;

use Symfony\Component\HttpFoundation\Request;
use OAuthBundle\Security\Core\Authentication\Token\OAuthToken;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class WeiloginResourceOwner extends GenericOAuth2ResourceOwner
{
    /**
     * {@inheritDoc}
     */
    protected $paths = array(
        'oauthid'     => 'openid',
        'nickname'       => 'nickname',
        'realname'       => 'nickname',
        'profilepicture' => 'headimgurl',
    );
    
    public function getAccessToken(Request $request, $redirectUri, array $extraParameters = array())
    {
    
        $parameters = array_merge(array(
            'appid'      => $this->options['client_id'],
            'secret'     => $this->options['client_secret'],
            'code'       => $request->query->get('code',''),
            'grant_type' => 'authorization_code',
        ), $extraParameters);
    
        //https://mp.weixin.qq.com/cgi-bin/componentloginpage?component_appid=wx304d07ec91ce3500
        //&pre_auth_code=preauthcode@@@qL9MKId_ts-0F266BM-LMC0HoIILcy_zH7iQns1-hZSiV8uUQa009cJD7Ti8H50a
        //&redirect_uri=http://www.135plat.com/weixin/plogin
        $response = $this->doGetTokenRequest($this->options['access_token_url'], $parameters);

        $response = $this->getResponseContent($response);
    
        $this->validateResponseContent($response);

        return $response;
    }
    
    public function getClientAccessToken(array $extraParameters = array())
    {
        $parameters = array_merge(array(
            'appid'      => $this->options['client_id'],
            'secret'     => $this->options['client_secret'],
            'grant_type' => 'client_credential',
        ), $extraParameters);
    
        ksort($parameters);
    
    
        return $this->getResponseContent($this->doGetTokenRequest('https://api.weixin.qq.com/cgi-bin/token', $parameters));
    }
    
    /**
     * {@inheritDoc}
     */
    public function getAuthorizationUrl($redirectUri, array $extraParameters = array())
    {
        $parameters = array_merge(array(
            'appid'         => $this->options['client_id'],
            'redirect_uri'  => $redirectUri,
            'response_type' => 'code',
            'scope'         => $this->options['scope'] ? $this->options['scope'] : 'snsapi_login',//'snsapi_login',
            'state'         => $this->state ? urlencode($this->state) : '',
        ), $extraParameters);
    
        ksort($parameters); // i don't know why, but the order of the parameters REALLY matters
    
        return $this->normalizeUrl($this->options['authorization_url'], $parameters)."#wechat_redirect";
    }
    
    /**
     * {@inheritDoc}
     */
    public function getUserInformation(array $accessToken = null, array $extraParameters = array())
    {
        
        //$accessToken = $this->getResponseContent($accessToken['access_token']);
        // if ('snsapi_userinfo' === $this->options['scope']) {
        //     $openid = $accessToken['openid'];
    
        //     $url = $this->normalizeUrl($this->options['infos_url'], array(
        //         'access_token' => $accessToken['access_token'],
        //         'openid'       => $openid,
        //     ));
    
        //     $response = $this->doGetUserInformationRequest($url);
        //     $content = $this->getResponseContent($response);
        // } else {
        //     $content = array(
        //         'openid' => $accessToken['openid'],
        //     );
        // }
    
        $openid = $accessToken['openid'];
        $url = $this->normalizeUrl($this->options['infos_url'], array(
            'access_token' => $accessToken['access_token'],
            'openid'       => $openid,
        ));
        
    
        
        $response = $this->doGetUserInformationRequest($url);
        $content = $this->getResponseContent($response);
        $this->validateResponseContent($content);
    
        $response = $this->getUserResponse();
        $response->setResponse($content);
        $response->setResourceOwner($this);
        $response->setOAuthToken(new OAuthToken($accessToken));

        return $response;
    }
    
    /**
     * 获取预授权码
     * @param int $suiteid
     * @param string $suite_access_token
     * @return number
     */
    public function getPreAuthCode($accessToken)
    {
        if (empty ( $accessToken ))
            return 0;
    
        //缓存key
        $key = 'pre_auth_code_' . $accessToken;
    
        $res = $this->get('core.common')->S($key);
        if ($res !== false)
            return $res;
    
        $param = array();
        $param ['component_appid']= $this->options['client_id'];
        $url = 'https://api.weixin.qq.com/cgi-bin/component/api_create_preauthcode?component_access_token='.$accessToken;
        $res = $this->getResponseContent($this->doGetTokenRequest($url, $param));
        if (@array_key_exists ( 'pre_auth_code', $res ))
        {
            //存入缓存
            $this->get('core.common')->S($key, $res['pre_auth_code'], $res['expires_in']);
    
            return $res['pre_auth_code'];
        }
    
        return 0;
    }
    
    /**
     * {@inheritDoc}
     */
    protected function configureOptions(OptionsResolverInterface $resolver)
    {
        parent::configureOptions($resolver);
    
        $resolver->setDefaults(array(
            'authorization_url' => 'https://open.weixin.qq.com/connect/qrconnect',
            'access_token_url'  => 'https://api.weixin.qq.com/sns/oauth2/access_token',
            'infos_url'         => 'https://api.weixin.qq.com/sns/userinfo',
        ));
    }
    
    /**
     * {@inheritDoc}
     */
    protected function validateResponseContent($response)
    {
        if (isset($response['errmsg'])) {
            throw new AuthenticationException(sprintf('OAuth error: "%s"', $response['errmsg']));
        }
    }
}