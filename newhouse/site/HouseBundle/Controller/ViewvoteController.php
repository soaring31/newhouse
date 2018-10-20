<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年11月21日
*/
namespace HouseBundle\Controller;

/**
* 前台投票
* @author house
*/
class ViewvoteController extends Controller
{



    /**
    * 实现的save方法
    * house
    */
    public function saveAction()
    {
        if($this->get('request')->getMethod() == "POST")
        {
            $ids = $this->get('request')->get('id', '');

            if($ids)
            {
                $ids = explode(',', $ids);
                foreach($ids as $id)
                {
                    $this->get('house.voteoptions')->update($id, array('votes'=>$this->get('house.voteoptions')->findOneBy(array('id'=>$id))->getVotes()+1));
                }
            }else
                $this->get('house.voteoptions')->add($_POST);
            
            return $this->showMessage('投票成功',1);
        }

        return $this->showMessage('投票失败',0);
    }
    /**
    * 实现的delete方法
    * house
    */
    public function deleteAction()
    {
        $ids = $this->get('request')->get('id', '');

        if($ids)
        {
            $ids = explode(',', $ids);
            foreach($ids as $id)
            {
                $this->get('house.voteoptions')->delete($id);
            }
        }
        return $this->success('操作成功');
    }

    /**
    * 首页
    * house
    */
    public function indexAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}