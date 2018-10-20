<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年6月23日
*/
namespace OAuthBundle\Services\WeiXin;

use OAuthBundle\Services\ErrorCode;

class WeiXinMessage extends WeiXin
{
	/**
     * 分析微信消息请求,
     * ex: $this->get('oauth.weixin_message')->responseMsg($token);
     */
    public function responseMsg($token=null, $postStr=null)
    {
        if($this->get('request')->getMethod() != "POST")
           return $this->get('request')->get('echostr','');

        ///$file = $this->get('core.common')->getTempRoot()."responseMsg.txt";

        //$cvtfile = $this->get('core.common')->getTempRoot()."ComponentVerifyTicket.txt";
        if($token&&$this->token!=$token)
        {
            $map = array();
            $map['token'] = $token;
            
            $account = $this->get('db.wxusers')->findOneBy($map);
            
            //重置公众号配置
            if(is_object($account)&&$account->getAppid())
                parent::init($account->getAppid());
        }

    	if(is_null($postStr)&&isset($GLOBALS["HTTP_RAW_POST_DATA"]))
    	    $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

    	if(!empty($postStr))
    	{
            try {

                $encryptType = $this->get('request')->get('encrypt_type','raw');
                
                switch($encryptType)
                {
                    //aes加密
                    case 'aes':
//                      $this->get('core.common')->saveFile($file, $this->token);
                        $postObj = parent::decryptMsg($postStr);                        
                        
                        break;
                    //不加密
                    case 'raw':
                        libxml_disable_entity_loader(true);
                        $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                        break;
                }

                $RX_TYPE = trim($postObj->MsgType);

                if(empty($RX_TYPE))
                    $RX_TYPE = $postObj->InfoType;             

                switch($RX_TYPE){
                    case "event":
                        $result = $this->receiveEvent($postObj);
                        break;
                    case "text":
                        $result = $this->receiveText($postObj);

                        break;
                    case "image":
                        $result = $this->receiveImage($postObj);
                        break;
                    case "location":
                        $result = $this->receiveLocation($postObj);
                        break;
                    case "voice":
                        $result = $this->receiveVoice($postObj);
                        break;
                    case "video":
                        $result = $this->receiveVideo($postObj);
                        break;
                    case "link":
                        $result = $this->receiveLink($postObj);
                        break;
                    // 授权
                    case 'authorized':
                        return "success";
                        break;
                    case 'updateauthorized':
                        return "success";
                        break;
                    case 'component_verify_ticket':
                        //file_put_contents ( $cvtfile, $postObj->ComponentVerifyTicket );
                        $this->account->setComponentVerifyTicket( $postObj->ComponentVerifyTicket );

                        parent::resetOwner(true);
                        return "success";
                        break;
                    default:
                        return "unknown msg type: ".$RX_TYPE;
                        break;
                }
                if($encryptType=='aes')
                    $result = parent::encryptMsg($result);

                return $result;
            } catch (\Exception $e) {
                return $this->transmitText($postObj, $e->getMessage());
            }
        }else{
            throw new \InvalidArgumentException('微信SDK没有设置这个MsgType');
        }
    }

    /**
     * 响应文本内容
     *
     * @param object   $object       XML数据转对象数据
     */
    private function receiveText($object)
    {
        $keyword = trim($object->Content);
        #判断聊天机器人是否开启，并返回机器人接口回复。
        //$transmit = $this->get('db.wxdefault')->robotSwitch($keyword, $this->token);
        //if(!empty($transmit))
        //    return $this->xmlContent($object, $transmit);

        #多客服跳转开关
        // $transmit = $this->get('db.wxdefault')->transmitKey($keyword, $this->token);
        // if($transmit)
        //     return $this->transmitService($object);

        #客户自设关键字内容返回
    	$result = $this->getSetKeyword($object, $keyword);

    	#全文检索服务
    	//if(empty($result))
        //    $result = $this->searchKeyword($keyword);

    	#默认内容返回
    	
    	if(empty($result))
            $result = $this->xmlContent($object, $this->get('db.wxdefault')->defaultKey('reply_default',$this->token));
    	#消息记录保存...是否需要?
    	//$this->get('...')->addText($object);

    	return $result;
    }

    /**
     * 响应事件消息
     *
     * @param object   $object       XML数据转对象数据
     */
    private function receiveEvent($object)
    {
        switch ($object->Event)
        {
            case "subscribe":
                $default = '哈哈';//$this->get('db.wxdefault')->defaultKey('subscribe_default',$this->token);
                $userinfo = $this->get('oauth.weixin_user')->getUserInfo($object->FromUserName);
                $userinfo['token'] = $this->token;
                $reset = $this->get('db.users')->saveWxUser($userinfo);
                $content = $reset ? "欢迎关注，恭喜你".$reset['reset']."成功\n帐号：".$reset['username']."\n密码：".$reset['truepwd'] : $default;
                return $this->xmlContent($object, $content);
                break;
            case "unsubscribe":
                $content = $this->get('db.wxdefault')->defaultKey('unsubscribe_default',$this->token);
                return $this->xmlContent($object, $content);
                break;
            case "SCAN":
                $content = "扫描场景二维码\nopenid：".$object->FromUserName."\n接受用户：".$object->ToUserName;
                return $this->xmlContent($object, $content);
                break;
            case "CLICK":
                return $this->clickMenu($object);
                break;
            case "LOCATION":
                //$content = "菜单上传位置：\n纬度 ".$object->Latitude.";\n经度 ".$object->Longitude;
                //return $this->xmlContent($object, $content);
                break;
            case "VIEW":
                $content = "跳转链接 ".$object->EventKey; //待扩展
                return $this->xmlContent($object, $content);
                break;
            case "MASSSENDJOBFINISH":
                $content = "消息ID：".$object->MsgID."，结果：".$object->Status."，粉丝数：".$object->TotalCount."，过滤：".$object->FilterCount."，发送成功：".$object->SentCount."，发送失败：".$object->ErrorCount;
            	#群发成功是否将记录保存到数据库？
                return $this->xmlContent($object, $content);
                break;
            default:
                $content = "receive a new event: ".$object->Event;
                return $this->xmlContent($object, $content);
                break;
        }
    }

    /**
     * 响应图片消息
     *
     * @param object   $object       XML数据转对象数据
     */
    private function receiveImage($object)
    {
        #下载图片？不推荐，毫无卵用"http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=$access_token&media_id=$mediaid";
        $content = array("MediaId"=>$object->MediaId);
        $result = $this->transmitImage($object, $content);
        return $result;
    }

    /**
     * 响应位置消息
     *
     * @param object   $object       XML数据转对象数据
     */
    private function receiveLocation($object)
    {
        $content = "你发送的位置纬度为：".$object->Location_X."；经度为：".$object->Location_Y."；缩放级别为：".$object->Scale."；位置为：".$object->Label;
        $result = $this->transmitText($object, $content);
        return $result;
    }

    /**
     * 响应语音消息
     *
     * @param object   $object       XML数据转对象数据
     */
    private function receiveVoice($object)
    {
        if (isset($object->Recognition) && !empty($object->Recognition)){
            $content = "语音识别为你服务，您刚才说的是：".$object->Recognition;
            $result = $this->transmitText($object, $content);
        }else{
            $content = array("MediaId"=>$object->MediaId);
            $result = $this->transmitVoice($object, $content);
        }
        #下载图片？不推荐，毫无卵用"http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=$access_token&media_id=$mediaid"
        return $result;
    }

    /**
     * 响应视频消息
     *
     * @param object   $object       XML数据转对象数据
     */
    private function receiveVideo($object)
    {
        $content = array("MediaId"=>$object->MediaId, "ThumbMediaId"=>$object->ThumbMediaId, "Title"=>"", "Description"=>"");
        $result = $this->transmitVideo($object, $content);
        return $result;
    }

    /**
     * 响应链接消息
     *
     * @param object   $object       XML数据转对象数据
     */
    private function receiveLink($object)
    {
        $content = "你发送的是链接，标题为：".$object->Title."；内容为：".$object->Description."；链接地址为：".$object->Url;
        $result = $this->transmitText($object, $content);
        return $result;
    }

    /**
     * 组装返回数据（xml）
     *
     * @param string/array   $content       返回给微信的数据
     */
    public function xmlContent($object, $content)
    {
    	if(empty($content))
    	    return false;
    	if(is_array($content)){
    		if (isset($content[0]['PicUrl'])){
    			$result = $this->transmitNews($object, $content);
    		}else{
    			if(isset($content['MusicUrl']))
                    $result = $this->transmitMusic($object, $content);
    		}
    	}else{
    		$result = $this->transmitText($object, $content);
    	}
    	return $result;
    }

    /**
     * 点击事件Click类型处理
     *
     * @param object   $object       XML数据转对象数据
     */
    public function clickMenu($object)
    {
    	$userWxMenu = $this->get('db.wxmenus')->getClickmenu($this->appId);
    	$wxclick = array_intersect($userWxMenu, array($object->EventKey));
    	if(empty($wxclick)){
    		return $this->xmlContent($object, '点击事件没有填写正确的关键字,请联系公众号管理者后台设置！');
    	}else{
    		return $this->getSetKeyword($object, array_shift($wxclick));
    	}
    }

    private function searchKeyword()
    {
    	return false; //全文检索关键字预留方法入口
    }

    #关键字绑定返回
    private function getSetKeyword($object, $keyword)
    {
    	$setkeywords = $this->get('db.wxkeyword')->HandleKeyword($this->token, $keyword);

        if(empty($setkeywords))
            return false;

        switch($setkeywords['type'])
        {
            case "image":
                $content = array("MediaId" => $setkeywords['content']);
                $result = $this->transmitImage($object, $content);
                break;
            case "voice":
                $content = array("MediaId" => $setkeywords['content']);
                $result = $this->transmitVoice($object, $content);
                break;
            case "video":
                $content = array("MediaId" => $setkeywords['content']);
                $result = $this->transmitVideo($object, $content);
                break;
            case "news":
                $result = $this->transmitNews($object, $setkeywords['articles']);
                break;
            default:
                $result = $this->transmitText($object, $setkeywords['content']);
                break;
        }
        return $result;
    }

    #回复文本消息
    private function transmitText($object, $content, $funcFlag = 0)
    {
        $textTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[%s]]></Content>
<FuncFlag>%d</FuncFlag>
</xml>";
        $resultStr = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content, $funcFlag);
        return $resultStr;
    }

    #回复图文消息
    private function transmitNews($object, array $newsArray)
    {
        if(!is_array($newsArray)) return;
        $itemTpl = "<item>
        <Title><![CDATA[%s]]></Title>
        <Description><![CDATA[%s]]></Description>
        <PicUrl><![CDATA[%s]]></PicUrl>
        <Url><![CDATA[%s]]></Url>
        </item>";
        $item_str = "";
        foreach ($newsArray as $item){
            $item_str .= sprintf($itemTpl, $item['Title'], $item['Description'], $item['PicUrl'], $item['Url']);
        }
        $xmlTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[news]]></MsgType>
<ArticleCount>%s</ArticleCount>
<Articles>$item_str</Articles>
</xml>";
        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time(), count($newsArray));
        return $result;
    }

    #回复音乐消息
    private function transmitMusic($object, $musicArray)
    {
        $itemTpl = "<Music>
    <Title><![CDATA[%s]]></Title>
    <Description><![CDATA[%s]]></Description>
    <MusicUrl><![CDATA[%s]]></MusicUrl>
    <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
</Music>";
        $item_str = sprintf($itemTpl, $musicArray['Title'], $musicArray['Description'], $musicArray['MusicUrl'], $musicArray['HQMusicUrl']);
        $xmlTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[music]]></MsgType>
$item_str
</xml>";
        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    #回复图片消息
    private function transmitImage($object, $imageArray)
    {
        $itemTpl = "<Image>
    <MediaId><![CDATA[%s]]></MediaId>
</Image>";
        $item_str = sprintf($itemTpl, $imageArray['MediaId']);
        $xmlTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[image]]></MsgType>
$item_str
</xml>";
        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    #回复语音消息
    private function transmitVoice($object, $voiceArray)
    {
        $itemTpl = "<Voice>
    <MediaId><![CDATA[%s]]></MediaId>
</Voice>";
        $item_str = sprintf($itemTpl, $voiceArray['MediaId']);
        $xmlTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[voice]]></MsgType>
$item_str
</xml>";
        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    #回复视频消息
    private function transmitVideo($object, $videoArray)
    {
        $itemTpl = "<Video>
    <MediaId><![CDATA[%s]]></MediaId>
    <ThumbMediaId><![CDATA[%s]]></ThumbMediaId>
    <Title><![CDATA[%s]]></Title>
    <Description><![CDATA[%s]]></Description>
</Video>";
        $item_str = sprintf($itemTpl, $videoArray['MediaId'], $videoArray['ThumbMediaId'], $videoArray['Title'], $videoArray['Description']);
        $xmlTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[video]]></MsgType>
$item_str
</xml>";
        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    #回复多客服消息
    private function transmitService($object)
    {
        $xmlTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[transfer_customer_service]]></MsgType>
</xml>";
        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }
    
    /**
     * 提取出xml数据包中的加密消息
     * @param string $xmltext 待提取的xml字符串
     * @return string 提取出的加密消息字符串
     */
    public function extract($xmltext)
    {
        try {
            $xml = new \DOMDocument();
            $xml->loadXML($xmltext);
            $array_e = $xml->getElementsByTagName('Encrypt');
            $array_a = $xml->getElementsByTagName('ToUserName');
            $encrypt = $array_e->item(0)->nodeValue;
            $tousername = $array_a->item(0)->nodeValue;
            return array(0, $encrypt, $tousername);
        } catch (\Exception $e) {
            //print $e . "\n";
            return array(ErrorCode::$ParseXmlError, null, null);
        }
    }
    
    /**
     * 生成xml消息
     * @param string $encrypt 加密后的消息密文
     * @param string $signature 安全签名
     * @param string $timestamp 时间戳
     * @param string $nonce 随机字符串
     */
    public function generate($encrypt, $signature, $timestamp, $nonce)
    {
        $format = "<xml>
<Encrypt><![CDATA[%s]]></Encrypt>
<MsgSignature><![CDATA[%s]]></MsgSignature>
<TimeStamp>%s</TimeStamp>
<Nonce><![CDATA[%s]]></Nonce>
</xml>";
        return sprintf($format, $encrypt, $signature, $timestamp, $nonce);
    }

}