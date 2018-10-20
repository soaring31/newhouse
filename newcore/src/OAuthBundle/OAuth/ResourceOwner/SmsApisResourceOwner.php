<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年6月22日
*/
namespace OAuthBundle\OAuth\ResourceOwner;

use Buzz\Message\MessageInterface as HttpMessageInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SmsApisResourceOwner extends GenericOAuth2ResourceOwner
{
    /**
     * {@inheritDoc}
     */
    protected $paths = array(
        'oauthid'     => 'openid', //参数其实没有用
    );
    
    /**
     * 获取自定义接口数据
     * @param array $parameters 
     */
    public function getContent($extraParameters = array())
    {
        ksort($extraParameters);
        return $this->getResponseContent($this->doGetTokenRequest($this->options['url'], $extraParameters));
    }
    
    /**
     * Get the 'parsed' content based on the response headers.
     *
     * @param HttpMessageInterface $rawResponse
     *
     * @return array
     */
    protected function getResponseContent(HttpMessageInterface $rawResponse)
    {
        $content = $rawResponse->getContent();
        $content = trim($content); // BOM标记？空白行？
        if (!$content) { return ''; }
        // json
        if(substr($content,0,1)=='{'){
            $response = json_decode($content, true);
            if (JSON_ERROR_NONE !== json_last_error()) {
                parse_str($content, $response);
            }
            return $response; 
        }
        // xml
        if(substr($content,0,1)=='<'){
            $xml = @simplexml_load_string($content); 
            if(is_object($xml)) return $xml;
        }
        // html, text
        return $content;
    }

    /**
     * {@inheritDoc}
     */
    protected function configureOptions(OptionsResolverInterface $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults(array(
            'authorization_url' => '',
            'access_token_url'  => '',
            'infos_url'         => '',
        )); //参数其实没有用
    }

}