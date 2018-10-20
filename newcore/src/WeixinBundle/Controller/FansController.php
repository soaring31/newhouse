<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年07月15日
*/
namespace WeixinBundle\Controller;

/**
* 
* @author admina
*/
class FansController extends Controller
{
    /**
    * 实现的wxUpdate方法
    * admina
    */
    public function wxupdateAction()
    {
        $this->get('oauth.weixin_user')->syncFans();
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 实现的save方法
    * admina
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
                    $result = $this->get('weixin.wxfans')->findOneBy(array('id'=>$id));
                    if(isset($_POST['remark'])){
                    	$this->get('oauth.weixin_user')->setUserRemark($result->getOpenid(), $_POST['remark']);
                    }
                    $this->get('weixin.wxfans')->update($id, $_POST);
                }
            }       
            return $this->success('操作成功');
        }       
        return $this->error('操作失败');
    }

    /**
    * 实现的edittag方法
    * admina
    */
    public function edittagAction()
    {
    	$id = $this->getRequest()->get('_id', '');
    	
        $thistag = $this->get('weixin.wxfans')->findOneBy(array('id'=>$id));

    	$this->parameters['tags'] = $this->get('oauth.weixin_user')->getTags();
    	$this->parameters['mytags'] = $thistag->getTagIdList() ? unserialize($thistag->getTagIdList()) : array();

        return $this->render($this->getBundleName(), $this->parameters);
    }

    public function savetagAction()
    {
    	$tags = isset($_POST['tags']) ? $_POST['tags'] : array();
    	$addtag = $_POST['addtag'];
    	$result = $this->get('weixin.wxfans')->findOneBy(array('id'=>$this->getRequest()->get('id', '')));

    	if(count($tags)>3)
    		throw new \InvalidArgumentException('最多只能选择3个!');

    	$addtags = array_diff($tags, $result->getTagIdList() ? unserialize($result->getTagIdList()) : array());
    	$deltags = array_diff($result->getTagIdList() ? unserialize($result->getTagIdList()) : array(), $tags);

    	if(isset($addtag['checked']))
    	{
    		if(!empty($addtag['new'])){
    			if(count($tags)>2)
    				throw new \InvalidArgumentException('最多只能选择3个,包括新建标签!');
    			$info = $this->get('oauth.weixin_user')->create($addtag['new']);
    			$addtags[] = $info['tag']['id'];
    		}else{
    			throw new \InvalidArgumentException('请填写标签名！');
    		}
    	}else{
    		if(!empty($addtag['new']))
    			$info = $this->get('oauth.weixin_user')->create($addtag['new']);
    	}


		foreach($addtags as $value)
		{
			$this->get('oauth.weixin_user')->setMembersTags(array($result->getOpenid()), $value);
		}

		foreach($deltags as $value)
		{
			$this->get('oauth.weixin_user')->deleteMembersTags(array($result->getOpenid()), $value);
		}

    	return $this->success('操作成功');
    }

    public function deletetagAction()
    {
    	$tagname = $this->getRequest()->get('tagname', '');
    	$tags = array_flip($this->get('oauth.weixin_user')->getTags());
    	if(isset($tags[$tagname])){
    		$this->get('oauth.weixin_user')->delete($tags[$tagname]);
    	}else{
    		return $this->error('删除失败，请选择正确的标签后再删除！');
    	}
    	//如何刷新右侧页面~
    	return $this->success('删除成功');
    }

    public function tagseditAction()
    {
    	$tagname = $this->getRequest()->get('tagname', '');
    	$oldtag = $this->getRequest()->get('oldtag', '');
    	$tags = array_flip($this->get('oauth.weixin_user')->getTags());
    	if(isset($tags[$oldtag])){
    		$info = $this->get('oauth.weixin_user')->update($tags[$oldtag], $tagname);
    	}else{
    		return $this->error('操作失败，请选择正确的标签后再操作！');
    	}
    	//如何刷新右侧页面~
    	die(json_encode($info));
    }
}