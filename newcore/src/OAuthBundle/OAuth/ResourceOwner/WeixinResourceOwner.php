<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年6月22日
*/
namespace OAuthBundle\OAuth\ResourceOwner;

use WeixinBundle\Services\Pay\WxPay;
use WeixinBundle\Services\Pay\JsApiPay;
use Symfony\Component\HttpFoundation\Request;
use OAuthBundle\WeiXin\Pay\Request\WxPayResults;
use OAuthBundle\WeiXin\Pay\Request\WxPaySandbox;
use OAuthBundle\WeiXin\Pay\Request\WxPayOrderQuery;
use OAuthBundle\WeiXin\Pay\Request\WxPayUnifiedOrder;
use OAuthBundle\Security\Core\Authentication\Token\OAuthToken;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class WeixinResourceOwner extends GenericOAuth2ResourceOwner
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
    
    protected $model = "APP";
//    protected $model = "DEV";//沙箱模式,用于测试支付

    /**
     * 获取自定义接口数据
     * @param array $parameters
     */
    public function getMeUrl($extraParameters = array())
    {
        if(is_array($extraParameters))
        {
            $extraParameters = array_merge(array(
                'appid'      => $this->options['client_id'],
                'secret'     => $this->options['client_secret'],
                'grant_type' => isset($this->options['grant_type'])?$this->options['grant_type']:'client_credential',
            ), $extraParameters);

            if(empty($extraParameters['appid']))
                unset($extraParameters['appid']);

            if(empty($extraParameters['secret']))
                unset($extraParameters['secret']);

            if(empty($extraParameters['grant_type']))
                unset($extraParameters['grant_type']);

            ksort($extraParameters);
        }

        return $this->getResponseContent($this->doGetTokenRequest($this->options['me_url'], $extraParameters));
    }

    public function getClientAccessToken(array $extraParameters = array())
    {
        $parameters = array_merge(array(
            'appid' => $this->options['client_id'],
            'secret' => $this->options['client_secret'],
            'grant_type' => 'client_credential',
        ), $extraParameters);

        ksort($parameters);
        $response = $this->doGetTokenRequest('https://api.weixin.qq.com/cgi-bin/token', $parameters);

        return $this->getResponseContent($response);
    }
    
    /**
     * 获取第三方平台access_token
     * @param array $extraParameters
     */
    public function getOpenAccessToken(array $extraParameters = array())
    {
        $parameters = array_merge(array(
            'component_appid' => $this->options['client_id'],
            'component_appsecret' => $this->options['client_secret'],
            'component_verify_ticket' => $this->options['component_verify_ticket'],
        ), $extraParameters);
    
        ksort($parameters);

        $parameters = json_encode( $parameters );
        $response = $this->doGetTokenRequest('https://api.weixin.qq.com/cgi-bin/component/api_component_token', $parameters);
        return $this->getResponseContent($response);
    }

    public function getAccessToken(Request $request, $redirectUri, array $extraParameters = array())
    {
        $parameters = array_merge(array(
            'appid' => $this->options['client_id'],
            'secret' => $this->options['client_secret'],
            'code' => $request->query->get('code'),
            'grant_type' => 'authorization_code',
        ), $extraParameters);

        $response = $this->doGetTokenRequest($this->options['access_token_url'], $parameters);

        $content = $this->getResponseContent($response);

        $this->validateResponseContent($content);

        $this->get('request')->getSession()->set('token', $content);

        return $content;
    }

    /**
     * {@inheritDoc}
     */
    public function getAuthorizationUrl($redirectUri, array $extraParameters = array())
    {
        $parameters = array_merge(array(
            'appid' => $this->options['client_id'],
            'redirect_uri' => $redirectUri,
            'response_type' => 'code',
            'scope' => $this->options['scope'] ? $this->options['scope'] : 'snsapi_userinfo',
            'state' => $this->state ? urlencode($this->state) : null,
        ), $extraParameters);

        ksort($parameters);

        return $this->normalizeUrl($this->options['authorization_url'], $parameters).'#wechat_redirect';
        
        if($this->get('core.common')->isMobileClient())
            return $this->normalizeUrl($this->options['authorization_url'], $parameters);
        else
            return $this->normalizeUrl($this->options['qrcodeauthorization_url'], $parameters);
    }

    /**
     * {@inheritDoc}
     */
    public function getUserInformation(array $accessToken = null, array $extraParameters = array())
    {
        $url = $this->normalizeUrl($this->options['infos_url'], array(
            'access_token' => $accessToken['access_token'],
            'openid'       => $accessToken['openid'],
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
     * {@inheritDoc}
     */
    public function getComponentLoginUrl($redirectUri, array $extraParameters = array())
    {
        $parameters = array_merge(array(
            'component_appid' => $this->options['client_id'],
            'redirect_uri'  => $redirectUri,
            'pre_auth_code' => 0,//self::getPreAuthCode(),
        ), $extraParameters);
    
        ksort($parameters);

        return $this->normalizeUrl('https://mp.weixin.qq.com/cgi-bin/componentloginpage', $parameters);
    }
    
    /**
     * 验签方法
     */
    public function verifyNotify()
    {
        return true;
    }
    
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
     * H5支付：用于微信客户端、APP之外的手机浏览器内支付
     */
    public function getH5Pay(array $data)
    {
        if($this->model == "DEV")
        {
            //获取沙箱签名密钥
            $wxPaySandbox = new WxPaySandbox();
            
            //公众账号ID
            $wxPaySandbox->setAppid($this->options['client_id']);
            
            //商户号
            $wxPaySandbox->setMchId($this->options['mchid']);
            
            //随机字符串
            $wxPaySandbox->setNonceStr(self::getNonceStr());
            
            //signkey
            $wxPaySandbox->setPaysignkey($this->options['paysignkey']);
            
            //签名
            $wxPaySandbox->setSign();
            
            $xml = $wxPaySandbox->ToXml();
            
            $response = parent::HttpRequestCurl($this->options['signkeyurl'], $xml, 'post');
            $response = self::xmlToArray($response->getContent());
            
            $this->options['paysignkey'] = isset($response['sandbox_signkey'])?$response['sandbox_signkey']:$this->options['paysignkey'];
        }
      
        $input = new WxPayUnifiedOrder();
        $input->setBody($data['body']);
        $input->setAttach($data['attach']);
        $input->setOutTradeNo($data['ordersn']);
        $input->setTotalFee($data['total_fee']);
        $input->setTimeStart(date("YmdHis"));
        $input->setTimeExpire(date("YmdHis", time() + 600));
        $input->setGoodsTag($data['goods_tag']);
        $input->setNotifyUrl($this->get('core.common')->U('notify/weixin', array(), true));
        $input->setTradeType("MWEB");//JSAPI(公众号支付)、NATIVE(扫码支付)、APP、MWEB(H5支付)
        $input->setProductId($data['ordersn']);
        
        //公众账号ID
        $input->setAppid($this->options['client_id']);
        
        //商户号
        $input->setMchId($this->options['mchid']);
        
        //终端ip
        $input->setSpbillCreateIp($_SERVER['REMOTE_ADDR']);
        
        //随机字符串
        $input->setNonceStr(self::getNonceStr());
        
        //signkey
        $input->setPaysignkey($this->options['paysignkey']);
        
        //签名
        $input->setSign();

        //检测必填参数
        if(!$input->isOutTradeNoSet())        
            throw new \Exception("缺少统一支付接口必填参数out_trade_no！");
        
        if(!$input->isBodySet())
            throw new \Exception("缺少统一支付接口必填参数body！");
        
        if(!$input->isTotalFeeSet())
            throw new \Exception("缺少统一支付接口必填参数total_fee！");

        if(!$input->isTradeTypeSet())
            throw new \Exception("缺少统一支付接口必填参数trade_type！");
        
        //关联参数
        if($input->getTradeType() == "JSAPI" && !$input->isOpenidSet())
            throw new \Exception("统一支付接口中，缺少必填参数openid！trade_type为JSAPI时，openid为必填参数！");

        if($input->getTradeType() == "NATIVE" && !$input->isProductIdSet())
            throw new \Exception("统一支付接口中，缺少必填参数product_id！trade_type为JSAPI时，product_id为必填参数！");
        
        //异步通知url
        if(!$input->isNotifyUrlSet())
            $input->setNotifyUrl($this->get('core.common')->U("notify/weixin"));

        //转换成xml格式
        $xml = $input->ToXml();
        
        $response = parent::HttpRequestCurl($this->options['unifiedorder'], $input->ToXml(), 'post');

        $wxPayResults = new WxPayResults();

        $wxPayResults->setPaysignkey($this->options['paysignkey']);
        
        $result = $wxPayResults->init($response->getContent());
        $result['ordersn'] = $data['ordersn'];

        if(array_key_exists("return_code", $result)&& array_key_exists("result_code", $result)&& $result["return_code"] == "SUCCESS"&& $result["result_code"] == "SUCCESS")
            return $result;
        
        throw new \Exception(isset($result['return_msg'])?$result['return_msg']:'参数错误');
    }
    
    // 微信客户端内使用公众号支付(JSAPI 支付)
    public function getJsApi(array $data)
    {
        // 获取用户openid
        $tools = new JsApiPay();
        $openId = $tools->GetOpenid();
        //$openId = 'ozhJVv8uHPfMKquhA72rFG4Vu2l0';//使用一个固定的微信用户的openid做调试
        
        $input = new WxPayUnifiedOrder();
        $input->setBody($data['body']);
        $input->setOutTradeNo($data['ordersn']);
        $input->setTotalFee($data['total_fee']);
        $input->setTradeType("JSAPI");//JSAPI(公众号支付)、NATIVE(扫码支付)、APP、MWEB(H5支付)
        $input->setOpenId($openId);

        
        $input->setNotifyUrl($this->get('core.common')->U('notify/weixin', array(), true));
        $input->setAppid($this->options['client_id']);
        $input->setMchId($this->options['mchid']);
        $input->setSpbillCreateIp($_SERVER['REMOTE_ADDR']);
        $input->setNonceStr(self::getNonceStr());
        
        $input->setPaysignkey($this->options['paysignkey']);
        $input->setSign();

        $order = WxPay::unifiedOrder($input);

        $jsApiParameters = $tools->getJsApiParameters($order);
        
        //获取共享收货地址js函数参数
        //$editAddress = $tools->getEditAddressParameters();
        
        $ret = array(
            'jsApiParameters' => $jsApiParameters,
            //'editAddress' => $editAddress,
        );
        return $ret;
    }
    
    /**
     * 扫码支付
     * @param array $data
     */
    public function getNative(array $data)
    {
        if($this->model == "DEV")
        {
            //获取沙箱签名密钥
            $wxPaySandbox = new WxPaySandbox();
            
            //公众账号ID
            $wxPaySandbox->setAppid($this->options['client_id']);
            
            //商户号
            $wxPaySandbox->setMchId($this->options['mchid']);
            
            //随机字符串
            $wxPaySandbox->setNonceStr(self::getNonceStr());
            
            //signkey
            $wxPaySandbox->setPaysignkey($this->options['paysignkey']);
            
            //签名
            $wxPaySandbox->setSign();
            
            $xml = $wxPaySandbox->ToXml();
            
            $response = parent::HttpRequestCurl($this->options['signkeyurl'], $xml, 'post');
            $response = self::xmlToArray($response->getContent());
           
            $this->options['paysignkey'] = isset($response['sandbox_signkey'])?$response['sandbox_signkey']:$this->options['paysignkey'];
            
            //沙箱只接受固定值，否则提示输入的金额无效??
            $data['total_fee'] = 101;
        }
        
        $input = new WxPayUnifiedOrder();
        $input->setBody($data['body']);
        $input->setAttach($data['attach']);
        $input->setOutTradeNo($data['ordersn']);
        $input->setTotalFee($data['total_fee']);
        $input->setTimeStart(date("YmdHis"));
        $input->setTimeExpire(date("YmdHis", time() + 600));
        $input->setGoodsTag($data['goods_tag']);
        $input->setNotifyUrl($this->get('core.common')->U('notify/weixin', array(), true));
        $input->setTradeType("NATIVE");
        $input->setProductId($data['ordersn']);
        
        //公众账号ID
        $input->setAppid($this->options['client_id']);
        
        //商户号
        $input->setMchId($this->options['mchid']);
        
        //终端ip
        $input->setSpbillCreateIp($_SERVER['REMOTE_ADDR']);
        
        //随机字符串
        $input->setNonceStr(self::getNonceStr());
        
        //signkey
        $input->setPaysignkey($this->options['paysignkey']);
        
        //签名
        $input->setSign();

        //检测必填参数
        if(!$input->isOutTradeNoSet())        
            throw new \Exception("缺少统一支付接口必填参数out_trade_no！");
        
        if(!$input->isBodySet())
            throw new \Exception("缺少统一支付接口必填参数body！");
        
        if(!$input->isTotalFeeSet())
            throw new \Exception("缺少统一支付接口必填参数total_fee！");

        if(!$input->isTradeTypeSet())        
            throw new \Exception("缺少统一支付接口必填参数trade_type！");
        
        //关联参数
        if($input->getTradeType() == "JSAPI" && !$input->isOpenidSet())
            throw new \Exception("统一支付接口中，缺少必填参数openid！trade_type为JSAPI时，openid为必填参数！");

        if($input->getTradeType() == "NATIVE" && !$input->isProductIdSet())
            throw new \Exception("统一支付接口中，缺少必填参数product_id！trade_type为JSAPI时，product_id为必填参数！");
        
        //异步通知url
        if(!$input->isNotifyUrlSet())
            $input->setNotifyUrl($this->get('core.common')->U("notify/weixin"));

        //转换成xml格式
        $xml = $input->ToXml();
        
        $response = parent::HttpRequestCurl($this->options['unifiedorder'], $input->ToXml(), 'post');    

        $wxPayResults = new WxPayResults();

        $wxPayResults->setPaysignkey($this->options['paysignkey']);
        
        $result = $wxPayResults->init($response->getContent());

        $result['ordersn'] = $data['ordersn'];

        if(array_key_exists("return_code", $result)&& array_key_exists("result_code", $result)&& $result["return_code"] == "SUCCESS"&& $result["result_code"] == "SUCCESS")
            return $result;
        
        throw new \Exception(isset($result['return_msg'])?$result['return_msg']:'参数错误');
    }
    
    /**
     * 订单状态查询
     * @param array $data
     */
    public function tradeQueryRequest(array $data)
    {
        $input = new WxPayOrderQuery();
        
        //微信订单ID
        $input->setTransactionId(isset($data['transaction_id'])?$data['transaction_id']:'');
        
        //公众账号ID
        $input->setAppid($this->options['client_id']);
        
        //商户号
        $input->setMchId($this->options['mchid']);
        
        //随机字符串
        $input->setNonceStr(self::getNonceStr());
        
        //signkey
        $input->setPaysignkey($this->options['paysignkey']);
        
        //签名
        $input->setSign();

        $xml = $input->ToXml();

        $response = parent::HttpRequestCurl($this->options['orderquery'], $xml, 'post');
        
        $wxPayResults = new WxPayResults();

        $wxPayResults->setPaysignkey($this->options['paysignkey']);
        
        $result = $wxPayResults->init($response->getContent());

        if(array_key_exists("return_code", $result)&& array_key_exists("result_code", $result)&& $result["return_code"] == "SUCCESS"&& $result["result_code"] == "SUCCESS")
            return true;

        return false;
    }
    
    /**
     * 退款后订单处理
     * @param array $data
     */
    public function refundHandle(array $data)
    {
        $parameter = array();
        $parameter['batch_no']       = $data['batch_no'];      //退款批次号
        $parameter['success_num']    = $data['success_num'];   //批量退款数据中转账成功的笔数
        $parameter['result_details'] = $data['result_details'];//批量退款数据中的详细信息
    
        //退款处理?数据保存是否兼容?...测试未完成，因安装支付宝控件需人工申请
        $this->get('db.refundlist')->refundHandleAli($parameter);
    }
    
    /**
     * 订单支付成功后处理
     * @param array $data
     */
    public function orderHandle(array $data)
    {
        //获取通知的数据
        $xml = isset($GLOBALS['HTTP_RAW_POST_DATA'])?$GLOBALS['HTTP_RAW_POST_DATA']:'';
        
        //写日志文件
        $file = $this->get('core.common')->getTempRoot()."weixin_notify.txt";
        
        $this->get('core.common')->saveFile($file, $xml);
        
        $wxPayResults = new WxPayResults();
        
        $wxPayResults->setPaysignkey($this->options['paysignkey']);
        
        //如果返回成功则验证签名
        try {
            $result = $wxPayResults->init($xml);
            
            //查询订单
            $map = array();
            $map['checked'] = 0;
            $map['ordersn'] = isset($result['out_trade_no'])?$result['out_trade_no']:'';
            $order = $this->get('db.orderlist')->findOneBy($map);

            if(!is_object($order))
                throw new \Exception("无效的订单");
            
            $order->setTradeNo($result['transaction_id']);
            
            //如果订单未支付成功，则失败
            if(!self::tradeQueryRequest($result))
                throw new \Exception("无效的订单");
            
            $parameter = self::getParameter();
            
            foreach($parameter as &$v)
            {
                $v = isset($result[$v])?$result[$v]:"";
            }
            
            /**
             * 判断该笔订单是否在商户网站中已经做过处理
             * 如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
             * 请务必判断请求时的total_fee、seller_id与通知时获取的total_fee、seller_id为一致的
             * 注意：付款完成后，支付宝系统发送该交易状态通知
             */
            $this->get('db.orderlist')->orderHandleByWeiXin($parameter);
            
            return true;

        } catch (\Exception $e){
            throw new \Exception($e->getMessage());
        }
        
        throw new \Exception("参数错误");
    }
    
    public function getParameter()
    {
        return array(
            'ordersn'       => 'out_trade_no',
            'trade_no'      => 'transaction_id',
            'trade_status'  => 'result_code',
            'notify_time'   => 'time_end',
            'cash_fee'      => 'cash_fee',
         );
    }

    /**
     * {@inheritDoc}
     */
    protected function configureOptions(OptionsResolverInterface $resolver)
    {
        parent::configureOptions($resolver);
        
        switch($this->model)
        {
            //沙箱模式
            case 'DEV':
                $resolver->setDefaults(array(
                    'authorization_url' => 'https://open.weixin.qq.com/connect/oauth2/authorize',
                    'qrcodeauthorization_url' => 'https://open.weixin.qq.com/connect/qrconnect',
                    'access_token_url' => 'https://api.weixin.qq.com/sns/oauth2/access_token',
                    'infos_url' => 'https://api.weixin.qq.com/sns/userinfo',
                    'me_url' => 'https://api.weixin.qq.com/sns/userinfo',
                    'orderquery'=>'https://api.mch.weixin.qq.com/sandboxnew/pay/orderquery',
                    'unifiedorder'=>'https://api.mch.weixin.qq.com/sandboxnew/pay/unifiedorder',
                    'signkeyurl'=>'https://api.mch.weixin.qq.com/sandboxnew/pay/getsignkey',
                ));
                break;
                //默认为生产模式
            default:
                $resolver->setDefaults(array(
                    'authorization_url' => 'https://open.weixin.qq.com/connect/oauth2/authorize',
                    'qrcodeauthorization_url' => 'https://open.weixin.qq.com/connect/qrconnect',
                    'access_token_url' => 'https://api.weixin.qq.com/sns/oauth2/access_token',
                    'infos_url' => 'https://api.weixin.qq.com/sns/userinfo',
                    'me_url' => 'https://api.weixin.qq.com/sns/userinfo',
                    'orderquery'=>'https://api.mch.weixin.qq.com/pay/orderquery',
                    'unifiedorder'=>'https://api.mch.weixin.qq.com/pay/unifiedorder',
                ));
                break;
        }

        
    }

    /**
     * {@inheritDoc}
     */
    protected function validateResponseContent($response)
    {
        if (isset($response['errmsg']))
            throw new \Exception(sprintf('OAuth error: "%s"', $response['errmsg']));
    }
}