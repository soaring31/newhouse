<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年7月20日
*/
namespace OAuthBundle\AliPay;

class AliPaySubmit extends AliPay
{

    private $alipay_gateway_new = 'https://mapi.alipay.com/gateway.do?';

    public function getGateway()
    {
        return $this->alipay_gateway_new;
    }

    /**
     * 完成一次请求
     * @param $para_temp 请求参数数组
     * @param $method    请求参数方式，不建议修改
     * @return           提交表单HTML文本
     */
    public function buildRequestForm($para_temp, $method = 'get')
    {

        $para = $this->buildRequestPara($para_temp);
        //$me_url = $this->alipay_gateway_new."_input_charset=".trim(strtolower($this->alipay_config['input_charset']));
        //36213219740506032x
        //$info = $this->resourceOwner->uploadRequest($me_url, $para);
        //return $info->getContent(); #可是可以，但不能让getResponseContent函数处理，且不稳定

        $sHtml  = '<meta http-equiv="content-type" content="text/html" charset="'.trim(strtolower($this->alipay_config['input_charset'])).'">';
        $sHtml .= "<form id='alipaysubmit' name='alipaysubmit' action='".$this->alipay_gateway_new."_input_charset=".trim(strtolower($this->alipay_config['input_charset']))."' method='".$method."'>";
//         while (list ($key, $val) = each ($para)) {
//             $sHtml.= "<input type='hidden' name='".$key."' value='".$val."'/>";
//         }
        foreach($para as $key=>$val)
        {
            $sHtml.= "<input type='hidden' name='".$key."' value='".$val."'/>";
        }
        $sHtml = $sHtml."<input type='submit' value='提交' style='display:none;'></form>";        
        $sHtml = $sHtml."<script>document.forms['alipaysubmit'].submit();</script>";

        return $sHtml;

    }

     /**
     * 生成要请求给支付宝的参数数组
     * @param $para_temp 请求前的参数数组
     * @return 要请求的参数数组
     */
    public function buildRequestPara($para_temp)
    {
        //除去待签名参数数组中的空值和签名参数
        $para_temp = parent::paraFilter($para_temp);

        //对待签名参数数组排序
        $para_temp = parent::argSort($para_temp);

        //生成签名结果
        $mysign = parent::buildRequestMysign($para_temp);

        //签名结果与签名方式加入请求提交参数组中
        $para_temp['sign'] = $mysign;
        $para_temp['sign_type'] = strtoupper(trim($this->alipay_config['sign_type']));

        return $para_temp;
    }
    
    /**
     * 生成要请求给支付宝的参数数组
     * @param $para_temp 请求前的参数数组
     * @return 要请求的参数数组字符串
     */
    function buildRequestParaToString($para_temp) {
        //待请求参数数组
        $para = $this->buildRequestPara($para_temp);
    
        //把参数组中所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串，并对字符串做urlencode编码
        $request_data = parent::createLinkstringUrlencode($para);
    
        return $request_data;
    }

    /**
     * 一次支付请求的开始(基本版) Ps.基本版在手机上也能唤醒支付宝APP~
     * @param string  $out_trade_no  商户订单号，商户网站订单系统中唯一订单号，必填
     * @param string  $subject 	     订单名称，必填
     * @param int     $total_fee     付款金额，必填
     * @param string  $body          商品描述，可空
     */
    public function FormAliPay($out_trade_no, $subject, $total_fee, $body)
    {
    	if(empty($out_trade_no) || empty($subject) || empty($total_fee))
    		return false;

    	$parameters = array(
            
    		"service"       => $this->alipay_config['service'],
            "partner"       => $this->alipay_config['partner'],
            "seller_id"     => $this->alipay_config['seller_id'],
            "payment_type"  => $this->alipay_config['payment_type'],
            "notify_url"    => $this->alipay_config['notify_url'],
            "return_url"    => $this->alipay_config['return_url'],
            
            "anti_phishing_key" =>$this->alipay_config['anti_phishing_key'],
            "exter_invoke_ip"   =>$this->alipay_config['exter_invoke_ip'],
            "_input_charset"    => trim(strtolower($this->alipay_config['input_charset'])),

            "out_trade_no"  => trim($out_trade_no),
            "subject"       => trim($subject),
            "total_fee"     => (float)$total_fee,
            "body"          => trim($body),
                
        );

        $infohtml = $this->buildRequestForm($parameters);
        die($infohtml);
    }

    /**
     * 一次支付请求的开始(手机网页版)
     * @param string  $out_trade_no  商户订单号，商户网站订单系统中唯一订单号，必填
     * @param string  $subject       订单名称，必填
     * @param int     $total_fee     付款金额，必填
     * @param string  $body          商品描述，可空
     * @param string  $show_url      收银台页面上，商品展示的超链接，可空
     */
    public function FormWapAliPay($out_trade_no, $subject, $total_fee, $body, $show_url='')
    {
        if(empty($out_trade_no) || empty($subject) || empty($total_fee))
            return false;

        $parameters = array(
            
            "service"      => $this->alipay_config['service'],
            "partner"      => $this->alipay_config['partner'],
            "seller_id"    => $this->alipay_config['seller_id'],
            "payment_type" => $this->alipay_config['payment_type'],
            "notify_url"   => $this->alipay_config['notify_url'],
            "return_url"   => $this->alipay_config['return_url'],
            "out_trade_no" => trim($out_trade_no),
            "subject"      => trim($subject),
            "show_url"     => trim($show_url),
            "total_fee"    => (float)$total_fee,
            "body"         => trim($body),
            "_input_charset" => trim(strtolower($this->alipay_config['input_charset'])),
            //"app_pay"      => "Y", //启用此参数能唤起钱包APP支付宝
                
        );

        $infohtml = $this->buildRequestForm($parameters);
        die($infohtml);
    }

    /**
     * 一次支付请求的开始(自定义版)
     * @param array  $parameter  业务参数(多个)
     */
    public function FormAliPayCustom(array $parameter)
    {
    	if(!isset($parameter['out_trade_no']) || !isset($parameter['subject']))
    		return false;
    	if(isset($parameter['total_fee'])){
    		if(isset($parameter['price']))
    			unset($parameter['price']);
    		if(isset($parameter['quantity']))
    			unset($parameter['quantity']);
    	}else{
    		if(!isset($parameter['price']) || !isset($parameter['quantity']))
    			return false;
    	}

    	//业务参数
    	$business = array(

    		//四个基本参数，见上~
    		"out_trade_no" => "",
    		"subject" => "",
    		"total_fee" => "",
//以上三个必填，下面都是可空
    		"body" => "",

    		//商品单价
    		"price" => "",
    		//购买数量
    		"quantity" => "",
//规则：price、quantity能代替total_fee。即存在total_fee，就不能存在price和quantity；存在price、quantity，就不能存在total_fee。

    		//商品展示网址 收银台页面上，商品展示的超链接。
    		"show_url" => "",
    		//默认支付方式 creditPay（信用支付）directPay（余额支付）如果不设置，默认识别为余额支付。
    		"paymethod" => "",
    		//支付渠道 用于控制收银台支付渠道显示，该值的取值范围请参见支付渠道。
    		"enable_paymethod" => "",
    		//防钓鱼时间戳 通过时间戳查询接口获取的加密支付宝系统时间戳。开通后必填
    		"anti_phishing_key" => "",
    		//用户在创建交易时，该用户当前所使用机器的IP。开通后必填
    		"exter_invoke_ip" => "",
    		//公用回传参数 如果用户请求时传递了该参数，则返回给商户时会回传该参数。
    		"extra_common_param" => "",
    		//超时时间 设置未付款交易的超时时间，一旦超时，该笔交易就会自动被关闭。取值范围：1m～15d。
    		"it_b_pay" => "",
    		//快捷登录授权令牌 开通后必填
    		"token" => "",
    		//扫码支付方式 扫码支付的方式，支持前置模式和跳转模式。详情看文档
    		"qr_pay_mode" => "",
    		//商户自定二维码宽度 当qr_pay_mode=4时，该参数生效
    		"qrcode_width" => "",
    		//是否需要买家实名认证 T表示需要买家实名认证
    		"need_buyer_realnamed" => "",
    		//商户优惠活动参数 商户与支付宝约定的营销透传参数。
    		"promo_param" => "",
    		//花呗分期参数 详情看文档
    		"hb_fq_param" => "",
    		//商品类型 1表示实物类商品 0表示虚拟类商品 如果不传，默认为实物类商品。
    		"goods_type" => "",
    		
    	);
    	//业务参数详情请看文档。文档地址：https://doc.open.alipay.com/doc2/detail.htm?spm=a219a.7629140.0.0.kiX33I&treeId=62&articleId=103740&docType=1

    	$parameters = array(

    		"service"       => $this->alipay_config['service'],
            "partner"       => $this->alipay_config['partner'],
            "seller_id"     => $this->alipay_config['seller_id'],
            "payment_type"  => $this->alipay_config['payment_type'],
            "notify_url"    => $this->alipay_config['notify_url'],
            "return_url"    => $this->alipay_config['return_url'],
            
            "anti_phishing_key" =>$this->alipay_config['anti_phishing_key'],
            "exter_invoke_ip"   =>$this->alipay_config['exter_invoke_ip'],
            "_input_charset"    => trim(strtolower($this->alipay_config['input_charset'])),
                
        );

    	$parameters = array_merge(array_intersect_key($parameter, $business), $parameters);
    	$infohtml = $this->buildRequestForm($parameters);
        die($infohtml);

    }

    /**
     * 一次退款请求的发起
     * @param string  $batch_no      批次号，必填，格式：当天日期[8位]+序列号[3至24位]
     * @param array   $detail_data   退款详细数据，必填，格式（支付宝交易号^退款金额^备注#...）此处请用数组传入，规格array(array('支付宝交易号','退款金额','备注'),array()...)
     * @param int     $batch_num     退款笔数，必填，参数detail_data的值中，“#”字符出现的数量加1，最大支持1000笔（即“#”字符出现的数量999个）
     */
    public function RefundAliPay($batch_no, $batch_num, array $detail_data)
    {
        $detail = "";
        foreach($detail_data as $value){
            if(is_array($value) && count($value)>2)
                $detail = $detail ? $detail.'#'.(implode('^', $value)) : implode('^', $value);
        }

        if(empty($batch_no) || empty($batch_num) || empty($detail))
            return false;

        $parameters = array(
            
            "service"       => 'refund_fastpay_by_platform_pwd', //不要修改~

            "partner"       => $this->alipay_config['partner'],
            "notify_url"    => $this->alipay_config['notify_url'], 
            "refund_date"   => date("Y-m-d H:i:s",time()),          
            "seller_user_id" => $this->alipay_config['seller_id'],                      
            "_input_charset" => trim(strtolower($this->alipay_config['input_charset'])),

            "batch_no"      => trim($batch_no),
            "batch_num"     => (int)$batch_num,
            "detail_data"   => trim($detail),
                
        );

        $infohtml = $this->buildRequestForm($parameters);
        die($infohtml);
    }

}