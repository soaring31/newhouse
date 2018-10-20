<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年6月23日
*/
namespace OAuthBundle\Services\WeiXin;

class WeiXinUser extends WeiXin
{

    private $baseUrlTag     = 'https://api.weixin.qq.com/cgi-bin/tags/%s?access_token=%s';
    private $baseUserUrlTag = 'https://api.weixin.qq.com/cgi-bin/user/tag/%s?access_token=%s';
    private $baseUsersUrl   = 'https://api.weixin.qq.com/cgi-bin/user/%s?access_token=%s';
    private $baseRemarkUrl  = 'https://api.weixin.qq.com/cgi-bin/user/info/%s?access_token=%s';
    private $baseUserInfoUrl = 'https://api.weixin.qq.com/cgi-bin/user/%s?access_token=%s&openid=%s&lang=%s';
    private $baseMembersUrlTag = 'https://api.weixin.qq.com/cgi-bin/tags/members/%s?access_token=%s';

    /**
     * 新建标签
     *
     * @param string   $tag       标签名称
     */
    public function create($tag)
    {
    	$options = array();
        $options['me_url'] = sprintf($this->baseUrlTag, 'create', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['tag']['name'] = urlencode($tag);
        $data = urldecode(json_encode($data));
        
        $info = $this->resourceOwner->getMeUrl($data);
        parent::getError($info);
        
        return $info;
    }

    /**
     * 获取所有标签
     */
    public function getTags(array $tags=array())
    {
        $options = array();
        $options['me_url'] = sprintf($this->baseUrlTag, 'get', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $info = $this->resourceOwner->getMeUrl();
        parent::getError($info);
        
        
        foreach($info['tags'] as $value){
            $tags[$value['id']] = $value['name'];
        }
        unset($info);
        return $tags;
    }
    
    /**
     * 同步粉丝
     * @return boolean
     */
    public function syncFans()
    {
        $map = array();
        $batchData = array();
        $token = $this->get('request')->getSession()->get('wxtoken');
    
        $this->get('weixin.wxfans')->dbalUpdate(array('is_sync'=>0),array('token'=>$token));
    
        $tags = self::getTags();
        $users = $this->get('oauth.weixin_user')->getUsers();
        if(empty($users['total']))
            return false;
    
        foreach($users['data']['openid'] as $value)
        {
    
            $fans = $this->get('weixin.wxfans')->findOneBy(array('openid' => $value));
    
            $userinfo = self::getUserInfo($value);
            $userinfo['token'] = $token;
            $userinfo['is_sync'] = 1;
            $userinfo['tagname'] = $userinfo['tagid_list'] ? implode(',', array_intersect_key($tags, array_flip($userinfo['tagid_list']))) : '';
            $userinfo['tagid_list'] = $userinfo['tagid_list'] ? serialize($userinfo['tagid_list']) : '';
            $userinfo['sex'] = empty($userinfo['sex']) ? '未知' : ($userinfo['sex']==1 ? '男' : '女');
    
            if($fans)
                $this->get('weixin.wxfans')->update($fans->getId(),$userinfo, null, false);
            else
                $batchData[] = $userinfo;
        }
    
        if(count($batchData)>0)
            $this->get('weixin.wxfans')->batchadd($batchData);
    
        //删除已清掉的粉丝
        $map['token'] = $token;
        $map['is_sync'] = 0;
        $this->get('weixin.wxfans')->dbalDelete($map);
    
        return true;
    }

    /**
     * 编辑标签
     *
     * @param int      $tagid       要改的标签id
     * @param string   $newtag      标签新名称
     */
    public function update($tagid, $newtag)
    {
        $options = array();
        $options['me_url'] = sprintf($this->baseUrlTag, 'update', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['tag']['name'] = urlencode($newtag);
        $data['tag']['id'] = (int)$tagid;
        $data = urldecode(json_encode($data));
        
        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);
        
        return false;
    }

    /**
     * 删除标签
     *
     * @param int   $tagid       要删除的标签id
     */
    public function delete($tagid)
    {
        $options = array();
        $options['me_url'] = sprintf($this->baseUrlTag, 'delete', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['tag']['id'] = (int)$tagid;
        $data = json_encode($data);

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);
        
        return false;
    }

    /**
     * 获取标签下粉丝列表
     *
     * @param int      $tagid       要改的标签id
     * @param string   $next_openid 第一个拉取的OPENID，不填默认从头开始拉取
     */
    public function getTagUsers($tagid, $next_openid)
    {
        $options = array();
        $options['me_url'] = sprintf($this->baseUserUrlTag, 'get', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['tag']['id'] = (int)$tagid;
        $data['tag']['next_openid'] = $next_openid;
        
        $info = $this->resourceOwner->getMeUrl($data);
        parent::getError($info);
        
        return $info;
    }

    /**
     * 批量为用户打标签
     *
     * @param int      $tagid       要打的标签id
     * @param array    $openid_list 粉丝列表,以openid的形式
     */
    public function setMembersTags(array $openid_list, $tagid)
    {
        $options = array();
        $options['me_url'] = sprintf($this->baseMembersUrlTag, 'batchtagging', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['openid_list'] = $openid_list;
        $data['tagid'] = (int)$tagid;
        $data = json_encode($data);
        
        $info = $this->resourceOwner->getMeUrl($data);

        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);
        
        return false;
    }

    /**
     * 批量为用户删除标签
     *
     * @param int      $tagid       要删除的标签id
     * @param array    $openid_list 粉丝列表,以openid数组的形式
     */
    public function deleteMembersTags(array $openid_list, $tagid)
    {
        $options = array();
        $options['me_url'] = sprintf($this->baseMembersUrlTag, 'batchuntagging', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['openid_list'] = $openid_list;
        $data['tagid'] = (int)$tagid;
        $data = json_encode($data);
        
        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);
        
        return false;
    }

    /**
     * 获取用户身上的标签列表
     *
     * @param string   $openid 用户的OPENID
     */
    public function getUserTags($openid)
    {
        $options = array();
        $options['me_url'] = sprintf($this->baseUrlTag, 'getidlist', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['tag']['openid'] = $openid;
        $data = json_encode($data);
        
        $info = $this->resourceOwner->getMeUrl($data);
        parent::getError($info);
        
        return $info;
    }

    /**
     * 设置用户备注名
     *
     * @param string   $openid 用户的OPENID
     * @param string   $remark 用户的备注名
     */
    public function setUserRemark($openid, $remark)
    {
        $options = array();
        $options['me_url'] = sprintf($this->baseRemarkUrl, 'updateremark', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['openid'] = $openid;
        $data['remark'] = urlencode($remark);
        $data = urldecode(json_encode($data));
        
        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);
        
        return false;
    }

    /**
     * 获取用户基本信息
     *
     * @param string   $openid 用户的OPENID
     * @param string   $lang   返回国家地区语言版本，zh_CN 简体，zh_TW 繁体，en 英语
     */
    public function getUserInfo($openid, $lang = 'zh_CN')
    {
        $options = array();
        $options['me_url'] = sprintf($this->baseUserInfoUrl, 'info', parent::getAccessToken(), $openid, $lang);
        $this->resourceOwner->setOption($options);

        $info = $this->resourceOwner->getMeUrl();

        parent::getError($info);
        return $info;
    }

    /**
     * 获取用户列表（关注此微信公众的用户）
     */
    public function getUsers()
    {
        $options = array();
        $options['me_url'] = sprintf($this->baseUsersUrl, 'get', parent::getAccessToken());
        $this->resourceOwner->setOption($options);
        
        $info = $this->resourceOwner->getMeUrl();
        parent::getError($info);
        
        return $info;
    }

}