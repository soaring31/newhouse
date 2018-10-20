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
 * 回调基础类
 * @author widyhu
 *
 */
class WxPayNotifyReply extends WxPayDataBase
{
    /**
     *
     * 设置错误码 FAIL 或者 SUCCESS
     * @param string
     */
    public function setReturnCode($return_code)
    {
        $this->values['return_code'] = $return_code;
    }
    
    /**
     *
     * 获取错误码 FAIL 或者 SUCCESS
     * @return string $return_code
     */
    public function getReturnCode()
    {
        return $this->values['return_code'];
    }
    
    /**
     *
     * 设置错误信息
     * @param string $return_code
     */
    public function setReturnMsg($return_msg)
    {
        $this->values['return_msg'] = $return_msg;
    }
    
    /**
     *
     * 获取错误信息
     * @return string
     */
    public function getReturnMsg()
    {
        return $this->values['return_msg'];
    }
    
    /**
     *
     * 设置返回参数
     * @param string $key
     * @param string $value
     */
    public function setData($key, $value)
    {
        $this->values[$key] = $value;
    }
}