<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年5月12日
*/
namespace OAuthBundle\WeiXin\Pay\Request;

/**
 *
 * 提交JSAPI输入对象
 *
 */
class WxPayJsApiPay extends WxPayDataBase
{
    /**
     * 设置支付时间戳
     * @param string $value
     **/
    public function setTimeStamp($value)
    {
        $this->values['timeStamp'] = $value;
    }
    /**
     * 获取支付时间戳的值
     * @return 值
     **/
    public function getTimeStamp()
    {
        return $this->values['timeStamp'];
    }
    /**
     * 判断支付时间戳是否存在
     * @return true 或 false
     **/
    public function isTimeStampSet()
    {
        return array_key_exists('timeStamp', $this->values);
    }
    
    /**
     * 随机字符串
     * @param string $value
     **/
    public function setNonceStr($value)
    {
        $this->values['nonceStr'] = $value;
    }
    /**
     * 获取notify随机字符串值
     * @return 值
     **/
    public function getReturnCode()
    {
        return $this->values['nonceStr'];
    }
    /**
     * 判断随机字符串是否存在
     * @return true 或 false
     **/
    public function isReturnCodeSet()
    {
        return array_key_exists('nonceStr', $this->values);
    }
    
    
    /**
     * 设置订单详情扩展字符串
     * @param string $value
     **/
    public function setPackage($value)
    {
        $this->values['package'] = $value;
    }
    /**
     * 获取订单详情扩展字符串的值
     * @return 值
     **/
    public function getPackage()
    {
        return $this->values['package'];
    }
    /**
     * 判断订单详情扩展字符串是否存在
     * @return true 或 false
     **/
    public function isPackageSet()
    {
        return array_key_exists('package', $this->values);
    }
    
    /**
     * 设置签名方式
     * @param string $value
     **/
    public function setSignType($value)
    {
        $this->values['signType'] = $value;
    }
    /**
     * 获取签名方式
     * @return 值
     **/
    public function getSignType()
    {
        return $this->values['signType'];
    }
    /**
     * 判断签名方式是否存在
     * @return true 或 false
     **/
    public function isSignTypeSet()
    {
        return array_key_exists('signType', $this->values);
    }
    
    /**
     * 设置签名方式
     * @param string $value
     **/
    public function setPaySign($value)
    {
        $this->values['paySign'] = $value;
    }
    /**
     * 获取签名方式
     * @return 值
     **/
    public function getPaySign()
    {
        return $this->values['paySign'];
    }
    /**
     * 判断签名方式是否存在
     * @return true 或 false
     **/
    public function isPaySignSet()
    {
        return array_key_exists('paySign', $this->values);
    }
}