<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年5月11日
*/
namespace OAuthBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

class NotifyController extends Controller
{
    public function connectServiceAction(Request $request, $service)
    {
        // Get the data from the resource owner
        $resourceOwner = $this->getResourceOwnerByName($service);
        
        //验证成功
        if($resourceOwner->verifyNotify())
        {
            //处理订单
            $resourceOwner->orderHandle($_POST);
            
            //请不要修改或删除
            die("success");
        }
        
        //验证失败,请不要修改或删除
        die("fail");
    }
}