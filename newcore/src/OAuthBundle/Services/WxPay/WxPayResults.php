<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年4月19日
*/
namespace OAuthBundle\Services\WxPay;

class WxPayResults extends WxPayDataBase
{
    /**
     *
     * 检测签名
     */
    public function CheckSign()
    {
        //fix异常
        if(!$this->IsSignSet()){
            throw new WxPayException("签名错误！");
        }
    
        $sign = $this->MakeSign($this->paysignkey);

        if($this->GetSign() == $sign){
            return true;
        }
        throw new WxPayException("签名错误！");
    }
    
    /**
     *
     * 使用数组初始化
     * @param array $array
     */
    public function FromArray($array)
    {
        $this->values = $array;
    }
    
    /**
     *
     * 使用数组初始化对象
     * @param array $array
     * @param 是否检测签名 $noCheckSign
     */
    public function InitFromArray($array, $noCheckSign = false)
    {
        $obj = new self();
        $obj->FromArray($array);
        if($noCheckSign == false){
            $obj->CheckSign();
        }
        return $obj;
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
     * 将xml转为array
     * @param string $xml
     * @throws WxPayException
     */
    public function Init($xml)
    {
        $obj = new self();
        $obj->FromXml($xml);
        $obj->setPaysignkey($this->paysignkey);
        //fix bug 2015-06-29
        if($obj->values['return_code'] != 'SUCCESS'){
            return $obj->GetValues();
        }
        $obj->CheckSign();
        return $obj->GetValues();
    }
}