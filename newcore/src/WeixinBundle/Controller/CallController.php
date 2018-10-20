<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms 28
* create date 2016年6月30日
*/
namespace WeixinBundle\Controller;

/**
 * 微信呼叫控制器
 * @author admin
 * 1467272622
 */
class CallController extends Controller
{
    public function returnAction($token, $appid)
    {
        try {
            $postStr = file_get_contents ( 'php://input' );
            $file = $this->get('core.common')->getTempRoot()."winxin.txt";
            
            $this->get('core.common')->saveFile($file, $token.($appid?"==>".$appid:''));
            $this->get('core.common')->saveFile($file, $_REQUEST);
            $this->get('core.common')->saveFile($file, $postStr);
            
        }catch (\Exception $e){
        
        }

        if($this->get('oauth.weixin_message')->checkSignature($token))
        {
            $data = $this->get('oauth.weixin_message')->responseMsg($token);
            die($data);
        }
        die();
    }
    
    /**
     * 微信扫码支付回调
     */
    public function notifyAction($token)
    {
        try {
            $postStr = file_get_contents ( 'php://input' );
            $file = $this->get('core.common')->getTempRoot()."wxpay.txt";
            $this->get('core.common')->saveFile($file, $_REQUEST);
            $this->get('core.common')->saveFile($file, $postStr);
        }catch (\Exception $e){
        
        }

        $result = $this->get('oauth.weixin_paynotify')->handle();
        die($result);
    }

}