<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年4月12日
*/
namespace WeixinBundle\Security;

use CoreBundle\Services\ServiceBase;
USE WeixinBundle\Security\Crypt\WXBizMsgCrypt;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AbstractWeiXin extends ServiceBase
{
    protected $debug = true;
    protected $name;
    protected $error;
    protected $appId;
    protected $token;
    protected $easkey;
    protected $account;
    protected $container;
    protected $errorCode;
    protected $appSecret;
    protected $mchid;
    protected $paysignkey;
    protected $resourceOwner;
    protected $component_access_token;
    protected $component_verify_ticket;

    public function __construct(ContainerInterface $container)
    {

        $this->container = $container;
    
        $this->resourceOwner = $this->get('oauth.security.oauth_utils')->getResourceOwner($this->name);
    
        //错误代码转义
        $errorCode = new ErrorCode();
        $this->errorCode = $errorCode->getText();
        
        //设置公众号菜单时，会与 request.appid 冲突！！
        //self::init($this->get('request')->getSession()->get('appid', $this->resourceOwner->getOption('client_id')));
    }
    
    /**
     * 初始化
     * @param UserInterface $user
     * @param array $extraParameters
     * @return boolean
     */
    public function init($appId)
    {
        //读缓存
        $key = 'oauth_account_'.$appId;
        
        $this->account = $this->get('core.common')->S($key);

        //判断actoken有效时间
        if(!is_object($this->account))
        {
            $map = array();
            $map['appid'] = $appId;

            $this->account = $this->get('db.wxusers')->findOneBy($map);
    
             if(!is_object($this->account))
                 throw new \InvalidArgumentException('无效的appid'.$appId);
        }    
        
        if(is_object($this->account))
            self::resetOwner();

        return true;
    }
    
    public function resetOwner($flag=false)
    {
        $this->appId = $this->account->getAppId();
        $this->appSecret = $this->account->getAppsecret();
        $this->easkey = $this->account->getEaskey();
        $this->component_access_token = $this->account->getActoken();
        $this->token = $this->account->getToken();
        $this->mchid = $this->account->getMchid();
        $this->paysignkey = $this->account->getPaysignkey();
        $this->component_verify_ticket = $this->account->getComponentVerifyTicket();

        $options = array();
        $options['client_id'] = $this->appId;
        $options['client_secret'] = $this->appSecret;
        $options['client_easkey'] = $this->easkey;
        $options['component_verify_ticket'] = $this->component_verify_ticket;
        $this->resourceOwner->setOption($options);

        if($this->account->getAcexp()-7100<=time())
        {
            try {
                switch($this->account->getType())
                {
                    //通过第三方平台授权的公众号
                    case 4:
                        $info = $this->resourceOwner->getOpenAccessToken();
                        if(isset($info['errcode'])) 
                        {
                            throw new \LogicException(isset($this->errorCode[$info['errcode']])?$this->errorCode[$info['errcode']].$info['errmsg']:$info['errmsg']);
                        }
                      
                        $this->account->setActoken($info['component_access_token']);
                        $this->account->setAcexp(time()+$info['expires_in']);
                        break;
                    //自行注册并认证的公众号
                    default:
                        $info = $this->resourceOwner->getClientAccessToken();               
                        if(isset($info['errcode']))
                        {
                            //在本地调试时，因为是动态IP,需要将本地IP加入到公众号的白名单
                            throw new \LogicException(isset($this->errorCode[$info['errcode']])?$this->errorCode[$info['errcode']].$info['errmsg']:$info['errmsg']);
                        }
                       
                        $this->account->setActoken($info['access_token']);
                        $this->account->setAcexp(time()+$info['expires_in']);
                        break;
                }
            }catch (\Exception $e){
                //??????，之前是清空的。如果这里不中止，下面的写缓存会有错误提示
                throw new \LogicException($e->getMessage());
            }

            $flag = true;
        }
        
        //更新actoken
        if($flag)
        {
            $this->get('db.wxusers')->update($this->account->getId(),array(), $this->account, false);

            //写缓存,access_token的有效期目前为2个小时也就是7200秒
            $key = 'oauth_account_'.$this->account->getAppId();
            $this->get('core.common')->S($key, $this->account, $this->account->getAcexp()-time()-100);
        }
        unset($info);
    }
    
    public function getToken()
    {
        return $this->token;
    }
    
    public function getAppId()
    {
        return $this->appId;
    }
    
    public function getAppIdByToken($token)
    {
        return $this->get('core.common')->xorDecode($token);
    }
    
    public function getTokenByAppId($appId)
    {
        return $this->get('core.common')->xorEncode($appId);
    }
    
    public function getComponentVerifyTicket()
    {
        return $this->component_verify_ticket;
    }
    
    public function getMchid()
    {
        return $this->mchid;
    }
    
    /**
     * 获取微信AccessToken（公众平台）
     */
    public function getAccessToken()
    {
        return $this->account->getActoken();
    }
    
    /**
     * 网页授权
     * @param unknown $info
     * @param string $scope
     * @param string $fansInfo
     * @param string $code
     * @param string $state
     * @throws \LogicException
     * @return multitype:unknown
     */
    public function webOauth($info, $scope = '', $fansInfo = '', $code='', $state='')
    {
        $request = $this->get('request');
        $redirect_uri = "http://".$request->server->get('HTTP_HOST').$request->getRequestUri();
    
        $codeUrl = $this->getCodeUrl($info, $redirect_uri, $scope, '', $fansInfo);
        if (empty($code) && empty($state)) {
            header('Location: ' . $codeUrl);
            exit();
        }else {
            if (!empty($code)) {
                $res = $this->get_web_access_token($info, $code);
    
                if (!isset($res['errcode']))
                    return array('access_token' => $res['access_token'], 'openid' => $res['openid']);
                else
                    throw new \LogicException("授权错误，请检查公众号权限和设置");
            }
        }
    }
    
    public function getCodeUrl($info, $redirect_uri = '', $scope = '', $state = 'oauth', $fansInfo)
    {
        if (empty($scope)) {
            if ($info['oauthinfo'])
                $scope = 'snsapi_userinfo';
            else 
                $scope = 'snsapi_base';
        }
    
        $redirect_uri = urlencode($redirect_uri);

        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $info['appid'] . '&redirect_uri=' . $redirect_uri . '&response_type=code&scope=' . $scope . '&state=' . $state;
        
        if (($info['type'] == 1) && ($info['winxintype'] == 3) && ($info['oauth'] == 1))
            $url .= '&component_appid=' . $this->appId;
    
        $url .= '#wechat_redirect';
        return $url;
    }
    
    /**
     * 获取微信服务器IP地址
     */
    public function getServerIp()
    {
        $options = $map = array();
        $options['me_url'] = 'https://api.weixin.qq.com/cgi-bin/getcallbackip';
        $this->resourceOwner->setOption($options);
        $map['access_token'] = self::getAccessToken();
        $info = $this->resourceOwner->getMeUrl($map);
    
        if(isset($info['errcode']))
            throw new \LogicException(isset($this->errorCode[$info['errcode']])?$this->errorCode[$info['errcode']].$info['errmsg']:$info['errmsg']);
    
        return $info['ip_list'];
    }
    
    /**
     * 错误检测
     * @param array $info
     * @throws \LogicException
     */
    public function getError(array $info)
    {
        if(isset($info['errcode']))
            throw new \LogicException(isset($this->errorCode[$info['errcode']])?$this->errorCode[$info['errcode']].$info['errmsg']:$info['errmsg']);
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
        return $this->resourceOwner->httpRequest($url, $content, $headers, $method);
    }
    
    public function httpRequestCurl($url, $content = null, $method = null)
    {
        return $this->resourceOwner->HttpRequestCurl($url, $content, $method);
    }
    
    /**
     * Performs an upload request
     * @param string $url           The url to fetch
     * @param string $content       The content of the request
     * @param string $media         The upload  field
     * @param array $headers        The headers of the request
     */
    public function uploadRequest($url, array $content, array $media, $headers = array())
    {
        return $this->resourceOwner->uploadRequest($url, $content, $media, $headers);
    }
    
    /**
     * 按xml格式生成数据
     *
     * @param  array $data   数据
     * @param  array $attribute   属性
     * @return string        返回xml格式数据
     */
    public function arrayToXML($data)
    {
        $xml = new \SimpleXMLElement("<xml></xml>");
        $f = create_function('$f,$c,$a','
            foreach($a as $k=>$v) {
                if(is_array($v)) {
                    if (!is_numeric($k))$ch=$c->addChild($k);
                    else $ch = $c->addChild(substr($c->getName(),0,-1));
                    $f($f,$ch,$v);
                } else {
                    if (is_numeric($v)){
						$c->addChild($k, $v);
                    }else{
						$n = $c->addChild($k); //$n->addCData($v);
						$dom = dom_import_simplexml($c);
						$cdata = $dom->ownerDocument->createCDATASection($v);
						$dom->appendChild($cdata);
					}
                }
            }');
        $f($f,$xml,$data);
        return $xml->asXML();
    }

    /**
     * 将xml转为array
     * @param unknown $xml
     * @return mixed
     */
    function xmlToArray($xml)
    {
        //将XML转为array
        $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $array_data;
    }
    
    /**
     * 检验微信的signature
     * @param string $token
     * @return boolean
     */
    public function checkSignature($token)
    {
        //微信加密签名，signature结合了开发者填写的token参数和请求中的timestamp参数、nonce参数。
        $signature = $this->get('request')->get('signature','');
    
        //时间戳
        $timestamp = $this->get('request')->get('timestamp','');
    
        //随机数
        $nonce = $this->get('request')->get('nonce','');
    
        $tmpArr = array($token, $timestamp, $nonce);
    
        //排序
        sort($tmpArr, SORT_STRING);
    
        $tmpStr = implode( $tmpArr );
    
        $tmpStr = sha1( $tmpStr );
    
        if( $tmpStr == $signature )
            return true;
        else
            return false;
    }
    
    /**
     * 解密
     * @param xml $encryptMsg
     * @return SimpleXMLElement
     */
    public function decryptMsg($encryptMsg)
    {
        $msg = "";
        $timeStamp = trim($this->get('request')->get('timestamp',''));
        $nonce = trim($this->get('request')->get('nonce',''));
        $msg_sign = trim($this->get('request')->get('msg_signature',''));

        $pc = new WXBizMsgCrypt ( $this->token, $this->easkey, $this->appId );

        //消息解密
        $errCode = $pc->decryptMsg ( $msg_sign, $timeStamp, $nonce, $encryptMsg, $msg );

        if ($errCode == 0)
            return simplexml_load_string($msg, 'SimpleXMLElement', LIBXML_NOCDATA);

        die($errCode);
    }
    
    /**
     * 加密
     * @param xml $decryptMsg
     */
    public function encryptMsg($decryptMsg)
    {
        $timeStamp = trim($this->get('request')->get('timestamp',''));
        $nonce = trim($this->get('request')->get('nonce',''));
        $msg_sign = trim($this->get('request')->get('msg_signature',''));
        $pc = new WXBizMsgCrypt ( $this->token, $this->easkey, $this->appId );
        
        //消息加密
        $errCode = $pc->encryptMsg ( $msg_sign, $timeStamp, $nonce, $decryptMsg );
        if ($errCode == 0)
            return $decryptMsg;

        die($errCode);
    }
}