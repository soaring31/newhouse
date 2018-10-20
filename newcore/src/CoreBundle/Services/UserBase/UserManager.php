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
use Symfony\Component\DependencyInjection\ContainerInterface;

class UserManager extends UserManagerAbstract
{
    protected $table;
    protected $class;
    protected $metadata;
    protected $repository;
    protected $objectManager;
    

    /**
     * @param ContainerInterface $container
     * @param CanonicalizerInterface $usernameCanonicalizer
     */
    public function __construct(ContainerInterface $container, CanonicalizerInterface $usernameCanonicalizer)
    {
        $this->container = $container;

        parent::__construct($this->get('security.encoder_factory'), $usernameCanonicalizer, $usernameCanonicalizer);

        $userDb = $this->get('core.common')->getUserDb();
        $entityclass = $this->get('core.common')->prefixEntityName('users');

        $this->objectManager = $this->get('doctrine')->getManager($userDb);
        $this->repository = $this->get('doctrine')->getRepository($entityclass, $userDb);

        $this->metadata = $this->objectManager->getClassMetadata($entityclass);
        $this->class = $this->metadata->getName();
    }

    /**
     * {@inheritDoc}
     */
    public function deleteUser(UserInterface $user)
    {
        $this->objectManager->remove($user);
        $this->objectManager->flush();
    }
    
    public function setUserDB($userBundle)
    {
        $userBundle = ucfirst($userBundle);

        //加bundle后缀
        if (!preg_match('/Bundle$/', $userBundle))
            $userBundle .= "Bundle";
        
        $entityclass = $userBundle.":Users";

        //去掉bundle后缀
        $userBundle = strtolower($userBundle);
        $userDb = substr($userBundle, 0, -6);

        $this->repository = $this->get('doctrine')->getRepository($entityclass, $userDb);
    }

    /**
     * {@inheritDoc}
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * {@inheritDoc}
     */
    public function findUserBy(array $criteria, $userDb=null)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * {@inheritDoc}
     */
    public function findUsers()
    {
        return $this->repository->findAll();
    }

    /**
     * {@inheritDoc}
     */
    public function reloadUser(UserInterface $user)
    {
        $this->objectManager->refresh($user);
    }


    /**
     * 新增或更新用户.
     *
     * @param UserInterface $user
     * @param Boolean       $andFlush Whether to flush the changes (default true)
     */
    public function updateUser(UserInterface $user, $andFlush = true)
    {
        $this->updateCanonicalFields($user);
        $this->updatePassword($user);

        //获得需要写入的字段名称
        $columns = $this->filterData();

        foreach($columns as $v){
            $name = $this->get('core.common')->ucWords($v);
            if(!$user->{"get" . $name}()) {
                $user->{"set" . $name}('');
            }
        }

        $this->objectManager->persist($user);
        if ($andFlush) $this->objectManager->flush();

        return $user;
    }

    /**
     * 数据过滤整合
     */
    public function filterData($class=null)
    {
    	$this->metadata = $class?$this->objectManager->getClassMetadata($class):$this->metadata;

    	$identifier		= $this->metadata->getIdentifier();
    	$columnNames	= $this->metadata->columnNames;
    	unset($columnNames[$identifier[0]]);
    	return $columnNames;
    }
}
