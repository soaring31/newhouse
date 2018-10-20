<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年03月21日
*/
namespace ManageBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
* 工作流
* 
*/
class Workflow extends AbstractServiceManager
{
    protected $table = 'Workflow';
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
    }
    
    public function update($id, array $data, $info=null, $isValid=true)
    {
        foreach($data as &$v)
        {
            if(!is_array($v))
                continue;
            
            $v = $v?implode(',', $v):'';
        }

        $info = parent::update($id, $data, $info, $isValid);
        
        if(!$info->getChecked()||$info->getGroupId()<=0)
            return $info;
        
        $user = parent::getUser();

        //变更组
        $this->get('db.userinfo')->changeUserGroup($info->getUid(), $info->getGroupId());
        
        // 更新userinfo
        $id = $this->_handleUserinfo($data);
        // 更新user_attr
        if ($id>0)
            $this->_handleUserAttr($data, $id);

        return $info;
    }
    /**
     * 更新userinfo
     * @param unknown $data
     */
    private function _handleUserinfo($data)
    {
        if (isset($data['userinfo']) && $data['userinfo'])
        {
            $datas = array();
            $fields = explode(',', $data['userinfo']);
            foreach ($fields as $v)
            {
                if (isset($data[$v]))
                    $datas[$v] = $data[$v];
            }
            
            if (count($datas)>0)
            {
                $result = $this->get('db.userinfo')->findOneBy(array('uid'=>$data['uid']));
                if (is_object($result))
                {
                    $this->get('db.userinfo')->update($result->getId(), $datas, null, false);
                    return $result->getId();
                }
            }
        }
        return 0;
    }
    
    /**
     * 更新user_attr
     * @param unknown $data
     * @param int $id userinfo表的id
     */
    private function _handleUserAttr($data, $id=0)
    {
        if ($id==0)
            return;
        
        $map = array();
        $map['mid'] = $id;
        
        if (isset($data['userattr']) && $data['userattr'])
        {
            $datas = array();
            $fields = explode(',', $data['userattr']);
            foreach ($fields as $v)
            {
                if (isset($data[$v]))
                {
                    $map['name'] = $v;
                    $result = $this->get('db.user_attr')->findOneBy($map);
                    if (is_object($result))
                    {
                        $this->get('db.user_attr')->update($result->getId(), array('value'=>$data[$v]), null, false);
                    } else {
                        $result = $this->get('db.workflow_attr')->findOneBy(array('mid'=>$data['id'], 'name'=>$v));
                        
                        if (is_object($result))
                            $this->get('db.user_attr')->add(array_merge($map, array('value'=>$data[$v], 'title'=>$result->getTitle())), null, false);
                    }
                }
            }
        }
    }
}