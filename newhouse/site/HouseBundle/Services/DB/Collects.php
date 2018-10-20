<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年09月28日
*/
namespace HouseBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
* 关注收藏
* 
*/
class Collects extends AbstractServiceManager
{
    protected $table = 'Collects';
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
    }

    public function add(array $data,  $info=null, $isValid=true)
    {

        $user = $this->get('core.common')->getUser();
        
        if(!is_object($user))
            throw new \LogicException("请登陆后再收藏", false);

        $data = $this->get('request')->request->All();

        $data['uid'] = isset($data['uid'])?$data['uid']:$user->getId();

        $count = parent::count($data);
        if($count>0)
            throw new \LogicException("禁止重复收藏", false);

        return parent::add($data, null, $isValid);
    }

    public function addCollects($uid,$aid){

        $_data = array('uid' => $uid, 'aid' => $aid,'checked' => '1','models' => 'houses');

        return parent::add($_data, null, false);
    }
}