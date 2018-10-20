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
 * 接口调用结果类
 *
 */
class WxPayResults extends WxPayDataBase
{
    /**
     *
     * 检测签名
     */
    public function checkSign()
    {
        //fix异常
        if(!$this->isSignSet())
            throw new \Exception("签名错误！");
    
        $sign = $this->makeSign();
        
        if($this->getSign() == $sign)
            return true;

        throw new \Exception("签名错误！");
    }
    
    /**
     *
     * 使用数组初始化
     * @param array $array
     */
    public function fromArray($array)
    {
        $this->values = $array;
    }
    
    /**
     *
     * 使用数组初始化对象
     * @param array $array
     * @param 是否检测签名 $noCheckSign
     */
    public static function initFromArray($array, $noCheckSign = false)
    {
        $obj = new self();
        $obj->fromArray($array);
        if($noCheckSign == false)
            $obj->CheckSign();

        return $obj;
    }
    
    /**
     *
     * 设置参数
     * @param string $key
     * @param string $value
     */
    public function setData($key, $value)
    {
        $this->values[$key] = $value;
    }
    
    /**
     * 将xml转为array
     * @param string $xml
     * @throws WxPayException
     */
    public function init($xml)
    {
        $obj = new self();
        $obj->setPaysignkey($this->paysignkey);
        $obj->FromXml($xml);

        if($obj->values['return_code'] != 'SUCCESS')
            return $obj->getValues();

        $obj->checkSign();
        return $obj->getValues();
    }
}