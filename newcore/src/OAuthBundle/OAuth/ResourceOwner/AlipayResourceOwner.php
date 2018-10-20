<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年5月5日
*/
namespace OAuthBundle\OAuth\ResourceOwner;

use OAuthBundle\AliPay\Aop\AopClient;
use Symfony\Component\HttpFoundation\Request;
use OAuthBundle\AliPay\Aop\Request\AlipayTradeQueryRequest;
use OAuthBundle\AliPay\Aop\Request\AlipayTradePagePayRequest;
use OAuthBundle\AliPay\Aop\Request\AlipayTradeWapPayRequest;
use OAuthBundle\Security\Core\Authentication\Token\OAuthToken;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AlipayResourceOwner extends GenericOAuth2ResourceOwner
{
    /**
     * {@inheritdoc}
     */
    protected $paths = array(
        'oauthid'   => 'user_id',
        'nickname'  => 'nickname',
        'realname'  => 'nickname',
        'headimage' => 'avatar',
        'location'  => array('province','city'),
    );
    
    protected $model = "PROD";//上线模式
    //protected $model = "DEV";//沙箱模式
    protected $private_key;
    protected $sign_type = "RSA";
    
    protected $postCharset = "UTF-8";
    protected $fileCharset = 'UTF-8';
    //返回数据格式
    protected $format = "json";
    //api版本
    protected $apiVersion = "1.0";
    
    /**
     * {@inheritDoc}
     */
    public function getAuthorizationUrl($redirectUri, array $extraParameters = array())
    {
        if ($this->options['csrf']) {
            if (null === $this->state)
                $this->state = $this->generateNonce();
    
            $this->storage->save($this, $this->state, 'csrf_state');
        }

        $parameters = array_merge(array(
            'app_id'     => $this->options['client_id'],
            'scope'         => $this->options['scope']?$this->options['scope']:"auth_user",
            'state'         => $this->state ? urlencode($this->state) : null,
            'redirect_uri'  => $redirectUri,
        ), $extraParameters);
    
        return $this->normalizeUrl($this->options['authorization_url'], $parameters);
    }
    
    public function getAopClient()
    {
        //初始化的AlipayClient
        $aop = new AopClient();
        $aop->gatewayUrl = $this->options['access_token_url'];
        $aop->appId = $this->options['client_id'];
        $aop->rsaPrivateKey = self::getPrivateKey();
        $aop->alipayrsaPublicKey= self::getAlipayPublicKey();
        $aop->apiVersion = '1.0';
        $aop->signType = $this->sign_type;
        $aop->postCharset= "utf-8";
        $aop->format='json';
        
        return $aop;
    }
    
    /**
     * 
     * 支付宝支付
     * 
     * @param: $mode，page-pc网站支付，wap-手机网站支付
     * 
     * @return:
     */ 
  
    public function getAlipaySubmit(array $data,$mode = 'page')
    {
        $config = self::getConfig();
        
        $parameter = array();
        $parameter['charset'] = trim(strtolower($config['input_charset']));
        $parameter['out_trade_no'] = $data['ordersn'];
        $parameter['product_code'] = 'FAST_INSTANT_TRADE_PAY';
        $parameter['total_amount'] = $data['fee'];
        $parameter['subject'] = isset($this->consume[$data['consume']])?$this->consume[$data['consume']]:'充值';
        $parameter['body'] = isset($this->consume[$data['consume']])?$this->consume[$data['consume']]:'充值';
 
        $aop = self::getAopClient();
        
        //创建API对应的request
        switch ($mode) {
            case 'wap':
                $parameter['product_code'] = 'QUICK_WAP_PAY';
                $payreQuest = new AlipayTradeWapPayRequest();
                break;
            default:
                $payreQuest = new AlipayTradePagePayRequest();
                break;
        }
        
        $payreQuest->setReturnUrl($config['return_url']);
        
        //在公共参数中设置回跳和通知地址
        $payreQuest->setNotifyUrl($config['notify_url']);
        
        //填充业务参数
        $payreQuest->setBizContent(json_encode($parameter));

        return $aop->pageExecute($payreQuest, 'GET');
    }
    
    /**
     * 订单检验查询
     */
    public function orderQuery($info)
    {
        
    }
    
    /**
     * 支付宝订单状态查询
     * @param unknown $data
     */
    public function tradeQueryRequest(array $data)
    {
        $parameter = array();
        $parameter['out_trade_no'] = isset($data['out_trade_no'])?$data['out_trade_no']:'';
        $parameter['trade_no'] = isset($data['trade_no'])?$data['trade_no']:'';

        //初始化的AlipayClient
        $aop = self::getAopClient();
        
        $tradeQueryRequest = new AlipayTradeQueryRequest();
        
        $tradeQueryRequest->setBizContent(json_encode($parameter));
        
        $response = $aop->execute($tradeQueryRequest);
        
        $responseNode = str_replace(".", "_", $tradeQueryRequest->getApiMethodName()) . "_response";
        
        $response = self::object_to_array($response->{$responseNode});
        
        if($response['code']!=10000)
            return false;

        return true;
    }
    
    public function getAccessToken(Request $request, $redirectUri, array $extraParameters = array())
    {
        //组装系统参数
        $extraParameters = array_merge(array(
            'app_id'    => $this->options['client_id'],
            'version'   => "1.0",
            'format'    => $this->format,
            'sign_type' => $this->sign_type,
            'method'    => "alipay.system.oauth.token",
            'timestamp' => date("Y-m-d H:i:s"),
            'charset'   => "utf-8",
            'code'      => $request->get('auth_code'),
            'grant_type'=> 'authorization_code',
        ), $extraParameters);
        
        $extraParameters["sign"] = self::rsaSign($extraParameters);
        
        $response = $this->doGetTokenRequest($this->options['access_token_url'], $extraParameters);
        $response = $this->getResponseContent($response);

        $this->validateResponseContent($response['alipay_system_oauth_token_response']);

        return $response['alipay_system_oauth_token_response'];        
    }
    
    public function getUserInformation(array $accessToken, array $extraParameters = array())
    {        
        //组装系统参数        
        $extraParameters = array_merge(array(
            'app_id'    => $this->options['client_id'],
            'version'   => "1.0",
            'format'    => $this->format,
            'sign_type' => $this->sign_type,
            'method'    => "alipay.user.info.share",
            'timestamp'  => date("Y-m-d H:i:s"),
            'auth_token' => $accessToken['access_token'],
            'charset' => "utf-8",
        ), $extraParameters);
        
        $extraParameters["sign"] = self::rsaSign($extraParameters);

        $content = $this->doGetUserInformationRequest($this->normalizeUrl($this->options['infos_url'], $extraParameters));
        $content = json_decode($content->getContent(), true);
        
        //检验
        self::validateResponseContent($content);

        if(!isset($content['alipay_user_info_share_response']['code']))
            throw new \Exception(sprintf('OAuth error: "%s"', '请求超时'));

        $content = $content['alipay_user_info_share_response'];
        
        if($content['code']!=10000)
            throw new \Exception(sprintf('OAuth error: "%s"', $content['sub_msg']));
        
        
        $response = $this->getUserResponse();
        $response->setResponse($content);
        
        $response->setResourceOwner($this);
        $response->setOAuthToken(new OAuthToken($accessToken));
        
        return $response;
    }

    /**
     * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
     * @param $para 需要拼接的数组
     * return 拼接完成以后的字符串
     */
    public function createLinkstringback($para)
    {
        ksort($para);
        reset($para);
        $arg = "";
        //while (list ($key, $val) = each ($para)) {
        foreach($para as $key=>$val)
        {
            $arg.=$key."=".$val."&";
        }

        //去掉最后一个&字符
        $arg = substr($arg,0,count($arg)-2);
    
        //如果存在转义字符，那么去掉转义
        if(get_magic_quotes_gpc())
            $arg = stripslashes($arg);
    
        return $arg;
    }
    
    public function createLinkstring($params)
    {
        $params = self::paraFilter($params);
        ksort($params);
        reset($params);
    
        $stringToBeSigned = "";
        $i = 0;
        foreach ($params as $k => $v)
        {
            if (false === self::checkEmpty($v) && "@" != substr($v, 0, 1))
            {
    
                // 转换成目标字符集
                $v = self::characet($v, $this->postCharset);
    
                if ($i == 0) {
                    $stringToBeSigned .= "$k" . "=" . "$v";
                } else {
                    $stringToBeSigned .= "&" . "$k" . "=" . "$v";
                }
                $i++;
            }
        }
    
        unset ($k, $v);
        return $stringToBeSigned;
    }
    
    /**
     * 校验$value是否非空
     *  if not set ,return true;
     *    if is null , return true;
     **/
    protected function checkEmpty($value)
    {
        if (!isset($value))
            return true;
        if ($value === null)
            return true;
        if (trim($value) === "")
            return true;

        return false;
    }
    
    /**
     * 转换字符集编码
     * @param $data
     * @param $targetCharset
     * @return string
     */
    public function characet($data, $targetCharset)
    {
        if (!empty($data))
        {
            $fileType = $this->fileCharset;
            if (strcasecmp($fileType, $targetCharset) != 0)
                $data = mb_convert_encoding($data, $targetCharset, $fileType);
        }

        return $data;
    }
    
    public function md5Sign($prestr, $key)
    {
        $prestr = self::createLinkstring($prestr);
        $prestr = $prestr . $key;
        return md5($prestr);
    }
    
    /**
     * RSA签名
     * @param $data 待签名数据
     * @param $private_key 商户私钥字符串
     * 注 php5.33不支持RSA2
     * return 签名结果
     */
    
    public function rsaSign($prestr, $private_key=null)
    {
        $private_key = $private_key?$private_key:self::getPrivateKey();
        $prestr = self::createLinkstring($prestr);

        $sign = "";
        //以下为了初始化私钥，保证在您填写私钥时不管是带格式还是不带格式都可以通过验证。
        $private_key=str_replace("-----BEGIN RSA PRIVATE KEY-----","",$private_key);
        $private_key=str_replace("-----END RSA PRIVATE KEY-----","",$private_key);
        $private_key=str_replace("-----BEGIN PRIVATE KEY-----","",$private_key);
        $private_key=str_replace("-----END PRIVATE KEY-----","",$private_key);
        $private_key=str_replace("\n","",$private_key);
    
        $private_key="-----BEGIN RSA PRIVATE KEY-----".PHP_EOL .wordwrap($private_key, 64, "\n", true). PHP_EOL."-----END RSA PRIVATE KEY-----";

        $res = openssl_get_privatekey($private_key);
        
        if($res)
            openssl_sign($prestr, $sign,$res);
        else
            throw new \LogicException("您的私钥格式不正确!"."<br/>"."The format of your private_key is incorrect!");
    
        openssl_free_key($res);

        //base64编码
        $sign = base64_encode($sign);

        return $sign;
    }
    
    /**
     * 除去数组中的空值和签名参数
     * @param $para 签名参数组
     * return 去掉空值与签名参数后的新签名参数组
     */
    public function paraFilter($para)
    {
        $para_filter = array();

        foreach($para as $key=>$val)
        {
            if($key == "sign"||$val == "")
                continue;
    
            $para_filter[$key] = $para[$key];
        }
        return $para_filter;
    }
    
    protected function validateResponseContent($response)
    {
        if (!$response)
            throw new \Exception(sprintf('OAuth error: "%s"', "认证失败"));
        
        if (isset($response['error_response']))
            throw new \Exception(sprintf('OAuth error: "%s"', $response['error_response']['sub_msg']));
    }
    
    public function getConfig()
    {
        $redirectUrl = $this->get('router.request_context')->getScheme();
        $redirectUrl .= $redirectUrl?'://':"";
        $redirectUrl .= $this->get('core.common')->C('maindomain');
        
        return array(
    
            //合作身份者ID，签约账号，以2088开头由16位纯数字组成的字符串，查看地址：https://b.alipay.com/order/pidAndKey.htm
            'partner' => trim($this->get('core.common')->C('alipay_pid')),
    
            //收款支付宝账号，以2088开头由16位纯数字组成的字符串，一般情况下收款账号就是签约账号
            'seller_id' => trim($this->get('core.common')->C('alipay_pid')),
    
            //商户的私钥,此处填写原始私钥去头去尾，RSA公私钥生成：https://doc.open.alipay.com/doc2/detail.htm?spm=a219a.7629140.0.0.nBDxfy&treeId=58&articleId=103242&docType=1
            'private_key' => file_get_contents ( getcwd().'/alipaycert/rsa_private_key.pem' ),
    
            'alipay_key' => trim($this->get('core.common')->C('alipay_key')),
            //支付宝的公钥，查看地址：https://b.alipay.com/order/pidAndKey.htm
            'alipay_public_key' => file_get_contents ( getcwd().'/alipaycert/alipay_public_key.pem' ),
    
            //服务器异步通知页面路径需http://格式的完整路径，不能加?id=123这类自定义参数，必须外网可以正常访问
            //'notify_url' => 'http://app.08cms.com/notify/alipay',
            'notify_url' => $redirectUrl.'/notify/alipay',
    
            //页面跳转同步通知页面路径,需http://格式的完整路径，不能加?id=123这类自定义参数，必须外网可以正常访问
            //'return_url' => 'http://app.08cms.com/return/alipay',
            'return_url' => $redirectUrl.'/return/alipay',
    
            //签名方式
            'sign_type' => strtoupper('RSA'),//strtoupper('MD5')RSA
    
            //字符编码格式 目前支持 gbk 或 utf-8
            'input_charset' => strtolower('utf-8'),
    
            //ca证书路径地址，用于curl中ssl校验,请保证cacert.pem文件在当前文件夹目录中
            'cacert' => getcwd().'/cert/cacert.pem',
    
            //日志文件地址
            'paylog' => getcwd().'/logs/alipaylog.txt',
    
            //访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
            'transport' => 'http',
    
            //支付类型 无需修改
            'payment_type' => '1',
    
            //产品类型 无需修改
            'service' => 'create_direct_pay_by_user',
    
            //防钓鱼时间戳 若要使用请调用类文件submit中的query_timestamp函数
            'anti_phishing_key' => '',
    
            //客户端的IP地址 非局域网的外网IP地址，如：221.0.0.1
            'exter_invoke_ip' => '',
        );
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
        //执行退款处理
        if(isset($data['batch_no']) && isset($data['success_num']) && isset($data['result_details']))
            return self::refundHandle($data);
        
        
        //如果订单未支付成功，则失败
        if(!self::tradeQueryRequest($data))
            die("fail");

        $parameter = self::getParameter();
        
        foreach($parameter as &$v)
        {
            $v = isset($data[$v])?$data[$v]:"";
        }

        /**
         * 判断该笔订单是否在商户网站中已经做过处理
         * 如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
         * 请务必判断请求时的total_fee、seller_id与通知时获取的total_fee、seller_id为一致的
         * 注意：付款完成后，支付宝系统发送该交易状态通知
         */
        $this->get('db.orderlist')->orderHandleByAlipay($parameter);
    }
    
    public function getParameter()
    {
        return array(
            'ordersn'       => 'out_trade_no',
            'trade_no'      => 'trade_no',
            'trade_status'  => 'trade_status',
            'total_fee'     => 'total_amount',
            'notify_id'     => 'notify_id',
            'notify_time'   => 'notify_time',
            'seller_id'     => 'seller_id'
        );
    }
    
    /**
     * 验签方法
     * 验签支付宝返回的信息，使用支付宝公钥
     */
    public function verifyNotify($data=null)
    {
        $file = $this->get('core.common')->getTempRoot()."alipay_notify.txt";
        
        $this->get('core.common')->saveFile($file, $data);

        $aop = self::getAopClient();
        $result = $aop->rsaCheckV1($data?$data:$_POST, self::getAlipayPublicKey(), $this->sign_type);
        return $result;
    }
    
    public function checkSignature($data, $TOKEN)
    {
        return true;
    }
    
    /**
     * {@inheritdoc}
    */
    protected function configureOptions(OptionsResolverInterface $resolver)
    {
        parent::configureOptions($resolver);

        switch($this->model)
        {
            //沙箱模式
            case 'DEV':
                $resolver->setDefaults(array(
                    'authorization_url' => 'https://openauth.alipaydev.com/oauth2/publicAppAuthorize.htm',
                    'request_token_url' => 'https://openapi.alipaydev.com/gateway.do',
                    'access_token_url'  => 'https://openapi.alipaydev.com/gateway.do',
                    'infos_url'         => 'https://openapi.alipaydev.com/gateway.do',
                ));
                break;
            //默认为生产模式
            default:
                $resolver->setDefaults(array(
                    'authorization_url' => 'https://openauth.alipay.com/oauth2/publicAppAuthorize.htm',
                    'request_token_url' => 'https://openapi.alipay.com/gateway.do',
                    'access_token_url'  => 'https://openapi.alipay.com/gateway.do',
                    'infos_url'         => 'https://openapi.alipay.com/gateway.do',
                ));
                break;
        }
    }
    
    protected function object_to_array($obj)
    {
        if(!is_object($obj))
            return $obj;

        $obj = (array)$obj;
        foreach ($obj as $k => $v)
        {
            if (gettype($v) == 'resource')
                return;

            if (gettype($v) == 'object' || gettype($v) == 'array')
                $obj[$k] = (array)self::object_to_array($v);
        }
    
        return $obj;
    }
    
    /**
     * 开发者私钥
     */
    protected function getPrivateKey()
    {
        return file_get_contents ( getcwd().'/alipaycert/rsa_private_key.pem' );
    }
    
    /**
     * 开发者公钥
     */
    protected function getPublicKey()
    {
        return file_get_contents ( getcwd().'/alipaycert/rsa_public_key.pem' );
    }
    
    /**
     * 支付宝公钥(由支付宝生成)
     */
    protected function getAlipayPublicKey()
    {
        return file_get_contents ( getcwd().'/alipaycert/alipay_public_key.pem' );
    }
}