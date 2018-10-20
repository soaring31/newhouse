<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016-06-20
*/
namespace OAuthBundle\OAuth\ResourceOwner;

use Buzz\Message\Form\FormUpload;
use Buzz\Message\Form\FormRequest;
use Buzz\Exception\ClientException;
use CoreBundle\Services\ServiceBase;
use Buzz\Message\Request as HttpRequest;
use Buzz\Message\Response as HttpResponse;
use OAuthBundle\OAuth\ResourceOwnerInterface;
use Symfony\Component\Security\Http\HttpUtils;
use OAuthBundle\OAuth\RequestDataStorageInterface;
use OAuthBundle\OAuth\Response\UserResponseInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Buzz\Client\ClientInterface as HttpClientInterface;
use OAuthBundle\OAuth\Exception\HttpTransportException;
use Buzz\Message\MessageInterface as HttpMessageInterface;
use Buzz\Message\RequestInterface as HttpRequestInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

abstract class AbstractResourceOwner extends ServiceBase implements ResourceOwnerInterface
{
    /**
     * @var array
     */
    protected $options = array();

    /**
     * @var array
     */
    protected $paths = array();

    /**
     * @var HttpClientInterface
     */
    protected $httpClient;

    /**
     * @var HttpUtils
     */
    protected $httpUtils;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $state;

    /**
     * @var RequestDataStorageInterface
     */
    public $storage;

    /**
     * @param HttpClientInterface         $httpClient Buzz http client
     * @param HttpUtils                   $httpUtils  Http utils
     * @param array                       $options    Options for the resource owner
     * @param string                      $name       Name for the resource owner
     * @param RequestDataStorageInterface $storage    Request token storage
     */
    public function __construct(HttpClientInterface $httpClient, HttpUtils $httpUtils, array $options, $name, RequestDataStorageInterface $storage, ContainerInterface $container)
    {
        $this->httpClient = $httpClient;
        $this->httpUtils  = $httpUtils;
        $this->name       = $name;
        $this->storage    = $storage;

        $this->container = $container;
        
        $mapId = $mapKey = array();
        switch ($name)
        {
            //qq快捷登录，从数据库里获取配置
            case 'qq':
                $mapId['name'] = 'appid';
                $mapKey['name'] = 'appkey';
                break;

            //微博快捷登录
            case 'weibo':
                $mapId['name'] = 'app_key';
                $mapKey['name'] = 'app_secret';
                break;
                
            //微信快捷登录
            case 'weixin':
                $mapId['name'] = 'wxappid';
                $mapKey['name'] = 'wxapp_secret';
                break;
                
            //微信快捷登录
            case 'weilogin':
            case 'wechat':
                $mapId['name'] = 'mwxappid';
                $mapKey['name'] = 'mwxapp_secret';
                break;
        }
        if (!empty($mapKey) && !empty($mapKey))
        {
            $map = array();
            $map['area'] = 0;
            $map['ename'] = 'mloginset';
            $mconfig = $this->get('db.mconfig')->getData($map);            

            $options['client_id'] = isset($mconfig[$mapId['name']])?$mconfig[$mapId['name']]['value']:$options['client_id'];
            $options['client_secret'] = isset($mconfig[$mapKey['name']])?$mconfig[$mapKey['name']]['value']:$options['client_secret'];
        }

        if (!empty($options['paths'])) {
            $this->addPaths($options['paths']);
        }
        unset($options['paths']);

        if (!empty($options['options'])) {
            $options += $options['options'];
            unset($options['options']);
        }
        unset($options['options']);
        
        $this->state = 'inint';//md5(date('Ymd'));

        // Resolve merged options
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $options = $resolver->resolve($options);
        $this->options = $options;

        $this->configure();

        //控制器初始化
        if(method_exists($this,'_initialize'))
            $this->_initialize();
    }


    /**
     * 获取自定义接口数据
     * @param array $parameters
     */
    public function getMeUrl($extraParameters = array())
    {
        return $this->getResponseContent($this->doGetTokenRequest($this->options['me_url'], $extraParameters));
    }

    /**
     * Gives a chance for extending providers to customize stuff
     */
    public function configure()
    {

    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * {@inheritDoc}
     */
    public function setOption($options)
    {
        $this->options = array_merge($this->options, $options);
    }

    /**
     * {@inheritDoc}
     * @param $name : 键值，为空返回所有
     */
    public function getOption($name='')
    {
        if(empty($name)) return $this->options;
        if (!array_key_exists($name, $this->options))
            throw new \InvalidArgumentException(sprintf('Unknown option "%s"', $name));

        return $this->options[$name];
    }

    /**
     * Add extra paths to the configuration.
     *
     * @param array $paths
     */
    public function addPaths(array $paths)
    {
        $this->paths = array_merge($this->paths, $paths);
    }

    /**
     * Refresh an access token using a refresh token.
     *
     * @param string $refreshToken    Refresh token
     * @param array  $extraParameters An array of parameters to add to the url
     *
     * @return array Array containing the access token and it's 'expires_in' value,
     *               along with any other parameters returned from the authentication
     *               provider.
     *
     * @throws AuthenticationException If an OAuth error occurred or no access token is found
     */
    public function refreshAccessToken($refreshToken, array $extraParameters = array())
    {
        throw new AuthenticationException('OAuth error: "Method unsupported."');
    }

    /**
     * Revoke an OAuth access token or refresh token.
     *
     * @param string $token The token (access token or a refresh token) that should be revoked.
     *
     * @return Boolean Returns True if the revocation was successful, otherwise False.
     *
     * @throws AuthenticationException If an OAuth error occurred
     */
    public function revokeToken($token)
    {
        throw new AuthenticationException('OAuth error: "Method unsupported."');
    }

    /**
     * Get the response object to return.
     *
     * @return UserResponseInterface
     */
    protected function getUserResponse()
    {
        $response = new $this->options['user_response_class'];
        if ($response instanceof UserResponseInterface) {
            $response->setPaths($this->paths);
        }

        return $response;
    }

    /**
     * @param string $url
     * @param array  $parameters
     *
     * @return string
     */
    protected function normalizeUrl($url, array $parameters = array())
    {
        $normalizedUrl = $url;
        if (!empty($parameters)) {
            $normalizedUrl .= (false !== strpos($url, '?') ? '&' : '?').http_build_query($parameters, '', '&');
        }

        return $normalizedUrl;
    }

    /**
     * Performs an HTTP request
     *
     * @param string $url           The url to fetch
     * @param string|array $content The content of the request
     * @param array  $headers       The headers of the request
     * @param string $method        The HTTP method to use
     *
     * @return HttpResponse The response content
     */
    public function httpRequest($url, $content = null, $headers = array(), $method = null)
    {
        if (null === $method) {
            $method = null === $content || '' === $content ? HttpRequestInterface::METHOD_GET : HttpRequestInterface::METHOD_POST;
        }

        $request  = new HttpRequest($method, $url);
        $response = new HttpResponse();

        $contentLength = 0;
        if (is_string($content)) {
            $contentLength = strlen($content);
        } elseif (is_array($content)) {
            $contentLength = strlen(implode('', $content));
        }

        $headers = array_merge(
            array(
                'User-Agent: OauthBundle (http://www.08cms.com)',
                'Content-Length: ' . $contentLength,
            ),
            $headers
        );

        $request->setHeaders($headers);
        $request->setContent($content);

        try {
            $this->httpClient->send($request, $response);
        } catch (ClientException $e) {
            throw new HttpTransportException('Error while sending HTTP request', $this->getName(), $e->getCode(), $e);
        }

        return $response;
    }

    /**
     * Performs an upload request
     * @param string $url           The url to fetch
     * @param string $content       The content of the request
     * @param string $media         The upload  field
     * @param array $headers        The headers of the request
     */
    public function uploadRequest($url, array $content, array $media = array(), $headers = array())
    {
        $response = new HttpResponse();

        $request = new FormRequest();
        $request->setResource($url);

        //定义附件
        foreach($media as $v)
        {
            if(!$content[$v])
                continue;
            $upload = new FormUpload($content[$v]);
            $upload->setName($v);
            $upload->setFilename(date('Y').'08cms'.$upload->getFilename());
            $upload->setContentType($upload->getContentType());
            $request->setField($v, $upload);
            unset($content[$v]);
        }

        //定义表单
        foreach($content as $k=>$v)
        {
            $request->setField($k, $v);
        }

        //定义url头
        $headers = array_merge(
            array(
                'User-Agent: OauthBundle (http://www.08cms.com)',
            ),
            $headers
        );

        try {
            $this->httpClient->send($request, $response);
        } catch (ClientException $e) {
            throw new HttpTransportException('Error while sending HTTP request', $this->getName(), $e->getCode(), $e);
        }

        //控制器初始化
        if(method_exists($this,'getResponseContent'))
            return $this->getResponseContent($response);

        return $response;
    }

    /**
     * Performs an upload request
     * @param string $url           The url to fetch
     * @param string $content       The content of the request
     */
    public function HttpRequestCurl($url, $content = null, $method = 'get', $curlparameter = array(), $headers = array())
    {
        $request  = new HttpRequest($method, $url);
        $response = new HttpResponse();

        $parameter = array();

        if(isset($curlparameter['CURL_PROXY_HOST']) && $curlparameter['CURL_PROXY_HOST'] != "0.0.0.0" && !empty($curlparameter['CURL_PROXY_PORT']))
        {
            $parameter[CURLOPT_PROXY] = $curlparameter['CURL_PROXY_HOST'];
            $parameter[CURLOPT_PROXYPORT] = $curlparameter['CURL_PROXY_PORT'];
        }

        if(isset($curlparameter['SSLCERT_PATH']) && isset($curlparameter['SSLKEY_PATH'])){
            $parameter[CURLOPT_SSLCERTTYPE] = 'PEM';
            $parameter[CURLOPT_SSLCERT] = $curlparameter['SSLCERT_PATH'];
            $parameter[CURLOPT_SSLKEYTYPE] = 'PEM';
            $parameter[CURLOPT_SSLKEY] = $curlparameter['SSLKEY_PATH'];
        }

        if(isset($curlparameter['CURLOPT_CAINFO']))
            $parameter[CURLOPT_CAINFO] = $curlparameter['CURLOPT_CAINFO'];

        $contentLength = 0;
        if (is_string($content)) {
            $contentLength = strlen($content);
        } elseif (is_array($content)) {
            $contentLength = strlen(implode('', $content));
        }

        $headers = array_merge(
            array(
                'User-Agent: OauthBundle (http://www.08cms.com)',
                'Content-Length: ' . $contentLength,
            ),
            $headers
        );

        $request->setHeaders($headers);
        $request->setContent($content);

        try {
            $this->httpClient->send($request, $response, $parameter);
        } catch (ClientException $e) {
            throw new HttpTransportException('Error while sending HTTP request', $this->getName(), $e->getCode(), $e);
        }

        return $response;

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
        // First check that content in response exists, due too bug: https://bugs.php.net/bug.php?id=54484
        $content = $rawResponse->getContent();
        if (!$content) {
            return array();
        }

        $response = json_decode($content, true);
        if (JSON_ERROR_NONE !== json_last_error()) {
            parse_str($content, $response);
        }

        return $response;
    }

    /**
     * Generate a non-guessable nonce value.
     *
     * @return string
     */
    protected function generateNonce()
    {
        return md5(microtime(true).uniqid('', true));
    }
    
    /**
     *
     * 产生随机字符串，不长于32位
     * @param int $length
     * @return 产生的随机字符串
     */
    public function getNonceStr($length = 32)
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str ="";
        for ( $i = 0; $i < $length; $i++ )  {
            $str .= substr($chars, mt_rand(0, strlen($chars)-1), 1);
        }
        return $str;
    }
    

    /**
     * 最简单的XML转数组
     * @param string $xmlstring XML字符串
     * @return array XML数组
     */
    public function xmlToArray($xml)
    {
        //将XML转为array
        $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $array_data;
    }

    /**
     * @param string $url
     * @param array  $parameters
     *
     * @return HttpResponse
     */
    abstract protected function doGetTokenRequest($url, $parameters = array());

    /**
     * @param string $url
     * @param array  $parameters
     *
     * @return HttpResponse
     */
    abstract protected function doGetUserInformationRequest($url, array $parameters = array());

    /**
     * Configure the option resolver
     *
     * @param OptionsResolverInterface $resolver
     */
    protected function configureOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(array(
            'client_id',
            'client_secret',
            'authorization_url',
            'access_token_url',
            'infos_url',
        ));

        $resolver->setDefaults(array(
            'scope'               => null,
            'csrf'                => false,
            'user_response_class' => 'OAuthBundle\OAuth\Response\PathUserResponse',
            'auth_with_one_url'   => false,
            'mchid'               => '',
            'paysignkey'          => '',
        ));

        if (method_exists($resolver, 'setDefined')) {
            $resolver->setAllowedValues('csrf', array(true, false));
        } else {
            $resolver->setAllowedValues(array(
                'csrf' => array(true, false),
            ));
        }
    }
}
