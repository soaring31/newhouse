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
 * 短链转换输入对象
 *
 */
class WxPayShortUrl extends WxPayDataBase
{
    /**
     * 设置需要转换的URL，签名用原串，传输需URL encode
     * @param string $value
     **/
    public function setLongUrl($value)
    {
        $this->values['long_url'] = $value;
    }
    /**
     * 获取需要转换的URL，签名用原串，传输需URL encode的值
     * @return 值
     **/
    public function getLongUrl()
    {
        return $this->values['long_url'];
    }
    /**
     * 判断需要转换的URL，签名用原串，传输需URL encode是否存在
     * @return true 或 false
     **/
    public function isLongUrlSet()
    {
        return array_key_exists('long_url', $this->values);
    }
}