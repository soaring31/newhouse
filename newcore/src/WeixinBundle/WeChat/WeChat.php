<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年7月4日
*/
namespace WeixinBundle\WeChat;

use CoreBundle\Services\ServiceBase;
use WeixinBundle\Security\Crypt\WXBizMsgCrypt;
use Symfony\Component\DependencyInjection\ContainerInterface;

class WeChat extends ServiceBase implements WeChatInterface
{
    private $token;
    private $wxuser;
    private $data = array();
    private $fun;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    public function init($wxuser)
    {       
        $this->wxuser = $wxuser;
        $this->token = $wxuser->getToken();
    }

    public function check($token)
    {    
        //第三方授权[暂未开发]
        if($this->wxuser->getAuthType() == 1)
        {
            //$map = array();
            //$account_info = $this->get('db.user_oauth')->findOneBy($map);
            //$this->wxuser['pigsecret'] 	= $account_info['token'];
            //$this->wxuser['appid'] 		= $account_info['appId'];
            //$this->wxuser['aeskey'] 	= $account_info['encodingAesKey'];
        }

        $xml = file_get_contents("php://input");
        
        if(empty($xml))
            return false;

        //消息加密方式
        if ($this->wxuser->getEncode()==2)
        {
            $this->data = $this->decodeMsg($xml);
            
            return true;
        }
        
        $xml = new \SimpleXMLElement($xml);
        
        if(empty($xml))
            return array();

        foreach ($xml as $key => $value) {
            $this->data[$key] = strval($value);
        }
    }
    
    public function reply($data)
    {
        
        if(isset($data['Event'])&&$data['Event'])
        {
            $eventReplyClassName = (isset($data['Event'])?$data['Event']:'') . 'EventReply';
    
    		if (class_exists($eventReplyClassName)) {
    			$eventReplyClassName = new $eventReplyClassName($this->token, $this->data['FromUserName'], $data, $this->siteUrl);
    			return $eventReplyClassName->index();
    		}
    		
    		switch($data['Event'])
    		{
    		    case 'CLICK':
    		        $data['Content'] = isset($data['EventKey'])?$data['EventKey']:'';
    		        break;
    		    case 'SCAN':
//     		        $data['Content'] = $this->_getRecognition($data['EventKey']);
    		        break;
    		    case 'MASSSENDJOBFINISH':
    		        break;
    		    case 'subscribe':
    		        break;
    		    case 'unsubscribe':
    		        break;
    		    case 'LOCATION':
    		        break;
    		}
        }
        
        if (isset($data['MsgType'])&&isset($data['Recognition'])&&$data['Recognition']&&'voice' == $data['MsgType'])
            $data['Content'] = $data['Recognition'];
        
        if (isset($data['Content']))
        {
            //获取服务器IP
            if ($data['Content'] == 'wechat ip')
                return array($_SERVER['REMOTE_ADDR'], 'text');
            
            if (strtolower($data['Content']) == 'go') {
                $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
                $apidata = $this->api_notice_increment('http://we-cdn.net', $xml, 1);
                header('Content-type: text/xml');
                exit($apidata);
                return false;
            }
            
            if(!(strpos($data['Content'], '审核') === FALSE))
            {
                return array(self::_shenhe(str_replace('审核', '', $data['Content'])), 'text');
            }
            
            if (!(strpos($data['Content'], '附近') === false)) {
                return self::_fujin(array(str_replace('附近', '', $data['Content'])));
            }
        }
        
        $data['Content'] = isset($data['Content'])?$data['Content']:'';
        
        switch($data['Content'])
        {
            case '地图':
                return array('地图', 'text');
                break;
            case 'help':
            case '帮助':
                return array('帮助', 'text');
                break;
            case '会员':
                return array('会员', 'text');
                break;
            default:
                return array('首页', 'text');
                break;
        }
    }
    
    private function _fujin($keyword)
    {
        $keyword = implode('', $keyword);
    
        if ($keyword == false) {
            return '很难过,无法识别主人的指令,正确使用方法是:输入【附近+关键词】 输入地理位置的时候就OK啦';
        }
    
        return '已经接收到你的指令' . "\n" . '请发送您的地理位置(对话框右下角点击＋号，然后点击“位置”)给我哈';
    }

    /**
     * 审核
     * @param string $name
     * @return string
     */
    private function _shenhe($name)
    {
        self::_behaviordata('usernameCheck','','1');

        if(empty($name)){
            return '正确的审核帐号方式是：审核+帐号';
        }else{
            $user = $this->get('db.users')->findOneBy(array('username'=>$name));
            if($user==false){
                return "提醒您,您未注册\n正确的审核帐号方式是：审核+帐号,不含+号";
            }else{               
                $data = array();
                $data['identifier'] = $this->data['FromUserName'];
                $data['type'] = 'weixin';
                $data['uid'] = $user['id'];

                $useroauth = $this->get('db.user_oauth')->findOneBy($data);

                $data['viptime'] = time()+intval($this->get('core.common')->C('reg_validdays'))*24*3600;
                $data['gid'] = $this->get('core.common')->C('reg_groupid');

                if($useroauth)
                    $this->get('db.user_oauth')->update($useroauth->getId(),$useroauth);
                else
                    $this->get('db.user_oauth')->add($data);

                return '恭喜您,您的帐号已经审核,您现在点击网页上的蓝色按钮就可以体验啦!';
            }
        }
    }
    
    /**
     * 加密消息
     * @param string $sRespData
     * @return string
     */
    public function encodeMsg($sRespData, $nonce)
    {
        $sReqTimeStamp = time();
    
        //解析之后的明文
        $encryptMsg = "";

        $pc = new WXBizMsgCrypt($this->wxuser->getToken(), $this->wxuser->getEaskey(), $this->wxuser->getAppid());
        $sRespData = str_replace('<?xml version="1.0"?>','',$sRespData);
        $errCode = $pc->encryptMsg($sRespData, $sReqTimeStamp, $nonce, $encryptMsg);
        
        if($errCode!=0)
            throw new \InvalidArgumentException($errCode);

        return $encryptMsg;
    }

    /**
     * 解密消息
     * @param string $msg
     * @return array
     */
    public function decodeMsg($msg)
    {
        $sReqMsgSig = $this->get('request')->get('msg_signature', '');
        $sReqTimeStamp = $this->get('request')->get('timestamp', '');

        $sReqNonce = $this->get('request')->get('nonce', '');;

        // post请求的密文数据
        $sReqData = $msg;

        // 解析之后的明文
        $sMsg = "";

        $pc = new WXBizMsgCrypt($this->wxuser->getToken(), $this->wxuser->getEaskey(), $this->wxuser->getAppid());
        $errCode = $pc->decryptMsg($sReqMsgSig, $sReqTimeStamp, $sReqNonce, $sReqData, $sMsg);
    
        if($errCode!=0)
            throw new \InvalidArgumentException($errCode);
        
        /*
         $xml = new DOMDocument();
         $xml->loadXML($sMsg);
         $content = $xml->getElementsByTagName('Content')->item(0)->nodeValue;
         */
        $data = array();
        $xml = new \SimpleXMLElement($sMsg);
        
        if(empty($xml)||!is_array($xml))
            return $data;

        foreach ($xml as $key => $value) {
            $data[$key] = strval($value);
        }
        return $data;
    }
    
    /**
     * 获取微信推送的数据
     * @return array 转换为数组后的数据
     */
    public function request()
    {
        return $this->data;
    }
    
    /**
     * * 响应微信发送的信息（自动回复）
     * @param  string $to      接收用户名
     * @param  string $from    发送者用户名
     * @param  array  $content 回复信息，文本信息为string类型
     * @param  string $type    消息类型
     * @param  string $flag    是否新标刚接受到的信息
     * @return string          XML字符串
     */
    public function response($content, $type = 'text', $flag = 0)
    {
        /* 基础数据 */
        $this->data = array(
            'ToUserName'   => $this->data['FromUserName'],
            'FromUserName' => $this->data['ToUserName'],
            'CreateTime'   => time(),
            'MsgType'      => $type,
        );
        
        $nonce = $this->get('request')->get('nonce');
        $encrypt_type = $this->get('request')->get('encrypt_type', '');
    
        /* 添加类型数据 */
        $this->$type($content);

        /* 添加状态 */
        $this->data['FuncFlag'] = $flag;
    
        /* 转换数据为XML */
        $xml = new \SimpleXMLElement('<xml></xml>');
        $this->_data2xml($xml, $this->data);

        if ($encrypt_type=='aes')
            exit($this->encodeMsg($xml->asXML(), $nonce));
        else
            exit($xml->asXML());   
    }
    
    /**
     * 回复文本信息
     * @param  string $content 要回复的信息
     */
    private function text($content)
    {
        $this->data['Content'] = $content;
    }
    
    /**
     * 回复音乐信息
     * @param  string $content 要回复的音乐
     */
    private function music($music)
    {
        list(
            $music['Title'],
            $music['Description'],
            $music['MusicUrl'],
            $music['HQMusicUrl']
        ) = $music;
        $this->data['Music'] = $music;
    }
    
    /**
     * 回复图文信息
     * @param  string $news 要回复的图文内容
     */
    private function news($news)
    {
        $articles = array();
        foreach ($news as $key => $value) {
            list(
                $articles[$key]['Title'],
                $articles[$key]['Description'],
                $articles[$key]['PicUrl'],
                $articles[$key]['Url']
            ) = $value;
            if($key >= 9) { break; } //最多只允许10调新闻
        }
        $this->data['ArticleCount'] = count($articles);
        $this->data['Articles'] = $articles;
    }

    private function transfer_customer_service($content)
    {
        $this->data['Content'] = '';
    }
    
    private function _data2xml($xml, $data, $item = 'item')
    {
        foreach ($data as $key => $value) {
            /* 指定默认的数字key */
            is_numeric($key) && $key = $item;
    
            /* 添加子元素 */
            if(is_array($value) || is_object($value)){
                $child = $xml->addChild($key);
                $this->data2xml($child, $value, $item);
            } else {
                if(is_numeric($value)){
                    $child = $xml->addChild($key, $value);
                } else {
                    $child = $xml->addChild($key);
                    $node  = dom_import_simplexml($child);
                    $node->appendChild($node->ownerDocument->createCDATASection($value));
                }
            }
        }
    }
    
    /**
     * 检验微信的signature
     * @param string $token
     * @return boolean
     */
    public function checkSignature($token)
    {
        //微信加密签名，signature结合了开发者填写的token参数和请求中的timestamp参数、nonce参数。
        $signature = $this->get('request')->get('signature','');
        
        //时间戳
        $timestamp = $this->get('request')->get('timestamp','');
        
        //随机数
        $nonce = $this->get('request')->get('nonce','');
        
        $tmpArr = array($token, $timestamp, $nonce);
        
        //排序
        sort($tmpArr, SORT_STRING);
        
        $tmpStr = implode( $tmpArr );
        
        $tmpStr = sha1( $tmpStr );
        
        if( $tmpStr == $signature )
            return true;
        else
            return false;
    }
}