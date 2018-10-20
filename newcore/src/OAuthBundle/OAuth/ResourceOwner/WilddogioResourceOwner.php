<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年3月15日
*/
namespace OAuthBundle\OAuth\ResourceOwner;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class WilddogioResourceOwner extends GenericOAuth2ResourceOwner
{
    /**
     * {@inheritDoc}
     */
    protected $paths = array(
        'oauthid'     => 'openid',
        'nickname'       => 'nickname',
        'realname'       => 'nickname',
        'profilepicture' => 'figureurl_qq_1',
    );
    
    /**
     * {@inheritDoc}
     * https://openapi.alipay.com/gateway.do?_input_charset=utf-8&partner=2088002045835984&return_url=http%3A%2F%2Fwww.gz269.com%2Fapp_dev.php%2Fmanage%2Foauth%2Fcheckalipay&service=alipay.auth.authorize&target_service=user.auth.quick.login&sign=6418f3f12e8580ae2090c26eb5203461&sign_type=MD5
     * https://openapi.alipay.com/gateway.do?_input_charset=utf-8&partner=2016062101539205&return_url=http%3A%2F%2Fwww.gz269.com%2Fapp_dev.php%2Fmanage%2Foauth%2Fcheckalipay&&sign=b5019f2c1044d2143e685e9e30f7cf11&sign_type=MD5
     */
    protected function configureOptions(OptionsResolverInterface $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults(array(
            'authorization_url' => 'https://andayyy.wilddogio.com/type2.json',
            'access_token_url'  => 'https://openapi.alipay.com/gateway.do',
            'infos_url'         => 'https://openapi.alipay.com/gateway.do',
            'infodata_url'      => 'https://andayyy.wilddogio.com/type.json',
        ));
    }
    
    private function requestUserIdentifier(array $accessToken = null)
    {
        $url = $this->normalizeUrl($this->options['me_url'], array(
            'access_token' => $accessToken['access_token'],
        ));
    
        $response = $this->httpRequest($url);
        $content = $this->getResponseContent($response);
    
        if (!isset($content['openid'])) {
            throw new AuthenticationException();
        }
    
        return $content['openid'];
    }
    
    /**
     * 获取自定义接口数据
     * @param array $parameters
     */
    public function getToUrl($url, $extraParameters)
    {
        return $this->doGetTokenRequest($url, $extraParameters);
    }
    
    public function getTest()
    {
        $response = $this->httpRequest($this->options['authorization_url']);
        $content = $this->getResponseContent($response);
        dump($content);die();
        return 'aa';
    }
    public function setTest()
    {
        $data = array();
        $data['type'] = 1;
        $data['name'] = 'test';
        
        $response = $this->httpRequest($this->options['infodata_url'], json_encode($data), array(), 'PUT');
        //$content = $this->getResponseContent($response);
        dump($response);die();
        return 'aa';
    }
}