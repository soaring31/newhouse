<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年3月17日
*/
namespace CoreBundle\Handler;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;


class AuthenticationHandler implements AuthenticationSuccessHandlerInterface,  AuthenticationFailureHandlerInterface
{
    protected $router, $doctrine;
    
    public function __construct(Router $router, Doctrine $doctrine)
    {
        $this->router = $router;
        $this->doctrine = $doctrine;
    }
    
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        $providerKey = $token->getProviderKey();
        $providerKey = explode('_', $providerKey);
        
        $url = isset($providerKey[0])?$this->router->generate($providerKey[0].'_main'):$request->headers->get('referer');

        //记录日志
//         $em = $this->doctrine->getManager();
//         $repository = $em->getRepository('CoreCoreBundle:User');
//         $user = $repository->findOneByUsername($token->getUsername());
        
//         $visit = new Visit();
//         $visit->setFormat($request->request->get('_format', 'none'));
//         $visit->setClientIp($request->request->get('client-ip', '0.0.0.0'));
//         $visit->setStatus(Visit::SUCCESS);
        
//         // ...
//         $user->addVisit($visit);
        
//         $em->persist($visit);
//         $em->flush();
        //$targetPath = $request->getSession()->get('_security.manage.target_path');
        //_security.house_area.target_path

        if ($request->isXmlHttpRequest()) {
            
            //ajax请求
            return new JsonResponse(array(
                'status' => true,
                'code'    => 0,
                'info' => '登入成功',
                'url' => $url,
            ));
        }

        return new RedirectResponse($url);
    }
    
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $parameters = array();
        $parameters['status'] = false;
        $parameters['code'] = $exception->getCode();
        $parameters['info'] = $exception->getMessage();
        dump($request);die();
        if ($request->isXmlHttpRequest())
            // Handle XHR here
            return new JsonResponse($parameters);

        unset($parameters['status']);
        return new RedirectResponse($this->router->generate('core_error', $parameters));
    }
}