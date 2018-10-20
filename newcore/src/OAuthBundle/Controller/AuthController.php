<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年5月3日
*/
namespace OAuthBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AuthController extends Controller
{    

    public function connectServiceAction(Request $request, $service)
    {
        $url = $this->get('router')->generate($this->container->getParameter('oauth.register_target_path'));

        //判断是否开启第三方帐号登陆
        if (!$this->container->getParameter('oauth.connect'))
            throw new NotFoundHttpException();

        //判断是否已登陆
        if ($this->isGranted('IS_AUTHENTICATED_REMEMBERED'))
            return new RedirectResponse($this->get('router')->generate($this->container->getParameter('oauth.default_target_path')));

        // Get the data from the resource owner
        $resourceOwner = $this->getResourceOwnerByName($service);

        $session = $request->getSession();
        $key = $request->query->get('key', time());

        if ($resourceOwner->handles($request)) {
            $accessToken = $resourceOwner->getAccessToken(
                $request,
                $this->container->get('oauth.security.oauth_utils')->getServiceAuthUrl($request, $resourceOwner)
            );

            // save in session
            $session->set('connect_confirmation.'.$key, $accessToken);
        } else
            $accessToken = $session->get('connect_confirmation.'.$key);

        // 认证失败跳转
        if (null === $accessToken)
            return $this->redirectToRoute($this->container->getParameter('oauth.failed_auth_path'));

        $userInformation = $resourceOwner->getUserInformation($accessToken);

        // Show confirmation page
        if (!$this->container->getParameter('oauth.connect.confirmation'))
            goto show_confirmation_page;
  
        /** @var $form FormInterface */
        $form = $this->createForm('form');
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid())
        {
            show_confirmation_page:
            
            $response = $userInformation->getResponse();
            $data = array();
            $data['type'] = $service;
            foreach($userInformation->getPaths() as $k=>$v)
            {
                if(empty($v))
                    continue;

                if(is_array($v))
                {
                    $item = "";
                    foreach($v as $kk)
                    {                        
                        $item .= isset($response[$kk])?$response[$kk]:"";
                    }
                    $data[$k] = $item;
                }else{
                    $data[$k] = isset($response[$v])?$response[$v]:"";
                }
                    
            }
            
            $oauthToken = $userInformation->getOAuthToken();
            $data['accessToken'] = is_object($oauthToken)?$oauthToken->getAccessToken():'';
            $data['expiresIn'] = is_object($oauthToken)?$oauthToken->getCreatedAt()+(int)$oauthToken->getExpiresIn():time();
            $data['extend'] = json_encode($response);

            //登陆
            $result = $this->get('db.user_oauth')->loginHandle($data, $oauthToken);
            
            if(is_object($result))
            {

                /**如果已改过帐密，则直接进入会员中心,
                 * 第三方登陆的帐号都有一次改帐密的机会
                 */
                if($result->getChecked())
                    $url = $this->get('router')->generate($this->container->getParameter('oauth.default_target_path'));

                if($this->get('core.common')->isAjax())
                    $this->showMessage('登陆成功',true,array(),$url);
            }
        }
        
        return new RedirectResponse($url);
    }
}