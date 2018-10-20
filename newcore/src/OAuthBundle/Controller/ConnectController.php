<?php
/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2016-06-20
 */
namespace OAuthBundle\Controller;

use OAuthBundle\OAuth\ResourceOwnerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\SecurityEvents;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use OAuthBundle\Security\Core\Authentication\Token\OAuthToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use OAuthBundle\Security\Core\Exception\AccountNotLinkedException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\AccountStatusException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class ConnectController extends Controller
{
    /**
     * Action that handles the login 'form'. If connecting is enabled the
     * user will be redirected to the appropriate login urls or registration forms.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function connectAction(Request $request)
    {
        $connect = $this->container->getParameter('oauth.connect');
        $hasUser = $this->isGranted('IS_AUTHENTICATED_REMEMBERED');

        $error = $this->getErrorForRequest($request);

        // if connecting is enabled and there is no user, redirect to the registration form
        if ($connect
            && !$hasUser
            && $error instanceof AccountNotLinkedException
        ) {
            $key = time();
            $session = $request->getSession();
            $session->set('_oauth.registration_error.'.$key, $error);

            return $this->redirectToRoute('oauth_connect_registration', array('key' => $key));
        }

        if ($error) {
            // TODO: this is a potential security risk (see http://trac.symfony-project.org/ticket/9523)
            $error = $error->getMessage();
        }

        return $this->render('OAuthBundle:Connect:login.html.'.$this->getTemplatingEngine(), array(
            'error'   => $error,
        ));
    }

    /**
     * Shows a registration form if there is no user logged in and connecting
     * is enabled.
     *
     * @param Request $request A request.
     * @param string  $key     Key used for retrieving the right information for the registration form.
     *
     * @return Response
     *
     * @throws NotFoundHttpException if `connect` functionality was not enabled
     * @throws AccessDeniedException if any user is authenticated
     * @throws \Exception
     */
    public function registrationAction(Request $request, $key)
    {
        $connect = $this->container->getParameter('oauth.connect');
        if (!$connect) {
            throw new NotFoundHttpException();
        }

        $hasUser = $this->isGranted('IS_AUTHENTICATED_REMEMBERED');
        if ($hasUser) {
            throw new AccessDeniedException('Cannot connect already registered account.');
        }

        $session = $request->getSession();
        $error = $session->get('_oauth.registration_error.'.$key);
        $session->remove('_oauth.registration_error.'.$key);

        if (!$error instanceof AccountNotLinkedException || time() - $key > 300) {
            // todo: fix this
            throw new \Exception('Cannot register an account.');
        }

        $userInformation = $this
            ->getResourceOwnerByName($error->getResourceOwnerName())
            ->getUserInformation($error->getRawToken())
        ;

        // enable compatibility with FOSUserBundle 1.3.x and 2.x
        if (interface_exists('FOS\UserBundle\Form\Factory\FactoryInterface')) {
            $form = $this->container->get('oauth.registration.form.factory')->createForm();
        } else {
            $form = $this->container->get('oauth.registration.form');
        }

        $formHandler = $this->container->get('oauth.registration.form.handler');
        if ($formHandler->process($request, $form, $userInformation)) {
            $this->container->get('oauth.account.connector')->connect($form->getData(), $userInformation);

            // Authenticate the user
            $this->authenticateUser($request, $form->getData(), $error->getResourceOwnerName(), $error->getRawToken());

            return $this->render('OAuthBundle:Connect:registration_success.html.'.$this->getTemplatingEngine(), array(
                'userInformation' => $userInformation,
            ));
        }

        // reset the error in the session
        $key = time();
        $session->set('_oauth.registration_error.'.$key, $error);

        return $this->render('OAuthBundle:Connect:registration.html.'.$this->getTemplatingEngine(), array(
            'key' => $key,
            'form' => $form->createView(),
            'userInformation' => $userInformation,
        ));
    }

    /**
     * Connects a user to a given account if the user is logged in and connect is enabled.
     *
     * @param Request $request The active request.
     * @param string  $service Name of the resource owner to connect to.
     *
     * @throws \Exception
     *
     * @return Response
     *
     * @throws NotFoundHttpException if `connect` functionality was not enabled
     * @throws AccessDeniedException if no user is authenticated
     */
    public function connectServiceAction(Request $request, $service)
    {
        $connect = $this->container->getParameter('oauth.connect');
        if (!$connect) {
            throw new NotFoundHttpException();
        }

        $hasUser = $this->isGranted('IS_AUTHENTICATED_REMEMBERED');
        if (!$hasUser) {
            throw new AccessDeniedException('Cannot connect an account.');
        }

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
            $session->set('_oauth.connect_confirmation.'.$key, $accessToken);
        } else {
            $accessToken = $session->get('_oauth.connect_confirmation.'.$key);
        }

        // Redirect to the login path if the token is empty (Eg. User cancelled auth)
        if (null === $accessToken) {
            return $this->redirectToRoute($this->container->getParameter('oauth.failed_auth_path'));
        }

        $userInformation = $resourceOwner->getUserInformation($accessToken);

        // Show confirmation page?
        if (!$this->container->getParameter('oauth.connect.confirmation')) {
            goto show_confirmation_page;
        }

        // Handle the form
        /** @var $form FormInterface */
        $form = $this->createForm('form');

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            show_confirmation_page:

            /** @var $currentToken OAuthToken */
            $currentToken = $this->getToken();
            $currentUser = $currentToken->getUser();

            $this->container->get('oauth.account.connector')->connect($currentUser, $userInformation);

            if ($currentToken instanceof OAuthToken) {
                // Update user token with new details
                $this->authenticateUser($request, $currentUser, $service, $currentToken->getRawToken(), false);
            }

            return $this->render('OAuthBundle:Connect:connect_success.html.' . $this->getTemplatingEngine(), array(
                'userInformation' => $userInformation,
                'service' => $service,
            ));
        }

        return $this->render('OAuthBundle:Connect:connect_confirm.html.' . $this->getTemplatingEngine(), array(
            'key' => $key,
            'service' => $service,
            'form' => $form->createView(),
            'userInformation' => $userInformation,
        ));
    }

    /**
     * 重定向到第三方授权页
     * @param Request $request
     * @param string  $service: ResourceOwner 的标识名(ID)
     *
     * @return RedirectResponse
     */
    public function redirectToServiceAction(Request $request, $service)
    {
        $authorizationUrl = $this->get('oauth.security.oauth_utils')->getAuthorizationUrl($request, $service);

        // Check for a return path and store it before redirect
        if ($request->hasSession()) {
            // initialize the session for preventing SessionUnavailableException
            $session = $request->getSession();
            $session->start();

            foreach ($this->container->getParameter('oauth.firewall_names') as $providerKey) {
                $sessionKey = '_security.'.$providerKey.'.target_path';

                $param = $this->container->getParameter('oauth.target_path_parameter');
                if (!empty($param) && $targetUrl = $request->get($param)) {
                    $session->set($sessionKey, $targetUrl);
                }

                if ($this->container->getParameter('oauth.use_referer') && !$session->has($sessionKey) && ($targetUrl = $request->headers->get('Referer')) && $targetUrl !== $authorizationUrl) {
                    $session->set($sessionKey, $targetUrl);
                }
            }
        }

        return $this->redirect($authorizationUrl);
    }

    /**
     * Get the security error for a given request.
     *
     * @param Request $request
     *
     * @return string|\Exception
     */
    protected function getErrorForRequest(Request $request)
    {
        // Symfony <2.6 BC. To be removed.
        $authenticationErrorKey = class_exists('Symfony\Component\Security\Core\Security')
            ? Security::AUTHENTICATION_ERROR : SecurityContextInterface::AUTHENTICATION_ERROR;

        $session = $request->getSession();
        if ($request->attributes->has($authenticationErrorKey)) {
            $error = $request->attributes->get($authenticationErrorKey);
        } elseif (null !== $session && $session->has($authenticationErrorKey)) {
            $error = $session->get($authenticationErrorKey);
            $session->remove($authenticationErrorKey);
        } else {
            $error = '';
        }

        return $error;
    }

    /**
     * Get a resource owner by name.
     *
     * @param string $name
     *
     * @return ResourceOwnerInterface
     *
     * @throws \RuntimeException if there is no resource owner with the given name.
     */
    protected function getResourceOwnerByName($name)
    {
        foreach ($this->container->getParameter('oauth.firewall_names') as $firewall) {
            $id = 'oauth.resource_ownermap.'.$firewall;
            if (!$this->container->has($id)) {
                continue;
            }

            $ownerMap = $this->container->get($id);
            
            $resourceOwner = $ownerMap->getResourceOwnerByName($name);

            if ($resourceOwner)
                return $resourceOwner;
        }

        throw new \RuntimeException(sprintf("No resource owner with name '%s'.", $name));
    }

    /**
     * Generates a route.
     *
     * @deprecated since version 0.4. Will be removed in 1.0.
     *
     * @param string  $route    Route name
     * @param array   $params   Route parameters
     * @param boolean $absolute Absolute url or note.
     *
     * @return string
     */
    protected function generate($route, $params = array(), $absolute = false)
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 0.4 and will be removed in 1.0. Use Symfony\Bundle\FrameworkBundle\Controller\Controller::generateUrl instead.', E_USER_DEPRECATED);

        return $this->container->get('router')->generate($route, $params, $absolute);
    }

    /**
     * Authenticate a user with Symfony Security.
     *
     * @param Request       $request
     * @param UserInterface $user
     * @param string        $resourceOwnerName
     * @param string        $accessToken
     * @param bool          $fakeLogin
     */
    protected function authenticateUser(Request $request, UserInterface $user, $resourceOwnerName, $accessToken, $fakeLogin = true)
    {
        try {
            $this->container->get('oauth.user_checker')->checkPostAuth($user);
        } catch (AccountStatusException $e) {
            // Don't authenticate locked, disabled or expired users
            return;
        }

        $token = new OAuthToken($accessToken, $user->getRoles());
        $token->setResourceOwnerName($resourceOwnerName);
        $token->setUser($user);
        $token->setAuthenticated(true);

        $this->setToken($token);

        if ($fakeLogin) {
            // Since we're "faking" normal login, we need to throw our INTERACTIVE_LOGIN event manually
            $this->container->get('event_dispatcher')->dispatch(
                SecurityEvents::INTERACTIVE_LOGIN,
                new InteractiveLoginEvent($request, $token)
            );
        }
    }

    /**
     * Returns templating engine name.
     *
     * @return string
     */
    protected function getTemplatingEngine()
    {
        return $this->container->getParameter('oauth.templating.engine');
    }

    /**
     * {@inheritdoc}
     *
     * Symfony <2.6 BC. To be removed.
     */
    protected function isGranted($attributes, $object = null)
    {
        if (method_exists('Symfony\Bundle\FrameworkBundle\Controller\Controller', 'isGranted')) {
            return parent::isGranted($attributes, $object);
        }

        return $this->get('security.context')->isGranted($attributes, $object);
    }

    /**
     * {@inheritdoc}
     *
     * Symfony <2.6 BC. To be removed.
     */
    protected function redirectToRoute($route, array $parameters = array(), $status = 302)
    {
        if (method_exists('Symfony\Bundle\FrameworkBundle\Controller\Controller', 'redirectToRoute')) {
            return parent::redirectToRoute($route, $parameters, $status);
        }

        return $this->redirect($this->generateUrl($route, $parameters), $status);
    }

    /**
     * @return null|TokenInterface
     *
     * Symfony <2.6 BC. Remove it and use only security.token_storage service instead.
     */
    protected function getToken()
    {
        if ($this->has('security.token_storage')) {
            return $this->get('security.token_storage')->getToken();
        }

        return $this->get('security.context')->getToken();
    }

    /**
     * @param TokenInterface $token
     *
     * Symfony <2.6 BC. Remove it and use only security.token_storage service instead.
     */
    protected function setToken(TokenInterface $token)
    {
        if ($this->has('security.token_storage')) {
            return $this->get('security.token_storage')->setToken($token);
        }

        return $this->get('security.context')->setToken($token);
    }
}
