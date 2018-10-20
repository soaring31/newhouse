<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年6月21日
*/
namespace OAuthBundle\OAuth\ResourceOwner;

use OAuthBundle\Security\Core\Authentication\Token\OAuthToken;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class AlipayResourceOwnerold extends GenericOAuth2ResourceOwner
{
    /**
     * {@inheritDoc}
     */
    protected $paths = array(
        'oauthid'     => 'openid',
        'nickname'       => 'nickname',
        'realname'       => 'nickname',
        'headimage' => 'figureurl_qq_1',
    );
    
    public function getAccessTokenss($code='',$extend=''){
        $sign=$_GET['sign'];
        unset($_GET['a'],$_GET['type'],$_GET['sign']);
        if ($sign===$this->createSign($_GET)){
            $this->Token=array(
                'access_token'=>$_GET['login_token'],
                'openid'=>$_GET['user_id'],
            );
            return $this->Token;
        }else{
            exit('签名验证失败');
        }
    }

    protected function createSign($params)
    {
        ksort($params);
        $stringToBeSigned = "";
        $i = 0;
        foreach ($params as $k => $v) {
            if ($i == 0) {
                $stringToBeSigned .= "$k" . "=" . "$v";
            } else {
                $stringToBeSigned .= "&" . "$k" . "=" . "$v";
            }
            $i++;
        }
        unset ($k, $v);
        return md5($stringToBeSigned.$this->options['client_secret']);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getAuthorizationUrl($redirectUri, array $extraParameters = array())
    {
        if ($this->options['csrf']) {
            if (null === $this->state) {
                $this->state = $this->generateNonce();
            }
    
            $this->storage->save($this, $this->state, 'csrf_state');
        }

        $parameters = array_merge(array(
            '_input_charset' => 'utf-8',
            'partner'        => $this->options['client_id'],
            'service'        => 'alipay.auth.authorize',
            'target_service' => 'user.auth.quick.login',
            'scope'          => $this->options['scope'],
            'state'          => $this->state ? urlencode($this->state) : null,
            'return_url'     => $redirectUri,
        ), $extraParameters);
        $parameters['sign'] = $this->createSign($parameters);
        $parameters['sign_type'] = 'MD5';

        return $this->normalizeUrl($this->options['authorization_url'], $parameters);
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
     * {@inheritDoc}
     * https://openapi.alipay.com/gateway.do?_input_charset=utf-8&partner=2088002045835984&return_url=http%3A%2F%2Fwww.gz269.com%2Fapp_dev.php%2Fmanage%2Foauth%2Fcheckalipay&service=alipay.auth.authorize&target_service=user.auth.quick.login&sign=6418f3f12e8580ae2090c26eb5203461&sign_type=MD5
     * https://openapi.alipay.com/gateway.do?_input_charset=utf-8&partner=2016062101539205&return_url=http%3A%2F%2Fwww.gz269.com%2Fapp_dev.php%2Fmanage%2Foauth%2Fcheckalipay&&sign=b5019f2c1044d2143e685e9e30f7cf11&sign_type=MD5
     */
    protected function configureOptions(OptionsResolverInterface $resolver)
    {
        parent::configureOptions($resolver);
    
        $resolver->setDefaults(array(
            'authorization_url' => 'https://openapi.alipay.com/gateway.do',
            'access_token_url'  => 'https://openapi.alipay.com/gateway.do',
            'infos_url'         => 'https://openapi.alipay.com/gateway.do',
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
     * 对数组排序
     * @param $para 排序前的数组
     * return 排序后的数组
     */
    public function argSort($para)
    {
        ksort($para);
        reset($para);
        return $para;
    }

    public function md5Sign($prestr, $key)
    {
        $prestr = $prestr . $key;
        return md5($prestr);
    }
    
    /**
     * RSA签名
     * @param $data 待签名数据
     * @param $private_key 商户私钥字符串
     * return 签名结果
     */
    function rsaSign($data, $private_key)
    {
        $sign = "";
        //以下为了初始化私钥，保证在您填写私钥时不管是带格式还是不带格式都可以通过验证。
        $private_key=str_replace("-----BEGIN RSA PRIVATE KEY-----","",$private_key);
        $private_key=str_replace("-----END RSA PRIVATE KEY-----","",$private_key);
        $private_key=str_replace("-----BEGIN PRIVATE KEY-----","",$private_key);
        $private_key=str_replace("-----END PRIVATE KEY-----","",$private_key);
        $private_key=str_replace("\n","",$private_key);

        $private_key="-----BEGIN RSA PRIVATE KEY-----".PHP_EOL .wordwrap($private_key, 64, "\n", true). PHP_EOL."-----END RSA PRIVATE KEY-----";

        $res=openssl_get_privatekey($private_key);
    
        if($res)
            openssl_sign($data, $sign,$res);
        else
            throw new \LogicException("您的私钥格式不正确!"."<br/>"."The format of your private_key is incorrect!");

        openssl_free_key($res);

        //base64编码
        $sign = base64_encode($sign);

        return $sign;
    }

    /**
     * 验证签名
     * @param $prestr 需要签名的字符串
     * @param $sign 签名结果
     * @param $key 私钥
     * return 签名结果
     */
    public function md5Verify($prestr, $sign, $key)
    {
        $prestr = $prestr . $key;
        $mysgin = md5($prestr);

        if($mysgin == $sign) {
            return true;
        }else {
            return false;
        }
    }
    
    /**
     * RSA验签
     * @param $data 待签名数据
     * @param $sign 要校对的的签名结果
     * @param $alipay_public_key 支付宝的公钥字符串
     * return 验证结果
     */
    function rsaVerify($data, $sign, $alipay_public_key)  {
        //以下为了初始化私钥，保证在您填写私钥时不管是带格式还是不带格式都可以通过验证。
        $alipay_public_key=str_replace("-----BEGIN PUBLIC KEY-----","",$alipay_public_key);
        $alipay_public_key=str_replace("-----END PUBLIC KEY-----","",$alipay_public_key);
        $alipay_public_key=str_replace("\n","",$alipay_public_key);
    
        $alipay_public_key='-----BEGIN PUBLIC KEY-----'.PHP_EOL.wordwrap($alipay_public_key, 64, "\n", true) .PHP_EOL.'-----END PUBLIC KEY-----';
        $res=openssl_get_publickey($alipay_public_key);
        if($res)
        {
            $result = (bool)openssl_verify($data, base64_decode($sign), $res);
        }
        else {
            echo "您的支付宝公钥格式不正确!"."<br/>"."The format of your alipay_public_key is incorrect!";
            exit();
        }
        openssl_free_key($res);
        return $result;
    }

    /**
     * 用于防钓鱼，调用接口query_timestamp来获取时间戳的处理函数
     * 注意：该功能PHP5环境及以上支持，因此必须服务器、本地电脑中装有支持DOMDocument、SSL的PHP配置环境。建议本地调试时使用PHP开发软件
     * return 时间戳字符串
     */
    public function query_timestamp()
    {
        $url = $this->alipay_gateway_new."service=query_timestamp&partner=".trim(strtolower($this->alipay_config['partner']))."&_input_charset=".trim(strtolower($this->alipay_config['input_charset']));
        $encrypt_key = "";      

        $doc = new \DOMDocument();
        $doc->load($url);
        $itemEncrypt_key = $doc->getElementsByTagName( "encrypt_key" );
        $encrypt_key = $itemEncrypt_key->item(0)->nodeValue;
        
        return $encrypt_key;
    }

    /**
     * 获取自定义接口数据
     * @param array $parameters
     */
    public function getToUrl($url, $extraParameters)
    {
        return $this->doGetTokenRequest($url, $extraParameters);
    }

}