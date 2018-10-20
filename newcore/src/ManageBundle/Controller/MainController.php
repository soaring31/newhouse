<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年1月12日
*/
namespace ManageBundle\Controller;

class MainController extends Controller
{
    public function indexAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }
    
    private function test()
    {
        $zip = new \ZipArchive;
        
        dump($zip);
        
        //CASE WHEN zjdate>=1493100826 THEN zjdate ELSE refreshdate END DESC
    }

    /**
     * 顶部菜单
     */
    public function topmenuAction()
    {
        //嵌入参数
        $this->parameters['menus'] = parent::getMenus();
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
     * 左侧菜单
     */
    public function leftmenuAction()
    {
        //根据菜单ID查看子菜单
        $menuId = $this->get('request')->get('menuId');

        //嵌入参数
        $this->parameters['menus'] = $menuId?$this->getMenus($menuId):array();
        return $this->render($this->getBundleName(), $this->parameters);
    }
    
    public function testAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    public function testbackAction()
    {
        $input = $this->get('weixin.wxpayunifiedorder');
        $input->SetBody("置顶充值");
        $input->SetAttach("置顶充值");
        $input->SetOut_trade_no(date("YmdHis"));
        $input->SetTotal_fee("1");
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetGoods_tag("test");
        //$input->SetNotify_url("http://paysdk.weixin.qq.com/example/notify.php");
        $input->SetNotify_url("http://dev.newcore.com");
        $input->SetTrade_type("NATIVE");
        $input->SetProduct_id("123456789");

        $result = $this->get('weixin.nativepay')->GetPayUrl($input);
        $url2 = $result["code_url"];
        
        //$url1 = $this->get('weixin.nativepay')->GetPrePayUrl("123456789");
        
        echo '<img alt="模式一扫码支付" src="http://paysdk.weixin.qq.com/example/qrcode.php?data='.$url2.'" style="width:150px;height:150px;"/>';
        die();
        return $this->render($this->getBundleName(), $this->parameters);
    }
}