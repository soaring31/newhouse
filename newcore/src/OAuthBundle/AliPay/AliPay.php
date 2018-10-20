<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年6月22日
*/
namespace OAuthBundle\AliPay;

use CoreBundle\Services\ServiceBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AliPay extends ServiceBase
{
    protected $resourceOwner;
    protected $alipay_config;
    protected $name = 'alipay';
    
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;  
        $this->resourceOwner = $this->get('oauth.security.oauth_utils')->getResourceOwner($this->name);

        $errorCode = new ErrorCode();
        $this->errorCode = $errorCode->getText();
        $this->alipay_config = $this->getConfig();
    }

    public function getConfig()
    {
        return array(

            //合作身份者ID，签约账号，以2088开头由16位纯数字组成的字符串，查看地址：https://b.alipay.com/order/pidAndKey.htm
            'partner' => trim($this->get('core.common')->C('alipay_pid')),

            //收款支付宝账号，以2088开头由16位纯数字组成的字符串，一般情况下收款账号就是签约账号
            'seller_id' => trim($this->get('core.common')->C('alipay_pid')),
            
            //商户的私钥,此处填写原始私钥去头去尾，RSA公私钥生成：https://doc.open.alipay.com/doc2/detail.htm?spm=a219a.7629140.0.0.nBDxfy&treeId=58&articleId=103242&docType=1
            'private_key' => file_get_contents ( getcwd().'/alipaycert/rsa_private_key.pem' ),
            
            'alipay_key' => trim($this->get('core.common')->C('alipay_key')),
            //支付宝的公钥，查看地址：https://b.alipay.com/order/pidAndKey.htm 
            'alipay_public_key' => file_get_contents ( getcwd().'/alipaycert/rsa_public_key.pem' ),

            //服务器异步通知页面路径需http://格式的完整路径，不能加?id=123这类自定义参数，必须外网可以正常访问
            'notify_url' => 'http://dev.newcore.com/alipay/notify',
            //'notify_url' => $this->get('core.common')->U('/','',TRUE).'/OAuth/return/alinotify',

            //页面跳转同步通知页面路径,需http://格式的完整路径，不能加?id=123这类自定义参数，必须外网可以正常访问
            'return_url' => 'http://dev.newcore.com/app_dev.php/alireturn',
            //'return_url' => $this->get('core.common')->U('/','',TRUE).'/OAuth/return/alireturn',

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

    /**
     * 生成签名结果
     * @param $para_sort 已排序要签名的数组
     * return 签名结果字符串
     */
    public function buildRequestMysign($para_sort)
    {
        $mysign = "";

        switch (strtoupper(trim($this->alipay_config['sign_type']))) {
            case "MD5" :
                return $this->resourceOwner->md5Sign($para_sort, $this->alipay_config['alipay_key']);
                break;
            case "RSA" :
            case "RSA2" :
                return $this->resourceOwner->rsaSign($para_sort, $this->alipay_config['private_key']);
                break;
            default :
                $mysign = "";
        }     
        return $mysign;
    }

    /**
     * 错误检测
     * @param string $info
     * @throws \LogicException
     */
    public function getError($info)
    {
        if(!empty($info))
            throw new \Exception(isset($this->errorCode[$info])?$this->errorCode[$info].'['.$info.']':'['.$info.']');
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
            if($key == "sign" || $key == "sign_type" || $val == "")
                continue;
    
            $para_filter[$key] = $para[$key];
        }
        return $para_filter;
    }
    
    /**
     * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
     * @param $para 需要拼接的数组
     * return 拼接完成以后的字符串
     */
    public function createLinkstring($para)
    {
        $arg  = "";
        //while (list ($key, $val) = each ($para)) {
        foreach($para as $key=>$val)
        {
            $arg.=$key."=".$val."&";
        }
        //去掉最后一个&字符
        $arg = substr($arg,0,count($arg)-2);
    
        //如果存在转义字符，那么去掉转义
        if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}
    
        return $arg;
    }
    /**
     * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串，并对字符串做urlencode编码
     * @param $para 需要拼接的数组
     * return 拼接完成以后的字符串
     */
    public function createLinkstringUrlencode($para)
    {
        $arg  = "";
        //while (list ($key, $val) = each ($para)) {
        foreach($para as $key=>$val)
        {
            $arg.=$key."=".urlencode($val)."&";
        }
        //去掉最后一个&字符
        $arg = substr($arg,0,count($arg)-2);
    
        //如果存在转义字符，那么去掉转义
        if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}
    
        return $arg;
    }

    /**
     * 写日志，方便测试（看网站需求，也可以改成把记录存入数据库）
     * 注意：服务器需要开通fopen配置
     * @param $word 要写入日志里的文本内容 默认值：空值
     */
    public function logResult($word='')
    {
        $fp = fopen("log.txt","a");
        flock($fp, LOCK_EX) ;
        fwrite($fp,"执行日期：".strftime("%Y%m%d%H%M%S",time())."\n".$word."\n");
        flock($fp, LOCK_UN);
        fclose($fp);
    }
    
    /**
     * 远程获取数据，POST模式
     * 注意：
     * 1.使用Crul需要修改服务器中php.ini文件的设置，找到php_curl.dll去掉前面的";"就行了
     * 2.文件夹中cacert.pem是SSL证书请保证其路径有效，目前默认路径是：getcwd().'\\cacert.pem'
     * @param $url 指定URL完整路径地址
     * @param $cacert_url 指定当前工作目录绝对路径
     * @param $para 请求的数据
     * @param $input_charset 编码格式。默认值：空值
     * return 远程输出的数据
     */
    public function getHttpResponsePOST($url, $cacert_url, $para, $input_charset = '')
    {
        if (trim($input_charset) != '')
            $url = $url."_input_charset=".$input_charset;

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);//SSL证书认证
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);//严格认证
        curl_setopt($curl, CURLOPT_CAINFO,$cacert_url);//证书地址
        curl_setopt($curl, CURLOPT_HEADER, 0 ); // 过滤HTTP头
        curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
        curl_setopt($curl,CURLOPT_POST,true); // post传输数据
        curl_setopt($curl,CURLOPT_POSTFIELDS,$para);// post传输数据
        $responseText = curl_exec($curl);
        //var_dump( curl_error($curl) );//如果执行curl过程中出现异常，可打开此开关，以便查看异常内容
        curl_close($curl);
    
        return $responseText;
    }
    
    /**
     * 远程获取数据，GET模式
     * 注意：
     * 1.使用Crul需要修改服务器中php.ini文件的设置，找到php_curl.dll去掉前面的";"就行了
     * 2.文件夹中cacert.pem是SSL证书请保证其路径有效，目前默认路径是：getcwd().'\\cacert.pem'
     * @param $url 指定URL完整路径地址
     * @param $cacert_url 指定当前工作目录绝对路径
     * return 远程输出的数据
     */
    public function getHttpResponseGET($url,$cacert_url)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, 0 ); // 过滤HTTP头
        curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);//SSL证书认证
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);//严格认证
        curl_setopt($curl, CURLOPT_CAINFO,$cacert_url);//证书地址
        $responseText = curl_exec($curl);
        //var_dump( curl_error($curl) );//如果执行curl过程中出现异常，可打开此开关，以便查看异常内容
        curl_close($curl);
    
        return $responseText;
    }
    
    /**
     * 实现多种字符编码方式
     * @param $input 需要编码的字符串
     * @param $_output_charset 输出的编码格式
     * @param $_input_charset 输入的编码格式
     * return 编码后的字符串
     */
    public function charsetEncode($input,$_output_charset ,$_input_charset)
    {
        $output = "";
        if(!isset($_output_charset) )$_output_charset  = $_input_charset;
        if($_input_charset == $_output_charset || $input ==null ) {
            $output = $input;
        } elseif (function_exists("mb_convert_encoding")) {
            $output = mb_convert_encoding($input,$_output_charset,$_input_charset);
        } elseif(function_exists("iconv")) {
            $output = iconv($_input_charset,$_output_charset,$input);
        } else die("sorry, you have no libs support for charset change.");
        return $output;
    }
    /**
     * 实现多种字符解码方式
     * @param $input 需要解码的字符串
     * @param $_output_charset 输出的解码格式
     * @param $_input_charset 输入的解码格式
     * return 解码后的字符串
     */
    public function charsetDecode($input,$_input_charset ,$_output_charset)
    {
        $output = "";

        if($_input_charset == $_output_charset || $input ==null ) {
            $output = $input;
        } elseif (function_exists("mb_convert_encoding")) {
            $output = mb_convert_encoding($input,$_output_charset,$_input_charset);
        } elseif(function_exists("iconv")) {
            $output = iconv($_input_charset,$_output_charset,$input);
        } else die("sorry, you have no libs support for charset changes.");
        return $output;
    }
    
}