<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年6月23日
*/
namespace OAuthBundle\Services\WeiXin;

class WeiXinMassMessage extends WeiXin
{

    private $baseUrlNews    = 'https://api.weixin.qq.com/cgi-bin/media/uploadnews?access_token=%s';
    private $baseUrlTag     = 'https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token=%s';
    private $baseUrlOpenId  = 'https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token=%s';
    private $baseUrlDelete  = 'https://api.weixin.qq.com/cgi-bin/message/mass/delete?access_token=%s';
    private $baseUrlPreview = 'https://api.weixin.qq.com/cgi-bin/message/mass/preview?access_token=%s';
    private $baseUrlGet     = 'https://api.weixin.qq.com/cgi-bin/message/mass/get?access_token=%s';

    /**
     * 新建群发图文消息
     *
     * @param array   $articles      图文消息数据
     */
    public function createMassNews(array $article)
    {
    	$options = array();
        $options['me_url'] = sprintf($this->baseUrlNews, parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['articles'] = $article;
        $data = urldecode(json_encode($data));
        
        $info = $this->resourceOwner->getMeUrl($data);
        parent::getError($info);
        
        return $info;
    }

    /**
     * 根据标签群发消息
     *
     * @param int      $tag_id      群发到的标签的tag_id，参加用户管理中用户分组接口，若is_to_all值为true，可不填写tag_id
     * @param string   $msgtype     群发的消息类型，图文消息为mpnews，文本消息为text，语音为voice，音乐为music，图片为image，视频为video，卡券为wxcard
     * @param array    $value       群发数据ex:array("media_id"=>"123dsdajkasd231j")
     * @param boolean  $is_to_all   用于设定是否向全部用户发送，值为true或false，选择true该消息群发给所有用户，选择false可根据tag_id发送给指定群组的用户
     */
    public function sendMassTag($tag_id, $msgtype, array $value, $is_to_all = false)
    {
        $options = array();
        $options['me_url'] = sprintf($this->baseUrlTag, parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data =array();
        $data['filter'] = array('is_to_all' => $is_to_all, 'tag_id' => (int)$tag_id);
        $data[$msgtype] = $value;
        $data['msgtype'] = trim($msgtype);
        $data = json_encode($data);
        
        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);
        
        return false;
    }

    /**
     * 群发消息(所有关注者)
     *
     * @param string   $msgtype     群发的消息类型，图文消息为mpnews，文本消息为text，语音为voice，音乐为music，图片为image，视频为video，卡券为wxcard
     * @param array    $value       群发数据ex:array("media_id"=>"123dsdajkasd231j")
     */
    public function sendMassAll($msgtype, array $value)
    {
        $options = array();
        $options['me_url'] = sprintf($this->baseUrlTag, parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data =array();
        $data['filter'] = array('is_to_all' => true, 'tag_id' => '');
        $data[$msgtype] = $value;
        $data['msgtype'] = trim($msgtype);
        $data = json_encode($data);
        
        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);
        
        return false;
    }

    /**
     * 根据OpenID群发消息
     *
     * @param array    $openids     一串OpenID列表，OpenID最少2个，最多10000个ex:array('OPENID1', 'OPENID2');
     * @param string   $msgtype     群发的消息类型，图文消息为mpnews，文本消息为text，语音为voice，音乐为music，图片为image，视频为video，卡券为wxcard
     * @param array    $value       群发数据ex:array("media_id"=>"123dsdajkasd231j")
     */
    public function sendMassOpenId(array $openids, $msgtype, array $value)
    {
        $options = array();
        $options['me_url'] = sprintf($this->baseUrlOpenId, parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['touser'] = $openids;
        $data[$msgtype] = $value;
        $data['msgtype'] = trim($msgtype);
        $data = json_encode($data);
        
        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;
        
        parent::getError($info);      
        return false;
    }

    /**
     * 删除群发
     *
     * @param int      $msg_id       群发的消息ID
     */
    public function deleteMass($msg_id)
    {
        $options = array();
        $options['me_url'] = sprintf($this->baseUrlDelete, parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['msg_id'] = (int)$msg_id;
        $data = json_encode($data);
        
        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);
        return false;
    }

    /**
     * 预览接口
     *
     * @param string   $openid      需要预览的OpenID
     * @param string   $msgtype     群发的消息类型，图文消息为mpnews，文本消息为text，语音为voice，音乐为music，图片为image，视频为video，卡券为wxcard
     * @param array    $value       群发数据ex:array("media_id"=>"123dsdajkasd231j")
     * @param string   $appid       有appid时是针对微信号进行预览（而非openID），openid和appid同时有时，以appid优先。
     */
    public function previewMass($openid, $msgtype, array $value, $appid = null)
    {
        $options = array();
        $options['me_url'] = sprintf($this->baseUrlPreview, parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        if(is_null($appid)){
            $data['touser'] = trim($openid);
        }else{
            $data['towxname'] = trim($appid);
        }
        $data[$msgtype] = $value;
        $data['msgtype'] = trim($msgtype);
        $data = json_encode($data);
        
        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;
        
        parent::getError($info);      
        return false;
    }

    /**
     * 查询群发消息发送状态
     *
     * @param int      $msg_id       群发的消息ID
     */
    public function getMass($msg_id)
    {
        $options = array();
        $options['me_url'] = sprintf($this->baseUrlGet, parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['msg_id'] = (int)$msg_id;
        $data = json_encode($data);
        
        $info = $this->resourceOwner->getMeUrl($data);

        parent::getError($info);

        return $info;
    }

}