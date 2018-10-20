<?php
/**
 * 新版客服功能
 *
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年6月29日
*/
namespace OAuthBundle\Services\WeiXin;

class WeiXinStaff extends WeiXin
{
    /**
     * 客服管理Url
     * @var string
     */
    private $customserviceUrl = 'https://api.weixin.qq.com/cgi-bin/customservice/%s?access_token=%s';

    /**
     * 客服账号管理Url
     * @var string
     */
    private $kfaccountUrl = 'https://api.weixin.qq.com/customservice/kfaccount/%s?access_token=%s';

    /**
     * 多客服 会话控制Url
     * @var string
     */
    private $kfsessionUrl = 'https://api.weixin.qq.com/customservice/kfsession/%s?access_token=%s';

    /**
     * 查询客服聊天记录Url
     * @var string
     */
    private $msgrecordUrl = 'https://api.weixin.qq.com/customservice/msgrecord/getmsglist?access_token=%s';



    /**
     * 消息转发到客服(在响应包中调用此服务方法返回微信需要的格式数据)
     * 微信服务器会先将消息POST到开发者填写的url上，如果希望将消息转发到多客服系统，则需要开发者在响应包中返回
     *
     * @param string   $openId   接收方帐号（收到的OpenID）
     * @return object|null xml
     */
    public function msgReply($openId)
    {
        $data = array();
        $fromUserName = $this->account->getWxaccount();
        $data['ToUserName'] = $openId;
        $data['FromUserName'] = $fromUserName;
        $data['CreateTime'] = time();
        $data['MsgType'] = 'transfer_customer_service';

        $info = $this->arrayToXML($data);
        return $info;
    }

    /**
     * 消息转发到指定客服(在响应包中调用此服务方法返回微信需要的格式数据)
     * 微信服务器会先将消息POST到开发者填写的url上，如果希望将消息转发到多客服系统，则需要开发者在响应包中返回
     *
     * @param string   $openId   接收方帐号（收到的OpenID）
     * @param string   $account  指定会话接入的客服账号
     */
    public function msgByReply($openId, $account)
    {
        $data = array();
        $fromUserName = $this->account->getWxaccount();
        $data['ToUserName'] = $openId;
        $data['FromUserName'] = $fromUserName;
        $data['CreateTime'] = time();
        $data['MsgType'] = 'transfer_customer_service';
        $data['TransInfo']['KfAccount'] = $account;

        $info = $this->arrayToXML($data);
        return $info;
    }

    /**
     * 获取所有客服信息列表
     *
     */
    public function getAccountLists()
    {
        $options = array();
        $options['me_url'] = sprintf($this->customserviceUrl, 'getkflist', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $info = $this->resourceOwner->getMeUrl();
        parent::getError($info);

        return $info;
    }

    /**
     * 获取所有在线的客服信息列表
     *
     */
    public function getAccountOnlines()
    {
        $options = array();
        $options['me_url'] = sprintf($this->customserviceUrl, 'getonlinekflist', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $info = $this->resourceOwner->getMeUrl();
        parent::getError($info);

        return $info;
    }

    /**
     * 添加客服帐号
     *
     * @param string   $kfAccount  完整客服帐号，格式为：帐号前缀@公众号微信号，帐号前缀最多10个字符，必须是英文、数字字符或者下划线，后缀为公众号微信号，长度不超过30个字符
     * @param string   $nickname  客服昵称，最长16个字
     */
    public function addAccount($kfAccount, $nickname)
    {
        $options = array();
        $options['me_url'] = sprintf($this->kfaccountUrl, 'add', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['kf_account'] = urlencode(trim($kfAccount));
        $data['nickname'] = urlencode(trim($nickname));
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 邀请绑定客服帐号
     *
     * @param string   $kfAccount  完整客服帐号，格式为：帐号前缀@公众号微信号
     * @param string   $inviteWx  接收绑定邀请的客服微信号
     */
    public function bindAccount($kfAccount, $inviteWx)
    {
        $options = array();
        $options['me_url'] = sprintf($this->kfaccountUrl, 'inviteworker', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['kf_account'] = urlencode(trim($kfAccount));
        $data['invite_wx'] = urlencode(trim($inviteWx));
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 设置(修改)客服信息
     *
     * @param string   $kfAccount  完整客服帐号，格式为：帐号前缀@公众号微信号
     * @param string   $nickname  客服昵称，最长16个字
     */
    public function setAccount($kfAccount, $nickname)
    {
        $options = array();
        $options['me_url'] = sprintf($this->kfaccountUrl, 'update', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['kf_account'] = urlencode(trim($kfAccount));
        $data['nickname'] = urlencode(trim($nickname));
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 设置（上传）客服头像
     *
     * @param string   $kfAccount  完整客服帐号，格式为：帐号前缀@公众号微信号
     * @param array   $media  媒体文件标识，有filename、filelength、content-type 等信息，文件大小为5M 以内
     */
    public function setAccountAvatar($kfAccount, $media)
    {
        $options = array();
        $options['me_url'] = sprintf($this->kfaccountUrl, 'uploadheadimg', parent::getAccessToken());

        //$this->resourceOwner->setOption($options);

        $data = array();
        $data['kf_account'] = trim($kfAccount);
        $data['media'] = $media;
        $info = $this->uploadRequest($options['me_url'], $data, array('media'));
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 删除客服帐号
     *
     * @param string   $kfAccount  完整客服帐号，格式为：帐号前缀@公众号微信号
     */
    public function deleteAccount($kfAccount)
    {
        $options = array();
        $options['me_url'] = sprintf($this->kfaccountUrl, 'del', parent::getAccessToken());

        $this->resourceOwner->setOption($options);

        $data = array();
        $data['kf_account'] = urlencode(trim($kfAccount));
        $data = urldecode(json_encode($data));
        $info = $this->resourceOwner->getMeUrl($data);

        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 创建会话
     * 只能为在线的客服（PC客户端在线，或者已绑定多客服助手）创建会话
     *
     * @param string   $kfAccount  完整客服帐号，格式为：帐号前缀@公众号微信号
     * @param string   $openid  粉丝的openid
     */
    public function addSession($kfAccount, $openid)
    {
        $options = array();
        $options['me_url'] = sprintf($this->kfsessionUrl, 'create', parent::getAccessToken());

        $this->resourceOwner->setOption($options);

        $data = array();
        $data['kf_account'] = trim($kfAccount);
        $data['openid'] = trim($openid);
        $data = json_encode($data);
        $info = $this->resourceOwner->getMeUrl($data);

        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 关闭会话
     *
     * @param string   $kfAccount  完整客服帐号，格式为：帐号前缀@公众号微信号
     * @param string   $openid  粉丝的openid
     */
    public function closeSession($kfAccount, $openid)
    {
        $options = array();
        $options['me_url'] = sprintf($this->kfsessionUrl, 'close', parent::getAccessToken());

        $this->resourceOwner->setOption($options);

        $data = array();
        $data['kf_account'] = trim($kfAccount);
        $data['openid'] = trim($openid);
        $data = json_encode($data);
        $info = $this->resourceOwner->getMeUrl($data);

        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 获取客户会话状态
     *
     * @param string   $openid  粉丝的openid
     */
    public function getSession($openid)
    {
        $options = array();
        $options['me_url'] = sprintf($this->kfsessionUrl, 'getsession', parent::getAccessToken());

        $this->resourceOwner->setOption($options);

        $data = array();
        $data['openid'] = trim($openid);
        $data = json_encode($data);
        $info = $this->resourceOwner->getMeUrl($data);

        parent::getError($info);

        return $info;
    }

    /**
     * 获取客服会话列表
     *
     * @param string   $openid  粉丝的openid
     */
    public function getSessionLists($openid)
    {
        $options = array();
        $options['me_url'] = sprintf($this->kfsessionUrl, 'getsessionlist', parent::getAccessToken());

        $this->resourceOwner->setOption($options);

        $data = array();
        $data['openid'] = trim($openid);
        $data = json_encode($data);
        $info = $this->resourceOwner->getMeUrl($data);

        parent::getError($info);

        return $info;
    }

    /**
     * 获取未接入会话列表
     *
     */
    public function getSessionWaiters($openid)
    {
        $options = array();
        $options['me_url'] = sprintf($this->sessionUrl, 'getwaitcase', parent::getAccessToken());

        $this->resourceOwner->setOption($options);

        $info = $this->resourceOwner->getMeUrl($options);

        parent::getError($info);

        return $info;
    }

    /**
     * 获取客服聊天记录
     *
     */
    public function getAccountRecords($openid)
    {
        $options = array();
        $options['me_url'] = sprintf($this->sessionUrl, 'getmsglist', parent::getAccessToken());

        $this->resourceOwner->setOption($options);

        $info = $this->resourceOwner->getMeUrl($options);

        parent::getError($info);

        return $info;
    }


}