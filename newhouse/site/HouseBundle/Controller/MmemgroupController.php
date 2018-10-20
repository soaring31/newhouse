<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年11月03日
*/
namespace HouseBundle\Controller;

/**
* 会员组
* @author house
*/
class MmemgroupController extends Controller
{



    /**
    * 会员组管理
    * house
    */
    public function mmemgroupmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 会员分类
    * house
    */
    public function mmemtypesAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 成员列表
    * house
    */
    public function mmemaccessAction()
    {
        $aid = $this->get('request')->get('aid',0);
        
        if ($aid)
        {
            $map = array();
            $map['aid'] = $aid;
            $result = $this->get('db.auth_access_area')->findBy($map);
            $ids = '';
            foreach ($result['data'] as $k=>$v)
            {
                $ids .= $v->getUid().',';
            }
            $ids = rtrim($ids,',');

            $map = array();
            $map['tableName'] = 'users';
            $map['query']['mid']['neq'] = 1;
            $map['query']['status'] = 1;
            $map['query']['id']['in'] = $ids;

            $map['subTableName'] = 'userinfo';
            $map['query1']['groupid'] = $this->get('request')->get('groupid',0);
            $map['aliasName'] = 'p';
            $map['subJoinField'] = 'uid';
            // 内嵌查询
            $users = $this->get('db.users')->getInternalSearch($map);
            
            $this->parameters['ident']['info']['data'] = $users['data'];
            $this->parameters['ident']['info']['pageCount'] = $users['pageCount'];
        }
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}