<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年6月23日
*/
namespace OAuthBundle\Services\WxPay;

class WeiXinPayNotify extends WeiXinPay
{

    public function handle($native = '', $needSign = true)
    {
        $msg = "OK";

        
        //if($native != '' || $native != 'native')
        //    return false;

        #当返回false的时候，表示notify中调用NotifyCallBack回调失败获取签名校验失败，此时直接回复失败
        $result = $this->notify($native, $msg);

        if($result == false){
            $this->values['return_code'] = "FAIL";
            $this->values['return_msg'] = $msg;
            $this->ReplyNotify(false);
            return;
        } else {
            #该分支在成功回调到NotifyCallBack方法，处理完成之后流程
            $this->values['return_code'] = "SUCCESS";
            $this->values['return_msg'] = "OK";
        }

        //处理订单
        $this->get('db.orderlist')->orderHandleWx($this->values);

        return $this->ReplyNotify($needSign);
    }
    
    /**
     * 处理订单
     */
    private function handleOrder()
    {
        if(!isset($this->values['return_code'])||$this->values['return_code']!='SUCCESS')
            return false;

        $orserSn = isset($this->values['out_trade_no'])?$this->values['out_trade_no']:'';
        
        if(empty($orserSn))
            return false;
        
        $this->get('db.orderlist')->handle($this->values);
    }

    /**
     * 
     * 支付结果通用通知
     * @param function $callback
     * 直接回调函数使用方法: notify(you_function);
     * 回调类成员函数方法:notify(array($this, you_function));
     * $callback  原型为：function function_name($data){}
     */
    private function notify($native, &$msg)
    {
        #获取通知的数据
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];

        try {
            parent::FromXml($xml);
        } catch (\Exception $e){
            $msg = $e->getMessage();
            return false;
        }

        return self::NotifyCallBack($this->values, $native);
    }

    /**
     * 
     * notify回调方法，该方法中需要赋值需要输出的参数,不可重写
     * @param array $data
     * @return true回调出来完成不需要继续回调，false回调处理未完成需要继续回调
     */
    final private function NotifyCallBack($data, $native)
    {
        $msg = "OK";
        $result = call_user_func_array(array($this, $native."NotifyProcess"), array($data, $msg));
        
        if($result == true){
            $this->values['return_code'] = "SUCCESS";
            $this->values['return_msg'] = "OK";
        }else{
            $this->values['return_code'] = "FAIL";
            $this->values['return_msg'] = $msg;
        }
        return $result;
    }

    /**
     * 
     * 回复通知
     * @param bool $needSign 是否需要签名输出
     */
    final private function ReplyNotify($needSign = false)
    {
        //如果需要签名
        if($needSign == true && $this->values['return_code'] == "SUCCESS")
            $this->values['sign'] = $this->MakeSign($this->paysignkey);

        return $this->ToXml();
    }

    //重写回调处理函数
    private function NotifyProcess($data, $msg)
    {
        if(!array_key_exists("transaction_id", $data))
            throw new \InvalidArgumentException('输入参数不正确');

        //查询订单，判断订单真实性
        if(!$this->Queryorder($data["transaction_id"]))
            throw new \InvalidArgumentException('订单查询失败');

        return true;
    }
    
    private function nativeNotifyProcess($data, &$msg)
    {      
        if(!array_key_exists("openid", $data) || !array_key_exists("product_id", $data))
        {
            $msg = "回调数据异常";
            return false;
        }
         
        $openid = $data["openid"];
        $product_id = $data["product_id"];
        
        #统一下单
        $result = $this->unifiedorder($openid, $product_id);
        if(!array_key_exists("appid", $result) || !array_key_exists("mch_id", $result) || !array_key_exists("prepay_id", $result))
        {
            $msg = "统一下单失败";
            return false;
         }
        
        $this->SetData("appid", $result["appid"]);
        $this->SetData("mch_id", $result["mch_id"]);
        $this->SetData("nonce_str", $this->getNonceStr());
        $this->SetData("prepay_id", $result["prepay_id"]);
        $this->SetData("result_code", "SUCCESS");
        $this->SetData("err_code_des", "OK");
        return true;
    }

    private function unifiedorder($openId, $product_id)
    {      
        $input = array(
            'body'=>'test', 
            'attach'=>'test', 
            'total_fee'=>10, 
            'goods_tag'=>'test', 
            'product_id'=>'LZX123456',
            'openId'=>$openId,
        );
        $result = $this->GetPayUrl($input, 'NATIVE');
        return $result;
    }

    //查询订单
    public function Queryorder($transaction_id)
    {
        $input = new WxPayOrderQuery();
        $input->SetTransaction_id($transaction_id);

        $result = $this->orderQuery($input);

        if(array_key_exists("return_code", $result) && array_key_exists("result_code", $result) && $result["return_code"] == "SUCCESS" && $result["result_code"] == "SUCCESS")
            return true;

        return false;
    }

    /**
     * 
     * 查询订单，WxPayOrderQuery中out_trade_no、transaction_id至少填一个
     * appid、mchid、spbill_create_ip、nonce_str不需要填入
     * @param WxPayOrderQuery $inputObj
     * @param int $timeOut
     * @throws WxPayException
     * @return 成功时返回，其他抛异常
     */
    public function orderQuery($inputObj, $timeOut = 6)
    {
        $result = array();
        $url = "https://api.mch.weixin.qq.com/pay/orderquery";

        //检测必填参数
        if(!$inputObj->IsOut_trade_noSet() && !$inputObj->IsTransaction_idSet())
            throw new \LogicException("订单查询接口中，out_trade_no、transaction_id至少填一个！");
        
        $inputObj->SetAppid($this->appId);//公众账号ID
        $inputObj->SetMch_id($this->mchid);//商户号
        $inputObj->SetNonce_str(self::getNonceStr());//随机字符串
        $inputObj->SetSign($this->paysignkey);//签名
        $xml = $inputObj->ToXml();

        
        $startTimeStamp = self::getMillisecond();//请求开始时间
        $response = parent::httpRequestCurl($url, $xml, 'post');

        $wxPayResults = new WxPayResults();
        $wxPayResults->setPaysignkey($this->paysignkey);
        $result = $wxPayResults->Init($response->getContent());

        self::costTime($url, $startTimeStamp, $result);//上报请求花费时间

        return $result;
    }
    
    /**
     *
     * 上报数据， 上报的时候将屏蔽所有异常流程
     * @param string $usrl
     * @param int $startTimeStamp
     * @param array $data
     */
    private function costTime($url, $startTimeStamp, $data)
    {
//         //如果不需要上报数据
//         if(WxPayConfig::REPORT_LEVENL == 0){
//             return;
//         }
//         //如果仅失败上报
//         if(WxPayConfig::REPORT_LEVENL == 1 &&
//             array_key_exists("return_code", $data) &&
//             $data["return_code"] == "SUCCESS" &&
//             array_key_exists("result_code", $data) &&
//             $data["result_code"] == "SUCCESS")
//         {
//             return;
//         }
        	
        //上报逻辑
        $endTimeStamp = parent::getMillisecond();
        $objInput = new WxPayReport();
        $objInput->SetInterface_url($url);
        $objInput->SetExecute_time_($endTimeStamp - $startTimeStamp);
        $objInput->SetUser_ip($_SERVER['REMOTE_ADDR']);

        //返回状态码
        if(array_key_exists("return_code", $data))
            $objInput->SetReturn_code($data["return_code"]);

        //返回信息
        if(array_key_exists("return_msg", $data))
            $objInput->SetReturn_msg($data["return_msg"]);

        //业务结果
        if(array_key_exists("result_code", $data))
            $objInput->SetResult_code($data["result_code"]);

        //错误代码
        if(array_key_exists("err_code", $data))
            $objInput->SetErr_code($data["err_code"]);

        //错误代码描述
        if(array_key_exists("err_code_des", $data))
            $objInput->SetErr_code_des($data["err_code_des"]);

        //商户订单号
        if(array_key_exists("out_trade_no", $data))
            $objInput->SetOut_trade_no($data["out_trade_no"]);

        //设备号
        if(array_key_exists("device_info", $data))
            $objInput->SetDevice_info($data["device_info"]);

        self::report($objInput);
    }

}