<?php
/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2016-06-20
 */
namespace OAuthBundle\OAuth\ResourceOwner;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BaiduMapResourceOwner extends GenericOAuth2ResourceOwner
{
    /**
     * {@inheritDoc}
     */
    protected $paths = array();

    /**
     * Override to set the Accept header as otherwise Yahoo defaults to XML
     *
     * {@inheritDoc}
     */
    public function doGetRequest($url, array $parameters = array(), $form = true)
    {
        if($form){
            return $this->uploadRequest($url, $parameters);
        }else{
            $result = $this->httpRequest($url.'?'.http_build_query($parameters, '', '&'), '', array(), 'get');
            return json_decode($result->getContent());
        }
    }

    protected function configureOptions(OptionsResolverInterface $resolver)
    {
        parent::configureOptions($resolver);  
        $resolver->setDefaults(array(
            'authorization_url' => 'http://api.map.baidu.com/geosearch/v3/nearby',
            'access_token_url'  => 'http://api.map.baidu.com/geosearch/v3/nearby',
            'infos_url'         => 'http://api.map.baidu.com/geosearch/v3/nearby',
        ));
    }
}
