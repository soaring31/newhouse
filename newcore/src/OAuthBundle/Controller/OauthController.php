<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016-06-20
*/
namespace OAuthBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;

class OauthController extends Controller
{
    public function checkweiboAction() // http://leunico.yicp.io/newcore/web/app_dev.php/manage/oauth/checkweibo
    {
        $oauthUtils = $this->get('oauth.security.oauth_utils');
        $request = $this->get('request');
        $sinaWeibo = $oauthUtils->getResourceOwner('weibo');

        $acfun = $oauthUtils->getResourceOwnerCheckPath('weibo');
        $redirectUrl = $oauthUtils->httpUtils->generateUri($request, $acfun);
        $accessToken = $sinaWeibo->getAccessToken($request, $redirectUrl);

        if ($accessToken && $accessToken['uid'] !== NULL ) {
            $oauthUtils->id_format($accessToken['uid']);
            $user_message = $sinaWeibo->getUserInformation($accessToken);
        }
        $userInfo = $user_message->getResponse();
        $data = array(
            'oauthid' => $userInfo['idstr'],
            'nickname' => $userInfo['name'],
            'location' => $userInfo['location'],
            'headimage' => $userInfo['avatar_hd'],
            'type' => 'weibo'
        );
        $result = $this->get('db.user_oauth')->loginHandle($data);
        $url = $this->get('router')->generate('main');
        if($result){
            if($this->get('core.common')->isAjax())
                $this->showMessage('登陆成功',true,array(),$url);

            return new RedirectResponse($url);
        }

        return $this->error("登陆失败");

        // dump($result);
        // die;
    }

}
