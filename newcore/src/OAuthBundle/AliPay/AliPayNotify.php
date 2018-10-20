<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年7月20日
*/
namespace OAuthBundle\AliPay;

class AliPayNotify extends AliPay
{

    //HTTPS形式消息验证地址
    private $https_verify_url = 'https://mapi.alipay.com/gateway.do?service=notify_verify&';
    
    //HTTP形式消息验证地址
    private $http_verify_url = 'http://notify.alipay.com/trade/notify_query.do?';

    /**
     * 针对notify_url验证消息是否是支付宝发出的合法消息
     *
     * @return  验证结果
     */
    public function verifyNotify()
    {

        if(empty($_POST)){ //判断POST来的数组是否为空
            return false;
        }else{

            #生成签名结果
            $isSign = $this->getSignVeryfy($_POST, $_POST["sign"]);

            #获取支付宝远程服务器ATN结果（验证是否是支付宝发来的消息）
            $responseTxt = 'false';

            if (!empty($_POST["notify_id"]))
                $responseTxt = $this->getResponse($_POST["notify_id"]);
            
            #写日志记录
            if($isSign){
              $isSignStr = 'true';
            }else{
              $isSignStr = 'false';
            }
            $log_text = "responseTxt=".$responseTxt."\n notify_url_log:isSign=".$isSignStr.",";
            $log_text = $log_text.$this->resourceOwner->createLinkString($_POST);
            $this->logResult($log_text);
            
            #验证
            //$responsetTxt的结果不是true，与服务器设置问题、合作身份者ID、notify_id一分钟失效有关
            //isSign的结果不是true，与安全校验码、请求时的参数格式（如：带自定义参数等）、编码格式有关
            if(preg_match("/true$/i",$responseTxt) && $isSign){
                return true;
            }else{
                return false;
            }
        }
    }
    
    /**
     * 针对return_url验证消息是否是支付宝发出的合法消息
     *
     * @return 验证结果
     */
    public function verifyReturn()
    {
        if(empty($_GET)){ //判断GET来的数组是否为空
            return false;
        }else{

            #生成签名结果
            $isSign = $this->getSignVeryfy($_GET, $_GET["sign"]);
            
            

            #获取支付宝远程服务器ATN结果（验证是否是支付宝发来的消息）
            $responseTxt = 'false';

            if (!empty($_GET["notify_id"]))
                $responseTxt = $this->getResponse($_GET["notify_id"]);

            #写日志记录
            /*if($isSign){
              $isSignStr = 'true';
            }else{
              $isSignStr = 'false';
            }
            $log_text = "responseTxt=".$responseTxt."\n return_url_log:isSign=".$isSignStr.",";
            $log_text = $log_text.$this->resourceOwner->createLinkString($_GET);
            $this->logResult($log_text);*/
            
            #验证
            //$responsetTxt的结果不是true，与服务器设置问题、合作身份者ID、notify_id一分钟失效有关
            //isSign的结果不是true，与安全校验码、请求时的参数格式（如：带自定义参数等）、编码格式有关
            if (preg_match("/true$/i",$responseTxt) && $isSign){
                return true;
            }else{
                return false;
            }
        }
    }
    
    /**
     * 获取返回时的签名验证结果
     * @param array  $para_temp  通知返回来的参数数组
     * @param string $sign       返回的签名结果
     * @return 签名验证结果
     */
    private function getSignVeryfy($para_temp, $sign)
    {
        #除去待签名参数数组中的空值和签名参数
        $para_filter = parent::paraFilter($para_temp);
        
        #对待签名参数数组排序
        $para_sort = parent::argSort($para_filter);
        
        #把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
        $prestr = parent::createLinkstring($para_sort);
        
        $isSgin = false;
        switch (strtoupper(trim($this->alipay_config['sign_type']))){
            case "MD5" :
                $isSgin = $this->resourceOwner->md5Verify($prestr, $sign, $this->alipay_config['alipay_key']);
                break;
            case "RSA":
            case "RSA2":
                $isSgin = $this->resourceOwner->rsaVerify($prestr, $sign, $this->alipay_config['alipay_public_key']);
                break;
            default :
                $isSgin = false;
        }       
        return $isSgin;
    }

    /**
     * 获取远程服务器ATN结果,验证返回URL
     * @param string $notify_id 通知校验ID
     * @return 服务器ATN结果
     * 验证结果集：
     * invalid 命令参数不对 出现这个错误，请检测返回处理中partner和key是否为空 
     * true    返回正确信息
     * false   请检查防火墙或者是服务器阻止端口问题以及验证时间是否超过一分钟
     */
    private function getResponse($notify_id)
    {
        $transport = strtolower(trim($this->alipay_config['transport']));
        $partner = trim($this->alipay_config['partner']);
        
        $veryfy_url = '';

        if($transport == 'https')
            $veryfy_url = $this->https_verify_url;
        else
            $veryfy_url = $this->http_verify_url;

        $veryfy_url = $veryfy_url."partner=" . $partner . "&notify_id=" . $notify_id; 
        $responseTxt = $this->resourceOwner->HttpRequestCurl($veryfy_url, '', 'get', array('CURLOPT_CAINFO' => $this->alipay_config['cacert']));

        return $responseTxt->getContent();
    }

    /**
     * 写日志，方便测试（看网站需求，也可以改成把记录存入数据库）
     * 注意：服务器需要开通fopen配置
     * @param $word 要写入日志里的文本内容 默认值：空值
     */
    public function logResult($word=''){

        $fp = fopen($this->alipay_config['paylog'], "a");
        flock($fp, LOCK_EX) ;
        fwrite($fp,"执行日期：".date('Y-m-d H:i:s',time())."\n".$word."\n");
        flock($fp, LOCK_UN);
        fclose($fp);
    }

}