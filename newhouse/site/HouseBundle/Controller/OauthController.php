<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016-06-20
*/
namespace HouseBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class OauthController extends Controller
{
    public function checkweiboAction(Request $request)
    {
        die('ttt');
        $oauthUtils = $this->get('oauth.security.oauth_utils');
        $sinaWeibo = $oauthUtils->getResourceOwner('weibo');

        $acfun = $oauthUtils->getResourceOwnerCheckPath('weibo');
        $redirectUrl = $oauthUtils->httpUtils->generateUri($request, $acfun);
        $accessToken = $sinaWeibo->getAccessToken($request, $redirectUrl);

        if ($accessToken && $accessToken['uid'] !== NULL ) {
            $oauthUtils->id_format($accessToken['uid']);
            $user_message = $sinaWeibo->getUserInformation($accessToken);
        }

        $userInfo = $user_message->getResponse();
        if(isset($userInfo['error_code']) && isset($userInfo['error']))
            return $this->error($userInfo['error']);

        $data = array(
            'oauthid' => $userInfo['idstr'],
            'nickname' => $userInfo['name'],
            'location' => $userInfo['location'],
            'headimage' => $userInfo['avatar_hd'],
            'type' => 'weibo'
        );

        $result = $this->get('db.user_oauth')->loginHandle($data);
        $url = $this->get('router')->generate('house_thlogin');

        if($result){
            if($this->get('core.common')->isAjax())
                $this->showMessage('登陆成功',true,array(),$url);

            return new RedirectResponse($url);
        }

        return $this->error("登陆失败");
    }

    public function checkqqAction(Request $request)
    {
        die('ttt');
        $oauthUtils = $this->get('oauth.security.oauth_utils');
        $qqOauth = $oauthUtils->getResourceOwner('qq');

        if($request->query->get('state') !== $qqOauth->storage->get_csrf($qqOauth, 'csrf_state'))
            return $this->error("The state does not match. You may be a victim of CSRF.");

        $acfun = $oauthUtils->getResourceOwnerCheckPath('qq');
        $redirectUrl = $oauthUtils->httpUtils->generateUri($request, $acfun);
        $accessToken = $qqOauth->getAccessToken($request, $redirectUrl);
        $user_message = $qqOauth->getUserInformation($accessToken);
        $userInfo = $user_message->getResponse();

        if(isset($userInfo['error_code']) && isset($userInfo['error']))
            return $this->error($userInfo['error']);

        $data = array(
            'oauthid' => $userInfo['openid'],
            'nickname' => $userInfo['nickname'],
            'location' => $userInfo['province']." ".$userInfo['city'],
            'headimage' => $userInfo['figureurl_qq_2'],
            'type' => 'qq'
        );

        $result = $this->get('db.user_oauth')->loginHandle($data);
        $url = $this->get('router')->generate('house_thlogin');

        if($result){
            if($this->get('core.common')->isAjax())
                $this->showMessage('登陆成功',true,array(),$url);

            return new RedirectResponse($url);
        }

        return $this->error("登陆失败");
    }

    public function checkwechatAction(Request $request)
    {
        die('ttt');
        $oauthUtils = $this->get('oauth.security.oauth_utils');
        $wechat = $oauthUtils->getResourceOwner('wechat');

        $acfun = $oauthUtils->getResourceOwnerCheckPath('wechat');
        $redirectUrl = $oauthUtils->httpUtils->generateUri($request, $acfun);
        $accessToken = $wechat->getAccessToken($request, $redirectUrl); //这里是否做accessToken保存处理?（不安全！）

        $user_message = $wechat->getUserInformation(array('access_token' => $accessToken));
        $userInfo = $user_message->getResponse();

        if(isset($userInfo['errcode']))
            return $this->error($userInfo['errmsg']);

        $data = array(
            'oauthid' => $userInfo['openid'],
            'nickname' => $userInfo['nickname'],
            'location' => $userInfo['province']." ".$userInfo['city'],
            'headimage' => $userInfo['headimgurl'],
            'type' => 'wechat'
        );

        $result = $this->get('db.user_oauth')->loginHandle($data);
        $url = $this->get('router')->generate('house_thlogin');

        if($result){
            if($this->get('core.common')->isAjax())
                $this->showMessage('登陆成功',true,array(),$url);

            return new RedirectResponse($url);
        }

        return $this->error("登陆失败");
    }

    public function checkweixinAction(Request $request)
    {
        die('ttt');
        //https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx77827302390ce603&redirect_uri=http%3A%2F%2Fleunico.yicp.io/newcore/web/app_dev.php/house/oauth/checkweixin&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect
        $status = $this->get('core.memcache')->getMemcache()->get('code');
        $token = $this->get('request')->getSession()->get('token');

        $oauthUtils = $this->get('oauth.security.oauth_utils');
        $weixin = $oauthUtils->getResourceOwner('weixin');
// $this->get('request')->getSession()->set('token', NULL);
// $this->get('core.memcache')->getMemcache()->delete('code');
// dump($status);
// dump($token);
// die;
        $acfun = $oauthUtils->getResourceOwnerCheckPath('weixin');
        $redirectUrl = $oauthUtils->httpUtils->generateUri($request, $acfun);

        if(empty($token) && empty($status)){
            $accessToken = $weixin->getAccessToken($request, $redirectUrl);
            $user_message = $weixin->getUserInformation(array('access_token' => $accessToken));
            $userInfo = $user_message->getResponse();
        }else{
            $accessToken = $token;
            $userInfo = $status;
        }

// dump($accessToken);
// die;
// $this->get('request')->getSession()->set('token', NULL);
// $this->get('core.memcache')->getMemcache()->delete('code');

        $data = array(
            'oauthid' => $userInfo['openid'],
            'nickname' => $userInfo['nickname'],
            'location' => $userInfo['province']." ".$userInfo['city'],
            'headimage' => $userInfo['headimgurl'],
            'type' => 'weixin'
        );
// dump($data);
// die;
        $result = $this->get('db.user_oauth')->loginHandle($data);
        $url = $this->get('router')->generate('house_thlogin');

        if($result){
            if($this->get('core.common')->isAjax())
                $this->showMessage('登陆成功',true,array(),$url);

            return new RedirectResponse($url);
        }

        return $this->error("登陆失败");
    }

}
