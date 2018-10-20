<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年1月14日
*/
namespace ManageBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AuthGroup extends AbstractServiceManager
{
    protected $table = 'AuthGroup';

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
    }
    
    public function add(array $data,  $info=null, $isValid=true)
    {
        $user = parent::getUser();
        
        if(!$user)
            throw new \InvalidArgumentException("登入失效，请重新登入!");
/*
        $info = new \stdClass();
        $info->metadata = $this->getClassMetadata($this->table);
        
        //嵌入UID
        if(method_exists($info->metadata->entity, 'setUid'))
            $info->metadata->entity->setUid($user->getId());
 * */        
        $data['uid'] = $user->getId();
        
        if (isset($data['region']) && $data['region'] === '') {
            $data['region'] = -1;
        }
        return parent::add($data, $info, $isValid);
    }
    
    public function update($id, array $data, $info=null, $isValid=true)
    {
        if (isset($data['region']) && $data['region'] === '')
            $data['region'] = -1;

        return parent::update($id, $data, $info, $isValid);
    }
}