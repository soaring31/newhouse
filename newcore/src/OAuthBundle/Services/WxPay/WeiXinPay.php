<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年6月23日
*/
namespace OAuthBundle\Services\WxPay;

use OAuthBundle\Services\WeiXin\WeiXin;

class WeiXinPay extends WeiXin
{

    protected $values  = array();
    protected $config  = array();
    private $srcurl  = 'http://paysdk.weixin.qq.com/example/qrcode.php?data=';
    private $posturl = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
    private $porturl = 'https://api.mch.weixin.qq.com/payitil/report';

    /**
     * REPORT_LEVENL：接口调用上报等级，默认为错误上报（注意：上报超时间为【1s】，上报无论成败【永不抛出异常】，不会影响接口调用流程）。
     * 开启上报之后，可以方便微信监控请求调用的质量，建议至少开启错误上报。
     * 上报等级：0.关闭上报; 1.仅错误出错上报; 2.全量上报
     * @var int
     */
    const REPORT_LEVENL = 1;

    /**
     * 生成扫描支付URL,模式一（需要设置回调地址）call...
     * 
     * @param string  $productId  商品订单号，必填。
     */
    public function GetPrePayUrl($productId)
    {
        if(empty($productId))
            throw new \InvalidArgumentException('生成二维码，缺少必填参数product_id！');

        $this->values = array(
            'appid' => $this->appId,
            'mch_id' => $this->mchid,
            'nonce_str' => $this->getNonceStr(),
            'product_id' => $productId,
            'time_stamp' => time(),
        );
        $this->values['sign'] = $this->MakeSign($this->config['paysignkey']);

        $url = "weixin://wxpay/bizpayurl?" . $this->ToUrlParams($this->paysignkey);
        return $this->srcurl.urlencode($url);
    }
    
    /**
     * 生成直接支付url，支付url有效期为2小时,模式二(推荐！)
     * 
     * @param array   $input         订单参数，见下~
     * @param string  $trade_type    交易类型，分为NATIVE和JSAPI两种，默认NATIVE
     */
    public function GetPayUrl(array $input, $trade_type = "URL")
    {
        if(!isset($input['body']))
            throw new \InvalidArgumentException('_缺少参数商品描述: body');
        if(!isset($input['attach']))
            throw new \InvalidArgumentException('缺少参数商品详情: attach');
        if(!isset($input['total_fee']))
            throw new \InvalidArgumentException('_缺少参数商品总金额: total_fee');
        if(!isset($input['goods_tag']))
            throw new \InvalidArgumentException('缺少参数商品标签: goods_tag');
        if(!isset($input['product_id']))
            throw new \InvalidArgumentException('_缺少参数商品订单号: product_id');

        if($trade_type != "JSAPI" && $trade_type != "NATIVE" && $trade_type != "URL")
            throw new \InvalidArgumentException('_交易类型参数错误：trade_type');

        $this->values = $input;

        #微信商户订单号
        $this->values['out_trade_no'] = $input['product_id'];//$this->get('core.common')->makeOrderId();
        #订单创建时间
        $this->values['time_start'] = date("YmdHis");
        #订单失效时间
        $this->values['time_expire'] = date("YmdHis", time() + 600);
        #回调地址
        $this->values['notify_url'] = $this->get('core.common')->U('weixin/notify/'.$this->token, '', true);
        #交易类型
        $this->values['trade_type'] = ($trade_type == "URL" ? "NATIVE" : $trade_type);

        $this->values['appid'] = $this->appId;
        $this->values['mch_id'] = $this->mchid;
        $this->values['nonce_str'] = $this->getNonceStr();
        $this->values['spbill_create_ip'] = $_SERVER['REMOTE_ADDR'];

        $this->values['sign'] = $this->MakeSign($this->paysignkey);

        $xml = $this->ToXml();


        $startTimeStamp = $this->getMillisecond();
        //$cert = $this->getPostCert(); //是否使用证书，切换证书检测请注释本条，否则在下面加上

        $response = $this->resourceOwner->HttpRequestCurl($this->posturl, $xml, 'post'); //$cert

        #$response = $this->resourceOwner->httpRequest($this->posturl, $xml);
        $result = $this->FromXml($response->getContent());
        $this->reportCostTime($startTimeStamp, $this->values); //上报请求花费时间

        dump($trade_type,$result['code_url']);die();
        if($trade_type == 'URL')
            return $this->srcurl.urlencode($result['code_url']);
        else
            return $result;

    }

    /**
     * 
     * 设置参数
     * @param string $key
     * @param string $value
     */
    public function SetData($key, $value)
    {
        $this->values[$key] = $value;
    }

    /**
     * 
     * 获取post证书,设置代理
     * @param WxPayException
     */
    private function getPostCert()
    {
        $cert = getcwd().'\\cert\apiclient_cert.pem';
        if(!is_file($cert))
            throw new \InvalidArgumentException('_缺少证书文件: apiclient_cert.pem');
        $key = getcwd().'\\cert\apiclient_key.pem';
        if(!is_file($key))
            throw new \InvalidArgumentException('_缺少证书文件: apiclient_key.pem');
        return array(
            'SSLCERT_PATH' => $cert,
            'SSLKEY_PATH'  => $key,
            'CURL_PROXY_HOST' => "0.0.0.0", //代理，例如:10.152.18.220
            'CURL_PROXY_PORT' => 0,         //端口，例如:8080
        );
    }

    /**
     * 
     * 参数数组转换为url参数
     * @param array $urlObj
     */
    protected function ToUrlParams($urlObj)
    {
        $buff = "";
        foreach ($urlObj as $k => $v)
        {
            if($k != "sign"){
                $buff .= $k . "=" . $v . "&";
            }
        }      
        $buff = trim($buff, "&");
        return $buff;
    }

    /**
     * 
     * 产生随机字符串，不长于32位
     * @param int $length
     * @return 产生的随机字符串
     */
    protected function getNonceStr($length = 32) 
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";  
        $str ="";
        for ( $i = 0; $i < $length; $i++ )  {  
            $str .= substr($chars, mt_rand(0, strlen($chars)-1), 1);  
        } 
        return $str;
    }

    /**
     * 生成签名
     * @return 签名，本函数不覆盖sign成员变量，如要设置签名需要调用SetSign方法赋值
     */
    protected function MakeSign($paysignkey)
    {
        if(empty($this->values) || empty($paysignkey))
            return false;

        #签名步骤一：按字典序排序参数
        ksort($this->values);
        $string = $this->ToUrlParams($this->values);
        #签名步骤二：在string后加入KEY
        $string = $string . "&key=".$paysignkey;
        #签名步骤三：MD5加密
        $string = md5($string);
        #签名步骤四：所有字符转为大写
        $result = strtoupper($string);
        return $result;
    }

    /**
     * 
     * 检测签名
     */
    protected function CheckSign()
    {
        if(!isset($this->values['sign'])){
            throw new \InvalidArgumentException("签名错误！");
        }
        
        $sign = $this->MakeSign($this->paysignkey);

        if($this->values['sign'] == $sign)
            return true;

        throw new \InvalidArgumentException("签名错误！");
    }

    /**
     * 输出xml字符
     * @throws WxPayException
     */
    protected function ToXml()
    {
        if(!is_array($this->values) || count($this->values)<=0)
            throw new \InvalidArgumentException("数组数据异常！");
        
        $xml = "<xml>";
        foreach ($this->values as $key=>$val)
        {
            if (is_numeric($val)){
                $xml.="<".$key.">".$val."</".$key.">";
            }else{
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.="</xml>";
        return $xml; 
    }

    /**
     * 将xml转为array
     * @param string $xml
     * @throws WxPayException
     */
    public function FromXml($xml)
    {
        if(!$xml)
            throw new \InvalidArgumentException("xml数据异常！");
        
        $encryptType = $this->get('request')->get('encrypt_type','raw');

        switch($encryptType)
        {
            //aes加密
            case 'aes':
                $postObj = parent::decryptMsg($xml);
                break;
            //不加密
            case 'raw':
                #将XML转为array，禁止引用外部xml实体
                libxml_disable_entity_loader(true);
                $postObj = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);

                break;
        }

        //转成数组
        $this->values = json_decode(json_encode($postObj), true);

        if($this->values['return_code'] != 'SUCCESS')
            throw new \InvalidArgumentException("二维码获取失败，请检查！");
        
        $this->CheckSign();

        return $this->values;
    }

    /**
     * 获取毫秒级别的时间戳
     */
    protected function getMillisecond()
    {
        $time = explode ( " ", microtime () );
        $time = $time[1] . ($time[0] * 1000);
        $time2 = explode( ".", $time );
        $time = $time2[0];
        return $time;
    }

    /**
     * 
     * 上报数据， 上报的时候将屏蔽所有异常流程
     * @param string $usrl
     * @param int $startTimeStamp
     * @param array $data
     */
    private function reportCostTime($startTimeStamp, $data)
    {
        #如果不需要上报数据
        if(self::REPORT_LEVENL == 0)
            return false;

        #如果仅失败上报
        if(self::REPORT_LEVENL == 1 && array_key_exists("return_code", $data) && $data["return_code"] == "SUCCESS" && array_key_exists("result_code", $data) && $data["result_code"] == "SUCCESS")
            return false;
         
        //上报逻辑
        $endTimeStamp = $this->getMillisecond();
        $this->values = array();
        $this->values['interface_url'] = $this->posturl;
        $this->values['execute_time_'] = $endTimeStamp - $startTimeStamp;
        //返回状态码
        if(array_key_exists("return_code", $data))
            $this->values['return_code'] = $data["return_code"];

        //返回信息
        if(array_key_exists("return_msg", $data))
            $this->values['return_msg'] = $data["return_msg"];

        //业务结果
        if(array_key_exists("result_code", $data))
            $this->values['result_code'] = $data["result_code"];

        //错误代码
        if(array_key_exists("err_code", $data))
            $this->values['err_code'] = $data["err_code"];
        
        //错误代码描述
        if(array_key_exists("err_code_des", $data))
            $this->values['err_code_des'] = $data["err_code_des"];

        //商户订单号
        if(array_key_exists("out_trade_no", $data))
            $this->values['out_trade_no'] = $data["out_trade_no"];

        //设备号
        if(array_key_exists("device_info", $data))
            $this->values['device_info'] = $data["device_info"];
    
        try{
            $this->report();
        } catch (\Exception $e){
            #不做任何处理...
        }

    }

    /**
     * 
     * 测速上报，该方法内部封装在report中，使用时请注意异常流程
     * WxPayReport中interface_url、return_code、result_code、user_ip、execute_time_必填
     * appid、mchid、spbill_create_ip、nonce_str不需要填入
     * @param WxPayReport $inputObj
     * @param int $timeOut
     * @throws WxPayException
     * @return 成功时返回，其他抛异常
     */
    protected function reportback()
    {
        //检测必填参数
        if(!isset($this->values['interface_url']))
            throw new \InvalidArgumentException('接口URL，缺少必填参数:interface_url！');
        if(!isset($this->values['return_code']))
            throw new \InvalidArgumentException('返回状态码，缺少必填参数:return_code！');

        if(!isset($this->values['execute_time_']))
            throw new \InvalidArgumentException('接口耗时，缺少必填参数:execute_time_！');

        $this->values['appid'] = $this->appId;
        $this->values['mch_id'] = $this->mchid;
        $this->values['nonce_str'] = $this->getNonceStr();
        $this->values['user_ip'] = $_SERVER['REMOTE_ADDR'];
        $this->values['time'] = date("YmdHis");

        $this->values['sign'] = $this->MakeSign($this->paysignkey);
        $xml = $this->ToXml();
        
        //$startTimeStamp = self::getMillisecond();//请求开始时间
        $response = parent::httpRequestCurl($this->porturl, $xml, 'post');
        dump($response);die();
        return $response;
    }
    
    /**
     *
     * 测速上报，该方法内部封装在report中，使用时请注意异常流程
     * WxPayReport中interface_url、return_code、result_code、user_ip、execute_time_必填
     * appid、mchid、spbill_create_ip、nonce_str不需要填入
     * @param WxPayReport $inputObj
     * @param int $timeOut
     * @throws WxPayException
     * @return 成功时返回，其他抛异常
     */
    public function report($inputObj, $timeOut = 1)
    {

        $url = "https://api.mch.weixin.qq.com/payitil/report";

        //检测必填参数
        if(!$inputObj->IsInterface_urlSet())
            throw new WxPayException("接口URL，缺少必填参数interface_url！");

         if(!$inputObj->IsReturn_codeSet()) {
            throw new WxPayException("返回状态码，缺少必填参数return_code！");
        } if(!$inputObj->IsResult_codeSet()) {
            throw new WxPayException("业务结果，缺少必填参数result_code！");
        } if(!$inputObj->IsUser_ipSet()) {
            throw new WxPayException("访问接口IP，缺少必填参数user_ip！");
        } if(!$inputObj->IsExecute_time_Set()) {
            throw new WxPayException("接口耗时，缺少必填参数execute_time_！");
        }

        $inputObj->SetAppid($this->appId);//公众账号ID
        $inputObj->SetMch_id($this->mchid);//商户号
        $inputObj->SetUser_ip($_SERVER['REMOTE_ADDR']);//终端ip
        $inputObj->SetTime(date("YmdHis"));//商户上报时间
        $inputObj->SetNonce_str(self::getNonceStr());//随机字符串

        $inputObj->SetSign($this->paysignkey);//签名
        
        $xml = $inputObj->ToXml();

        $response = parent::httpRequestCurl($url, $xml, 'post');

        return $response->getContent();
    }
    
    /**
     * 以post方式提交xml到对应的接口url
     *
     * @param string $xml  需要post的xml数据
     * @param string $url  url
     * @param bool $useCert 是否需要证书，默认不需要
     * @param int $second   url执行超时时间，默认30s
     * @throws WxPayException
     */
    protected function postXmlCurl($xml, $url, $useCert = false, $second = 30)
    {
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);

        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,TRUE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,2);//严格校验
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    
//         if($useCert == true){
//             //设置证书
//             //使用证书：cert 与 key 分别属于两个.pem文件
//             curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
//             curl_setopt($ch,CURLOPT_SSLCERT, WxPayConfig::SSLCERT_PATH);
//             curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
//             curl_setopt($ch,CURLOPT_SSLKEY, WxPayConfig::SSLKEY_PATH);
//         }
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        //运行curl
        $data = curl_exec($ch);
        dump($xml, $url, $data);die();
        //返回结果
        if($data){
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
            throw new WxPayException("curl出错，错误码:$error");
        }
    }

}