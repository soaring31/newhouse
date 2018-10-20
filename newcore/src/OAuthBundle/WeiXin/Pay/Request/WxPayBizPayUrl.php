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
 * 扫码支付模式一生成二维码参数
 *
 */
class WxPayBizPayUrl extends WxPayDataBase
{    
    /**
     * 设置支付时间戳
     * @param string $value
     **/
    public function setTimeStamp($value)
    {
        $this->values['time_stamp'] = $value;
    }
    /**
     * 获取支付时间戳的值
     * @return 值
     **/
    public function getTimeStamp()
    {
        return $this->values['time_stamp'];
    }
    /**
     * 判断支付时间戳是否存在
     * @return true 或 false
     **/
    public function isTimeStampSet()
    {
        return array_key_exists('time_stamp', $this->values);
    }
    
    /**
     * 设置随机字符串
     * @param string $value
     **/
    public function setNonceStr($value)
    {
        $this->values['nonce_str'] = $value;
    }
    /**
     * 获取随机字符串的值
     * @return 值
     **/
    public function getNonceStr()
    {
        return $this->values['nonce_str'];
    }
    /**
     * 判断随机字符串是否存在
     * @return true 或 false
     **/
    public function isNonceStrSet()
    {
        return array_key_exists('nonce_str', $this->values);
    }
    
    /**
     * 设置商品ID
     * @param string $value
     **/
    public function setProductId($value)
    {
        $this->values['product_id'] = $value;
    }
    /**
     * 获取商品ID的值
     * @return 值
     **/
    public function getProductId()
    {
        return $this->values['product_id'];
    }
    /**
     * 判断商品ID是否存在
     * @return true 或 false
     **/
    public function isProductIdSet()
    {
        return array_key_exists('product_id', $this->values);
    }
}