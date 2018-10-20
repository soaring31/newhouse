<?php
/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2016-06-20
 */
namespace OAuthBundle\OAuth\ResourceOwner;

use Buzz\Message\MessageInterface as HttpMessageInterface;
use OAuthBundle\Security\Core\Authentication\Token\OAuthToken;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * QQ互联
 * @author Administrator
 *
 */
class QQResourceOwner extends GenericOAuth2ResourceOwner
{
    /**
     * {@inheritDoc}
     */
    protected $paths = array(
        'oauthid' => 'openid',
        'nickname'  => 'nickname',
        'realname'  => 'nickname',
        'email' => 'email',
        'headimage' => 'figureurl_qq_1',
        'location'  => array('province','city'),
    );

    /**
     * {@inheritDoc}
     */
    public function getResponseContent(HttpMessageInterface $rawResponse)
    {
        $content = $rawResponse->getContent();
        $matches = array();
        if (preg_match('/^callback\((.+)\);$/', $content, $matches)) {
            $rawResponse->setContent(trim($matches[1]));
        }

        return parent::getResponseContent($rawResponse);
    }
    
    /**
     * 获取腾讯微博登录用户的用户资料。
     * @param array $accessToken
     * @param array $extraParameters
     */
    public function getInformation(array $accessToken = null, array $extraParameters = array())
    {
        $openid = isset($extraParameters['openid']) ? $extraParameters['openid'] : $this->requestUserIdentifier($accessToken);
        
        $url = $this->normalizeUrl($this->options['info_url'], array(
            'oauth_consumer_key' => $this->options['client_id'],
            'access_token'       => $accessToken['access_token'],
            'openid'             => $openid,
            'format'             => 'json',
        ));

        return $this->getResponseContent($this->doGetUserInformationRequest($url));
    }

    /**
     * 获取相册列表。
     * @param array $accessToken
     * @param array $extraParameters
     */
    public function getListalbum(array $accessToken = null, array $extraParameters = array())
    {
        $openid = isset($extraParameters['openid']) ? $extraParameters['openid'] : $this->requestUserIdentifier($accessToken);
        
        $url = $this->normalizeUrl($this->options['list_album'], array(
            'oauth_consumer_key' => $this->options['client_id'],
            'access_token'       => $accessToken['access_token'],
            'openid'             => $openid,
            'format'             => 'json',
        ));

        return $this->getResponseContent($this->doGetUserInformationRequest($url));
    }

    /**
     * 上传相片。
     * @param array $accessToken
     * @param array $extraParameters
     */
    public function UploadPic(array $accessToken = null, array $extraParameters = array())
    {
        $openid = isset($extraParameters['openid']) ? $extraParameters['openid'] : $this->requestUserIdentifier($accessToken);
        
        $url = $this->normalizeUrl($this->options['upload_pic'], array(
            'oauth_consumer_key' => $this->options['client_id'],
            'access_token'       => $accessToken['access_token'],
            'openid'             => $openid,
            'format'             => 'json',
        ));

        return $this->getResponseContent($this->doGetUserInformationRequest($url));
    }

    /**
     * 获取微博听众列表。
     * @param array $accessToken
     * @param array $extraParameters
     */
    public function getFanslist(array $accessToken = null, array $extraParameters = array())
    {
        $openid = isset($extraParameters['openid']) ? $extraParameters['openid'] : $this->requestUserIdentifier($accessToken);
        
        $url = $this->normalizeUrl($this->options['get_fanslist'], array(
            'oauth_consumer_key' => $this->options['client_id'],
            'access_token'       => $accessToken['access_token'],
            'openid'             => $openid,
            'format'             => 'json',
            'reqnum'             => isset($extraParameters['reqnum']) ? $extraParameters['reqnum'] : 10,
            'startindex'         => isset($extraParameters['startindex']) ? $extraParameters['startindex'] : 0,
        ));

        return $this->getResponseContent($this->doGetUserInformationRequest($url));
    }

    /**
     * 获取财付通收货地址。
     * @param array $accessToken
     * @param array $extraParameters
     */
    public function getTenpayaddr(array $accessToken = null, array $extraParameters = array())
    {
        $openid = isset($extraParameters['openid']) ? $extraParameters['openid'] : $this->requestUserIdentifier($accessToken);
        
        $url = $this->normalizeUrl($this->options['get_tenpay_addr'], array(
            'oauth_consumer_key' => $this->options['client_id'],
            'access_token'       => $accessToken['access_token'],
            'openid'             => $openid,
            'format'             => 'json',
            'ver'                => isset($extraParameters['ver']) ? $extraParameters['ver'] : 1,
            'limit'              => isset($extraParameters['limit']) ? $extraParameters['limit'] : 5,
        ));

        return $this->getResponseContent($this->doGetUserInformationRequest($url));
    }

    /**
     * {@inheritDoc}
     */
    public function getUserInformation(array $accessToken = null, array $extraParameters = array())
    {
        $openid = isset($extraParameters['openid']) ? $extraParameters['openid'] : $this->requestUserIdentifier($accessToken);

        $url = $this->normalizeUrl($this->options['infos_url'], array(
            'oauth_consumer_key' => $this->options['client_id'],
            'access_token'       => $accessToken['access_token'],
            'openid'             => $openid,
            'format'             => 'json',
        ));

        $response = $this->doGetUserInformationRequest($url);
        $content = $this->getResponseContent($response);

        // Custom errors:
        if (isset($content['ret']) && 0 === $content['ret']) {
            $content['openid'] = $openid;
        } else {
            throw new AuthenticationException(sprintf('OAuth error: %s', isset($content['ret']) ? $content['msg'] : 'invalid response'));
        }

        $response = $this->getUserResponse();
        $response->setResponse($content);
        $response->setResourceOwner($this);
        $response->setOAuthToken(new OAuthToken($accessToken));

        return $response;
    }
    
    /**
     * 发表一条微博信息（纯文本）到腾讯微博平台上。
     * 注意连续两次发布的微博内容不可以重复。
     * @param array $accessToken
     * @param array $extraParameters
     */
    public function addWeiBoMsg(array $accessToken = null, array $extraParameters = array())
    {
        $extraParameters['openid'] = isset($extraParameters['openid']) ? $extraParameters['openid'] : $this->requestUserIdentifier($accessToken);

        $extraParameters['access_token'] = $accessToken['access_token'];
        $extraParameters['oauth_consumer_key'] = $this->options['client_id'];
        $extraParameters['format'] = isset($extraParameters['format'])?$extraParameters['format']:'json';

        $url = $this->normalizeUrl($this->options['add_t_url'], $extraParameters);
        
        return $this->getResponseContent($this->doGetUserInformationRequest($url));
    }
    
    /**
     * 根据微博ID删除指定微博。
     * @param array $accessToken
     * @param array $extraParameters
     */
    public function delWeiBoMsg(array $accessToken = null, array $extraParameters = array())
    {
        $extraParameters['openid'] = isset($extraParameters['openid']) ? $extraParameters['openid'] : $this->requestUserIdentifier($accessToken);
    
        $extraParameters['access_token'] = $accessToken['access_token'];
        $extraParameters['oauth_consumer_key'] = $this->options['client_id'];
        $extraParameters['format'] = isset($extraParameters['format'])?$extraParameters['format']:'json';
    
        $url = $this->normalizeUrl($this->options['del_t_url'], $extraParameters);
    
        return $this->getResponseContent($this->doGetUserInformationRequest($url));
    }
    
    /**
     * 上传一张图片，并发布一条消息到腾讯微博平台上。
     * @param array $accessToken
     * @param array $extraParameters
     */
    public function addWeiBoPic(array $accessToken = null, array $extraParameters = array())
    {
        $extraParameters['openid'] = isset($extraParameters['openid']) ? $extraParameters['openid'] : $this->requestUserIdentifier($accessToken);
        
        $extraParameters['access_token'] = $accessToken['access_token'];
        $extraParameters['oauth_consumer_key'] = $this->options['client_id'];
        $extraParameters['format'] = isset($extraParameters['format'])?$extraParameters['format']:'json';
        
        $url = $this->normalizeUrl($this->options['add_pic_t_url'], $extraParameters);
        
        return $this->getResponseContent($this->doGetUserInformationRequest($url));
    }

    /**
     * 创建相册
     * @param array $accessToken
     * @param array $extraParameters
     */
    public function addAlbum(array $accessToken = null, array $extraParameters = array())
    {
        $extraParameters['openid'] = isset($extraParameters['openid']) ? $extraParameters['openid'] : $this->requestUserIdentifier($accessToken);
        
        $extraParameters['access_token'] = $accessToken['access_token'];
        $extraParameters['oauth_consumer_key'] = $this->options['client_id'];
        $extraParameters['format'] = isset($extraParameters['format'])?$extraParameters['format']:'json';
        
        $url = $this->normalizeUrl($this->options['add_album'], $extraParameters);
        
        return $this->getResponseContent($this->doGetUserInformationRequest($url));
    }

    /**
     * 发表一条空间说说
     * @param array $accessToken
     * @param array $extraParameters
     */
    public function addToPic(array $accessToken = null, array $extraParameters = array())
    {
        $extraParameters['openid'] = isset($extraParameters['openid']) ? $extraParameters['openid'] : $this->requestUserIdentifier($accessToken);
        
        $extraParameters['access_token'] = $accessToken['access_token'];
        $extraParameters['oauth_consumer_key'] = $this->options['client_id'];
        $extraParameters['format'] = isset($extraParameters['format'])?$extraParameters['format']:'json';
        
        $url = $this->normalizeUrl($this->options['add_topic'], $extraParameters);
        
        return $this->getResponseContent($this->doGetUserInformationRequest($url));
    }

    /**
     * 发表一条空间日志
     * @param array $accessToken
     * @param array $extraParameters
     */
    public function addBlog(array $accessToken = null, array $extraParameters = array())
    {
        $extraParameters['openid'] = isset($extraParameters['openid']) ? $extraParameters['openid'] : $this->requestUserIdentifier($accessToken);
        
        $extraParameters['access_token'] = $accessToken['access_token'];
        $extraParameters['oauth_consumer_key'] = $this->options['client_id'];
        $extraParameters['format'] = isset($extraParameters['format'])?$extraParameters['format']:'json';
        
        $url = $this->normalizeUrl($this->options['add_blog'], $extraParameters);
        
        return $this->getResponseContent($this->doGetUserInformationRequest($url));
    }

    /**
     * 添加分享
     * @param array $accessToken
     * @param array $extraParameters
     */
    public function addShare(array $accessToken = null, array $extraParameters = array())
    {
        $extraParameters['openid'] = isset($extraParameters['openid']) ? $extraParameters['openid'] : $this->requestUserIdentifier($accessToken);
        
        $extraParameters['access_token'] = $accessToken['access_token'];
        $extraParameters['oauth_consumer_key'] = $this->options['client_id'];
        $extraParameters['format'] = isset($extraParameters['format'])?$extraParameters['format']:'json';
        
        $url = $this->normalizeUrl($this->options['add_share'], $extraParameters);
        
        return $this->getResponseContent($this->doGetUserInformationRequest($url));
    }

    /**
     * 收听某个用户
     * @param array $accessToken
     * @param array $extraParameters
     */
    public function addIdol(array $accessToken = null, array $extraParameters = array())
    {
        $extraParameters['openid'] = isset($extraParameters['openid']) ? $extraParameters['openid'] : $this->requestUserIdentifier($accessToken);
        
        $extraParameters['access_token'] = $accessToken['access_token'];
        $extraParameters['oauth_consumer_key'] = $this->options['client_id'];
        $extraParameters['format'] = isset($extraParameters['format'])?$extraParameters['format']:'json';
        
        $url = $this->normalizeUrl($this->options['add_idol'], $extraParameters);
        
        return $this->getResponseContent($this->doGetUserInformationRequest($url));
    }
    
    /**
     * 获取一条微博的转播或评论信息列表。
     * @param array $accessToken
     * @param array $extraParameters
     */
    public function getRepostList(array $accessToken = null, array $extraParameters = array())
    {
        $extraParameters['openid'] = isset($extraParameters['openid']) ? $extraParameters['openid'] : $this->requestUserIdentifier($accessToken);
        
        $extraParameters['access_token'] = $accessToken['access_token'];
        $extraParameters['oauth_consumer_key'] = $this->options['client_id'];
        $extraParameters['format'] = isset($extraParameters['format'])?$extraParameters['format']:'json';
        
        $url = $this->normalizeUrl($this->options['repost_url'], $extraParameters);
        
        return $this->getResponseContent($this->doGetUserInformationRequest($url));
    }

    /**
     * {@inheritDoc}
     */
    protected function configureOptions(OptionsResolverInterface $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults(array(
            'authorization_url' => 'https://graph.qq.com/oauth2.0/authorize?format=json',
            'access_token_url'  => 'https://graph.qq.com/oauth2.0/token',
            'info_url'          => 'https://graph.qq.com/user/get_info',
            'infos_url'         => 'https://graph.qq.com/user/get_user_info',
            'me_url'            => 'https://graph.qq.com/oauth2.0/me',
            'add_t_url'         => 'https://graph.qq.com/t/add_t',
            'del_t_url'         => 'https://graph.qq.com/t/del_t',
            'add_pic_t_url'     => 'https://graph.qq.com/t/add_pic_t',
            'repost_url'        => 'https://graph.qq.com/t/get_repost_list',
            'add_topic'         => 'https://graph.qq.com/shuoshuo/add_topic',
            'add_blog'          => 'https://graph.qq.com/blog/add_one_blog',
            'add_share'         => 'https://graph.qq.com/share/add_share',
            'list_album'        => 'https://graph.qq.com/photo/list_album',
            'add_album'         => 'https://graph.qq.com/photo/add_album',
            'upload_pic'        => 'https://graph.qq.com/photo/upload_pic',
            'get_fanslist'      => 'https://graph.qq.com/relation/get_fanslist',
            'get_idollist'      => 'https://graph.qq.com/relation/get_idollist',
            'add_idol'          => 'https://graph.qq.com/relation/add_idol',
            'get_tenpay_addr'   => 'https://graph.qq.com/cft_info/get_tenpay_addr',
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
}
