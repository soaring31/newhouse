<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016-06-20
*/
namespace OAuthBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ReturnController extends Controller
{
    /**
     * 支付同步回调
     * @param Request $request
     * @param string $service
     */
    public function connectServiceAction(Request $request, $service)
    {
        $resourceOwner = $this->getResourceOwnerByName($service);
        
        //验证成功
        if($resourceOwner->verifyNotify($_GET))
        {
            //如果订单未支付成功，则失败
            if(!$resourceOwner->tradeQueryRequest($_GET))
                die("fail");
        }

        die('success');
        return $this->render($this->getBundleName(), $this->parameters);
    }
    
    public function callServiceAction(Request $request, $wxtoken)
    {        
//         try {
//             $postStr = file_get_contents ( 'php://input' );
//             $file = $this->get('core.common')->getTempRoot()."winxin.txt";
//             $this->get('core.common')->saveFile($file, $wxtoken);
//             $this->get('core.common')->saveFile($file, $postStr);
        
//         }catch (\Exception $e){
        
//         }
        
        if($this->getResourceOwnerByName('weixin')->checkSignature($wxtoken))
        {
            $data = $this->get('oauth.weixin_message')->responseMsg($wxtoken);
            //$file = $this->get('core.common')->getTempRoot()."winxin.txt";
            //$this->get('core.common')->saveFile($file, $data);
            die($data);
        }
        die();
    }
    
    /**
     * 支付跳转
     */
    public function alipayAction()
    {
        $data = $this->get('oauth.alipay_submit')->getConfig();
        
        $parameter = array(
            "service" => $data['service'],
            "partner" => $data['partner'],
            "seller_id" => $data['seller_id'],
            "payment_type" => $data['payment_type'],
            "notify_url" => $data['notify_url'],
            "return_url" => $data['return_url'],        
            "anti_phishing_key" => $data['anti_phishing_key'],
            "exter_invoke_ip" => $data['exter_invoke_ip'],
            "out_trade_no" => $this->get('core.common')->makeOrderId(),
            "subject" => $this->get('request')->get('subject', '置顶刷新'),
            "total_fee"	=> $this->get('request')->get('total_fee', 0.01),
            "body" => $this->get('request')->get('body', '置顶提示内容'),
            "_input_charset" => trim(strtolower($data['input_charset']))        
        );


        $info = $this->get('oauth.alipay_submit')->buildRequestParaToString($parameter);

        return new RedirectResponse($this->get('oauth.alipay_submit')->getGateway().$info);
    }
    
    /**
     * 支付宝页面跳转同步通知页面
     */
    public function alireturnAction()
    {
        //$verify_result = $this->get('oauth.alipay_notify')->verifyReturn();

        //if(!$verify_result)
        //    throw new \Exception("验证失败");


        #获取支付宝的通知返回参数
        $parameter = array();

        $parameter['productid']    = $_GET['out_trade_no']; //商户订单号
        $parameter['trade_no']     = $_GET['trade_no'];     //支付宝交易号
        $parameter['trade_status'] = $_GET['trade_status']; //交易状态
        $parameter['total_fee']    = $_GET['total_fee'];    //交易金额
        $parameter['notify_id']    = $_GET['notify_id'];    //通知校验ID
        $parameter['notify_time']  = $_GET['notify_time'];  //通知的发送时间
        $parameter['buyer_email']  = $_GET['buyer_email'];  //买家支付宝帐号

        if($_GET['trade_status'] == 'TRADE_FINISHED' || $_GET['trade_status'] == 'TRADE_SUCCESS')
        {
            //判断该笔订单是否在商户网站中已经做过处理
            //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
            if(!$this->get('db.orderlist')->checkOrder($parameter['productid']))
                $this->get('db.orderlist')->orderHandleAli($parameter);

            $this->get('oauth.alipay_notify')->logResult(">>订单".$parameter['productid']."交易成功!".date('Y-m-d H:i:s',time()));

            echo "验证成功,这里跳转到一个页面，带上变量parameter显示交易的相关信息。<br/>";
            //$this->redirectToRoute('hello', array('parameter' => $parameter));
        }else{
            #echo "跳转支付宝返回错误...trade_status=".$_GET['trade_status'];
            $this->get('oauth.alipay_notify')->getError($_GET['trade_status']);
        }

    }

    /**
     * 支付宝服务器异步通知页面
     */
    public function alinotifyAction()
    {
        $verify_result = $this->get('oauth.alipay_notify')->verifyNotify();
        if($verify_result){

            #获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表   
            $parameter = array();

            if(isset($_POST['batch_no']) && isset($_POST['success_num']) && isset($_POST['result_details']))
            {
                $parameter['batch_no']       = $_POST['batch_no'];      //退款批次号
                $parameter['success_num']    = $_POST['success_num'];   //批量退款数据中转账成功的笔数
                $parameter['result_details'] = $_POST['result_details'];//批量退款数据中的详细信息

                #退款处理?数据保存是否兼容?...测试未完成，因安装支付宝控件需人工申请
                $this->get('db.refundlist')->refundHandleAli($parameter);
            }else{

                #获取参数
                $parameter['productid']    = $_POST['out_trade_no']; //商户订单号
                $parameter['trade_no']     = $_POST['trade_no'];     //支付宝交易号
                $parameter['trade_status'] = $_POST['trade_status']; //交易状态
                $parameter['total_fee']    = $_POST['total_fee'];    //交易金额
                $parameter['notify_id']    = $_POST['notify_id'];    //通知校验ID
                $parameter['notify_time']  = $_POST['notify_time'];  //通知的发送时间
                $parameter['buyer_email']  = $_POST['buyer_email'];  //买家支付宝帐号

                if($_POST['trade_status'] == 'TRADE_FINISHED'){
                    //判断该笔订单是否在商户网站中已经做过处理
                    //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                    $this->get('db.orderlist')->orderHandleAli($parameter);
                    //请务必判断请求时的total_fee、seller_id与通知时获取的total_fee、seller_id为一致的
                    //如果有做过处理，不执行商户的业务程序        
                    //注意：退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知
                }else if($_POST['trade_status'] == 'TRADE_SUCCESS'){
                    //判断该笔订单是否在商户网站中已经做过处理
                    //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                    if(!$this->get('db.orderlist')->checkOrder($parameter['productid']))
                        $this->get('db.orderlist')->orderHandleAli($parameter);
                    //请务必判断请求时的total_fee、seller_id与通知时获取的total_fee、seller_id为一致的
                    //如果有做过处理，不执行商户的业务程序                 
                    //注意：付款完成后，支付宝系统发送该交易状态通知
                }
            }

            echo "success";     //*请不要修改或删除
        }else{
            #验证失败
            echo "fail";
            #调试用，写文本函数记录程序运行情况是否正常
            #logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
        }

    }

    public function wxreturnAction()
    {
        echo 'wxreturnAction';die();
    }

    public function wxnotifyAction()
    {
        echo 'wxnotifyAction';die();
    }
    
}
