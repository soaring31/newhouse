<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年09月09日
*/
namespace ManageBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
        
/**
* 类目关联
* 
*/
class CateRelation extends AbstractServiceManager
{
    protected $table = 'CateRelation';
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
    }

    public function setRelation($id)
    {
        $result = $this->findOneBy(array('id'=>$id));
    	if(empty($result))
    		return array();

        $master = $this->get($result->getMaster())->findAll();
        $slave = $this->get($result->getSlave())->findAll();
        return array('master' => $master['data'], 'slave' => $slave['data'], 'already' => $result->getCfgs());
    }

    public function getRelation($id)
    {
        if(empty($id))
            return;

        $result = $this->findOneBy(array('id'=>$id));
        $master = $this->get($result->getMaster())->findAll();
        return array('master' => $master['data'], 'relation' => json_encode(unserialize($result->getCfgs())));
    }

}