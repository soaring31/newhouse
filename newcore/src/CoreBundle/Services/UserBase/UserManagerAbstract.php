<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package Tripod
* create date 2015-5-29
*/
namespace CoreBundle\Services\UserBase;

use CoreBundle\Util\CanonicalizerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;


abstract class UserManagerAbstract implements UserManagerInterface, UserProviderInterface
{
    protected $container;
    protected $encoderFactory;
    protected $usernameCanonicalizer;
    protected $emailCanonicalizer;

    /**
     * Constructor.
     *
     * @param EncoderFactoryInterface $encoderFactory
     * @param CanonicalizerInterface  $usernameCanonicalizer
     * @param CanonicalizerInterface  $emailCanonicalizer
     */
    public function __construct(EncoderFactoryInterface $encoderFactory,
        CanonicalizerInterface $usernameCanonicalizer,
        CanonicalizerInterface $emailCanonicalizer)
    {
        $this->encoderFactory = $encoderFactory;
        $this->emailCanonicalizer = $emailCanonicalizer;
        $this->usernameCanonicalizer = $usernameCanonicalizer;
    }

    /**
     * Returns an empty user instance
     *
     * @return UserInterface
     */
    public function createUser()
    {
        $class = $this->getClass();
        $user = new $class;

        return $user;
    }

    /**
     * Finds a user by email
     *
     * @param string $email
     *
     * @return UserInterface
     */
    public function findUserByEmail($email)
    {
        $user = $this->findUserBy(array('email' => $this->canonicalizeEmail($email),'is_delete'=>0));
        
        if (!is_object($user)) throw new UsernameNotFoundException(sprintf('email用户 "%s" 没有找到.', $email));
        
        return $user;
    }

    /**
     * Finds a user by username
     *
     * @param string $username
     *
     * @return UserInterface
     */
    public function findUserByUsername($username)
    {
        $user = $this->findUserBy(array('username' => $this->canonicalizeUsername($username),'is_delete'=>0));
        
        if (!is_object($user)) throw new UsernameNotFoundException(sprintf('用户名 "%s" 没有找到.', $username));
        
        return $user;
    }
    
    /**
     * Finds a user by tel
     *
     * @param int $tel
     *
     * @return UserInterface
     */
    public function findUserByTel($tel)
    {
        $user = $this->findUserBy(array('tel' => $this->canonicalizeUsername($tel),'is_delete'=>0));
        
        if (!is_object($user)) throw new UsernameNotFoundException(sprintf('手机用户 "%s" 没有找到.', $tel));
        
        return $user;
    }

    /**
     * Finds a user either by email, or username
     *
     * @param string $usernameOrEmail
     *
     * @return UserInterface
     */
    public function findUserByUsernameOrEmail($usernameOrEmail)
    {
        //判断EMAIL
        if (filter_var($usernameOrEmail, FILTER_VALIDATE_EMAIL)) {
            return $this->findUserByEmail($usernameOrEmail);
        }
        
        //判断手机
        if($this->get('core.common')->isMobile($usernameOrEmail))
        {
            return $this->findUserByTel($usernameOrEmail);
        }

        return $this->findUserByUsername($usernameOrEmail);
    }

    /**
     * Finds a user either by confirmation token
     *
     * @param string $token
     *
     * @return UserInterface
     */
    public function findUserByConfirmationToken($token)
    {
        return $this->findUserBy(array('token' => $token));
    }

    /**
     * Refreshed a user by User Instance
     *
     * Throws UnsupportedUserException if a User Instance is given which is not
     * managed by this UserManager (so another Manager could try managing it)
     *
     * It is strongly discouraged to use this method manually as it bypasses
     * all ACL checks.
     *
     * @param UserInterface $user
     *
     * @return UserInterface
     */
    public function refreshUser(UserInterface $user)
    {
        $refreshedUser = $this->findUserBy(array('id' => $user->getId()));

        if (null === $refreshedUser) {
            throw new UsernameNotFoundException(sprintf('User with ID "%d" could not be reloaded.', $user->getId()));
        }

        return $refreshedUser;
    }

    /**
     * Loads a user by username
     *
     * It is strongly discouraged to call this method manually as it bypasses
     * all ACL checks.
     *
     * @param string $username
     *
     * @return UserInterface
     */
    public function loadUserByUsername($username)
    {
        $user = $this->findUserByUsername($username);

        if (!is_object($user)) throw new UsernameNotFoundException(sprintf('用户名 "%s" 没有找到.', $username));

        return $user;
    }

    /**
     * 更新用户信息(自动规范化动作)
     * {@inheritDoc}
     */
    public function updateCanonicalFields(UserInterface $user)
    {
        $user->setUsername($this->canonicalizeUsername($user->getUsername()));
        $user->setEmail($this->canonicalizeEmail($user->getEmail()));
    }

    /**更新用户密码
     * {@inheritDoc}
     */
    public function updatePassword(UserInterface $user)
    {
        if (0 !== strlen($password = $user->getPassword())) {
            $encoder = $this->getEncoder($user);
            $user->setPassword($encoder->encodePassword($password, $user->getSalt()));
            $user->eraseCredentials();
        }
    }

    /**
     * Canonicalizes an email
     *
     * @param string $email
     *
     * @return string
     */
    protected function canonicalizeEmail($email)
    {
        return $this->emailCanonicalizer->canonicalize($email);
    }

    /**
     * Canonicalizes a username
     *
     * @param string $username
     *
     * @return string
     */
    protected function canonicalizeUsername($username)
    {
        return $this->usernameCanonicalizer->canonicalize($username);
    }

    protected function getEncoder(UserInterface $user)
    {
        return $this->encoderFactory->getEncoder($user);
    }

    /**
     * {@inheritDoc}
     */
    public function supportsClass($class)
    {
        return $class === $this->getClass();
    }
    
    /**
     * 获得服务
     * @param int $id
     * @throws \InvalidArgumentException
     */
    protected function get($id)
    {
        if (!$this->container->has($id)) throw new \InvalidArgumentException("[".$id."]服务未注册。");
    
        return $this->container->get($id);
    }
}
