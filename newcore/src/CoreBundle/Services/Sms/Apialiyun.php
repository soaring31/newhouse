<?php
/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2017年8月4日
 */
namespace CoreBundle\Services\Sms;

use CoreBundle\Services\Aliyun\Core\Config;
use CoreBundle\Services\Aliyun\Core\DefaultAcsClient;
use CoreBundle\Services\Aliyun\lib\Profile\DefaultProfile;
use Symfony\Component\DependencyInjection\ContainerInterface;
use CoreBundle\Services\Aliyun\Dysmsapi\Request\V20170525\SendSmsRequest;

class Apialiyun extends Sms
{
    private $bparams = array();

    // base path
    private $baseurl = 'http://dysmsapi.aliyuncs.com';

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        
        // 加载区域结点配置
        Config::load();
        
        //$this->_init();
        // 短信API产品名
        $product = "Dysmsapi";
        
        // 短信API产品域名
        $domain = "dysmsapi.aliyuncs.com";
        
        // 暂时不支持多Region
        $region = "cn-hangzhou";
        
        // 服务结点
        $endPointName = "cn-hangzhou";
        
        $accessKeyId = $this->get('core.common')->C('sms_accesskeyid');
        
        $accessKeySecret = $this->get('core.common')->C('sms_accesskeysecret');

        // 初始化用户Profile实例
        $profile = DefaultProfile::getProfile($region, $accessKeyId, $accessKeySecret);

        // 增加服务结点
        DefaultProfile::addEndpoint($endPointName, $region, $product, $domain);

        // 初始化AcsClient用于发起请求
        $this->acsClient = new DefaultAcsClient($profile);
    }

    public function sendSMSaa($mobiles,$content='')
    {
        
    }
    
    /**
     * 发送短信范例
     *
     * @param string $phoneNumbers 必填, 短信接收号码 (e.g. 12345678901)
     * @param string $signName <p>
     * 必填, 短信签名，应严格"签名名称"填写，参考：<a href="https://dysms.console.aliyun.com/dysms.htm#/sign">短信签名页</a>
     * </p>
     * @param string $templateCode <p>
     * 必填, 短信模板Code，应严格按"模板CODE"填写, 参考：<a href="https://dysms.console.aliyun.com/dysms.htm#/template">短信模板页</a>
     * (e.g. SMS_0001)
     * </p>
     
     * @param array|null $templateParam <p>
     * 选填, 假如模板中存在变量需要替换则为必填项 (e.g. Array("code"=>"12345", "product"=>"阿里通信"))
     * </p>
     * @param string|null $outId [optional] 选填, 发送短信流水号 (e.g. 1234)
     * @return stdClass
     */
    public function sendSms($phoneNumbers, $content='', $templateCode=null, $templateParam = null, $outId = null)
    {
        $signName = $this->get('core.common')->C('sms_signname');
        
        $phoneNumbers = is_array($phoneNumbers)?$phoneNumbers:explode(',', $phoneNumbers);
        
        foreach($phoneNumbers as $phoneNumber)
        {
        
            // 初始化SendSmsRequest实例用于设置发送短信的参数
            $request = new SendSmsRequest();
        
            // 必填，设置雉短信接收号码
            $request->setPhoneNumbers($phoneNumber);
        
            // 必填，设置签名名称
            $request->setSignName($signName);
        
            // 必填，设置模板CODE
            $request->setTemplateCode($templateCode);
        
            // 可选，设置模板参数
            if($templateParam)
                $request->setTemplateParam(json_encode($templateParam));
        
            // 可选，设置流水号
            if($outId)
                $request->setOutId($outId);
    
            // 发起访问请求
            $acsResponse = $this->acsClient->getAcsResponse($request);

            $res = $this->errInfo($acsResponse);
        }
        return $res;
    
        return $acsResponse;
    
    }
    
    function sendSMSback($mobiles,$content='')
    {
        //此处需要替换成自己的AK信息
        $accessKeyId = "yourAccessKeyId";//参考本文档步骤2
        $accessKeySecret = "yourAccessKeySecret";//参考本文档步骤2
        //短信API产品名（短信产品名固定，无需修改）
        $product = "Dysmsapi";
        //短信API产品域名（接口地址固定，无需修改）
        $domain = "dysmsapi.aliyuncs.com";
        //暂时不支持多Region（目前仅支持cn-hangzhou请勿修改）
        $region = "cn-hangzhou";
        //初始化访问的acsCleint
        $profile = DefaultProfile::getProfile($region, $accessKeyId, $accessKeySecret);
        DefaultProfile::addEndpoint("cn-hangzhou", "cn-hangzhou", $product, $domain);
        $acsClient= new DefaultAcsClient($profile);
        $request = new SendSmsRequest();
        //必填-短信接收号码。支持以逗号分隔的形式进行批量调用，批量上限为1000个手机号码,批量调用相对于单条调用及时性稍有延迟,验证码类型的短信推荐使用单条调用的方式
        $request->setPhoneNumbers("15067126468");
        //必填-短信签名
        $request->setSignName("云通信");
        //必填-短信模板Code
        $request->setTemplateCode("SMS_0001");
        //选填-假如模板中存在变量需要替换则为必填(JSON格式),友情提示:如果JSON中需要带换行符,请参照标准的JSON协议对换行符的要求,比如短信内容中包含\r\n的情况在JSON中需要表示成\\r\\n,否则会导致JSON在服务端解析失败
        $request->setTemplateParam("{\"code\":\"12345\",\"product\":\"云通信服务\"}");
        //选填-发送短信流水号
        $request->setOutId("1234");
        //发起访问请求
        $acsResponse = $acsClient->getAcsResponse($request);
        
        dump($acsResponse);die();
    }
    
    /**
     * 余额查询
     * @return double 余额
     */
    function getBalance()
    {
        return array("","");
    }
    
    /**
     * 错误代码-信息对照表
     */
    public function errInfo($data,$re=0)
    {
        $err = array(
            'OK'  => '发送成功',
            'isp.RAM_PERMISSION_DENY' => 'RAM权限DENY',
            'isv.OUT_OF_SERVICE' => '业务停机',
            'isv.PRODUCT_UN_SUBSCRIPT' => '未开通云通信产品的阿里云客户',
            'isv.PRODUCT_UNSUBSCRIBE' => '产品未开通',
            'isv.ACCOUNT_NOT_EXISTS' => '账户不存在',
            'isv.ACCOUNT_ABNORMAL' => '账户异常',
            'isv.SMS_TEMPLATE_ILLEGAL' => '短信模板不合法',
            'isv.SMS_SIGNATURE_ILLEGAL' => '短信签名不合法',
            'isv.INVALID_PARAMETERS' => '参数异常',
            'isp.SYSTEM_ERROR' => '系统错误',
            'isv.MOBILE_NUMBER_ILLEGAL' => '非法手机号',
            'isv.MOBILE_COUNT_OVER_LIMIT' => '手机号码数量超过限制',
            'isv.TEMPLATE_MISSING_PARAMETERS' => '模板缺少变量',
            'isv.BUSINESS_LIMIT_CONTROL' => '业务限流',
            'isv.INVALID_JSON_PARAM' => 'JSON参数不合法，只接受字符串值',
            'isv.BLACK_KEY_CONTROL_LIMIT' => '黑名单管控',
            'isv.PARAM_LENGTH_LIMIT' => '参数超出长度限制',
            'isv.PARAM_NOT_SUPPORT_URL' => '不支持URL',
            'isv.AMOUNT_NOT_ENOUGH' => '账户余额不足'
        );

        if($re)
            return $err;

        $no = is_object($data)?$data->Code:"";
        $msg = is_object($data)?$data->Message:"";
        $msg || $msg = '(未知错误)';
        
        if($no=='OK')
            return array(1=>$no, 2=>isset($err[$msg])?$err[$msg]:$msg);
        
        return array($no,"{$msg}");
    }
}