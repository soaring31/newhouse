<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年4月20日
*/
namespace CoreBundle\Security\Http\Firewall;

use Psr\Log\LoggerInterface;
use CoreBundle\Services\ServiceBase;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;

class AnonymousAuthenticationListener extends ServiceBase implements ListenerInterface
{
    private $context;
    private $key;
    private $authenticationManager;
    private $logger;
    protected $container;

    public function __construct(SecurityContextInterface $context, $key, LoggerInterface $logger = null, AuthenticationManagerInterface $authenticationManager = null)
    {
        $this->context = $context;
        $this->key = $key;
        $this->authenticationManager = $authenticationManager;
        $this->logger = $logger;
    }

    /**
     * Handles anonymous authentication.
     *
     * @param GetResponseEvent $event A GetResponseEvent instance
     */
    public function handle(GetResponseEvent $event)
    {
        if (null !== $this->context->getToken()) {
            return;
        }

        $roles = array();
        $this->container = $event->getDispatcher()->getContainer();
        if($this->get('core.rbac')->isGranted())
            $roles = array('ANONYMOUSLY');

        try {            
            $token = new AnonymousToken($this->key, 'anons.', $roles);
            
            if (null !== $this->authenticationManager) {
                $token = $this->authenticationManager->authenticate($token);
            }

            $this->context->setToken($token);

            if (null !== $this->logger) {
                $this->logger->info('Populated SecurityContext with an anonymous Token');
            }
        } catch (AuthenticationException $failed) {
            if (null !== $this->logger) {
                $this->logger->info(sprintf('匿名用户认证失败: %s', $failed->getMessage()));
            }
        }
    }
}