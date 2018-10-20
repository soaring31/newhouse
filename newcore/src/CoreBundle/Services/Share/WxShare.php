<?php
namespace CoreBundle\Services\Share;

use CoreBundle\Services\ServiceBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class WxShare extends ServiceBase
{
    private $appId;
    private $appSecret;
    // 全局缓存文件
    private $filePath_accesstoken;
    private $filePath_jsapiticket;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        self::init();
    }
    
    private function init()
    {
        $map = array();
        $map['ename'] = 'mloginset';
        $result = $this->get('db.mconfig')->getData($map);

        $this->appId = isset($result['wxappid']['value'])?$result['wxappid']['value']:0;
        $this->appSecret = isset($result['wxapp_secret']['value'])?$result['wxapp_secret']['value']:0;
        $this->filePath_accesstoken = $this->get('core.common')->getSiteRoot()."Config".DIRECTORY_SEPARATOR."weixin_accesstoken.yml";
        $this->filePath_jsapiticket = $this->get('core.common')->getSiteRoot()."Config".DIRECTORY_SEPARATOR."weixin_jsapiticket.yml";
    }

    public function getSignPackage()
    {
        $jsapiTicket = self::getJsApiTicket();

        // 注意 URL 一定要动态获取，不能 hardcode.
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    
        $timestamp = time();
        $nonceStr = $this->createNonceStr();
    
        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
    
        $signature = sha1($string);
    
        $signPackage = array(
            "appId"     => $this->appId,
            "nonceStr"  => $nonceStr,
            "timestamp" => $timestamp,
            "url"       => $url,
            "signature" => $signature,
            "rawString" => $string
        );
        return $signPackage; 
    }

    private function createNonceStr($length = 16)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    private function getJsApiTicket()
    {
        $data = self::getData('jsapiticket');
        if ($data['expire_time'] < time())
        {
            $accessToken = self::getAccessToken();
            // 如果是企业号用以下 URL 获取 ticket
            // $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
            $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
            $res = $this->httpGet($url);
            if (false == $res)
                return false;

            $res = json_decode($res, true);
            if (is_array($res)) 
            {
                if (isset($res['ticket']))
                {
                    $ticket = $res['ticket'];
                    self::handleYmlData('jsapiticket', $ticket);
                } else {
                    return false;
                }
            }
        } else {
            $ticket = $data['jsapi_ticket'];
        }
        
        return $ticket;
    }

    private function getAccessToken()
    {
        $data = self::getData('accesstoken');
        if ($data['expire_time'] < time())
        {
            //如果是企业号用以下URL获取access_token
            // $url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=$this->appId&corpsecret=$this->appSecret";
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appId&secret=$this->appSecret";
            $res = $this->httpGet($url);
            if (false == $res)
                return false;
            $res = json_decode($res, true);
            if (is_array($res))
            {
                if (isset($res['access_token']))
                {
                    $access_token = $res['access_token'];
                    self::handleYmlData('accesstoken', $access_token);
                } else {
//                     throw new \LogicException(json_encode($res));//调试
                    return false;
                }
            }
        } else {
            $access_token = $data['access_token'];
        }
        
        return $access_token;
    }

    private function httpGet($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        // 为保证第三方服务器与微信服务器之间数据传输的安全性，所有微信接口采用https方式调用，必须使用下面2行代码打开ssl安全校验。
        // 如果在部署过程中代码在此处验证失败，请到 http://curl.haxx.se/ca/cacert.pem 下载新的证书判别文件。
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_URL, $url);
    
        $res = curl_exec($curl);
        curl_close($curl);
    
        return $res;
    }

    /**
     * 缓存 accesstoken 与 jsapiticket
     * @param unknown $name
     * @param unknown $value
     */
    private function handleYmlData($name, $value)
    {
        $result = array();

        $result['expire_time'] = time() + 7000;
        if ($name == 'accesstoken')
        {
            $result['access_token'] = $value;
            $this->get('core.ymlParser')->ymlWrite($result, $this->filePath_accesstoken);
        } elseif ($name == 'jsapiticket') {
            $result['jsapi_ticket'] = $value;
            $this->get('core.ymlParser')->ymlWrite($result, $this->filePath_jsapiticket);
        }
    }
    
    /**
     * 直接从文件中读取
     * @param array $criteria
     * @return multitype:
     */
    public function getData($name)
    {
        $info = array();
        if ($name == 'accesstoken')
            $info = $this->get('core.ymlParser')->ymlRead($this->filePath_accesstoken);
        elseif ($name == 'jsapiticket')
            $info = $this->get('core.ymlParser')->ymlRead($this->filePath_jsapiticket);
        return $info;
    }
}

