<?php
/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2016年6月29日
 */
namespace CoreBundle\Services\Sms;

use CoreBundle\Services\ServiceBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Sms extends ServiceBase implements SmsInterface
{
    protected $api  = ''; //api接口类型(提供商)
    protected $appId;
    protected $appSecret;

    protected $tela = array();
    protected $msga = array();
    protected $tel = '';
    protected $msg = '';
    protected $type = 0; // 发动类型: <空>-普通短信; vcode-认证码; login-登录; 
    protected $smscode = 0; // 认证码

    protected $cfg_mchar = 70; // 一条信息,文字个数(小灵通65个字)
    protected $cfg_mtels = 100; // 一次发送,最多200个手机号码个数

    protected $user = null;
    protected $userId = 0;
    protected $uquote = null;
    protected $noCheck = false;
    protected $msgType = 'mobile';
    protected $resourceOwner;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        self::init();
    }

    /**
     * 初始化
     * @param array $extraParameters
     * @return boolean
     */
    public function init(array $extraParameters = array())
    {
        $this->user = $this->get('core.common')->getUser();
        $this->api = $this->get('core.common')->C('sms_api');
        $this->resourceOwner = $this->get('oauth.security.oauth_utils')->getResourceOwner('smsapis');

        $this->appId = $this->resourceOwner->getOption('client_id');
        $this->appSecret = $this->resourceOwner->getOption('client_secret');
    }

    /**
     * 短信接口是否关闭
     * @return  bool    ---     0-开启,1关闭
     **/
    public function isClosed($tplid=0)
    {
        return empty($this->api); //'(close)'
    }

    /**
     * 余额查询
     * 结果说明：array(1,1234.5): 成功,余额为1234.5；array(-1,'失败原因'): 
     * @return  array   ---  结果数组    如：array(1,1234.5)
     **/
    public function balance()
    {
        return $this->get('core.'.$this->api)->getBalance(); 
    }

    /**
     * 短信发送
     * @param   string  $mobiles    手机号码,array/string(英文分号分开)
     * @param   string  $content    255个字符以内
     * @return  array   ---     结果数组,如：array(1,'操作成功'): 
     **/
    public function send(array $option, $body=null)
    {
        if (self::isClosed())
            throw new \LogicException("短信接口已被关闭");
            
        if(!isset($option['name'])||empty($option['name']))
            throw new \LogicException("手机号码[name]不能为空");

        if (!self::checkTplIsOpen(isset($option['tpl'])?$option['tpl']:'', 'tid'))
            throw new \LogicException("手机模版被禁用");

        // checked=1时说明这个验证码已经被使用过，所以强制设置为0
        if(isset($option['checked'])&&$option['checked']!=0)
            $option['checked'] = 0;

        $option['type'] = $this->msgType;

        $mconfig = $this->get('db.mconfig')->getData(array('ename'=>'mwebset'));

        $option['product'] = isset($mconfig['hostname']['value'])?$mconfig['hostname']['value']:'';

        switch($this->api)
        {
            case 'apialiyun':                
                //目前只将验证码(code)发送至阿里云，因为比如这种有 “_” 下划线的，长度大于20的都不能通过，不知道还有没有其他限制，现在就只把验证码 code 发过去
//                $this->res = $this->get('core.'.$this->api)->sendSMS($option['name'],$this->msg, $option['tpl'], array('code'=>isset($option['code'])?$option['code']:''));

                $postData = array('phone' => $option['name'], 'type' => 1, 'code' => $option['code']);
                $postdata = http_build_query($postData);
                $filed = array(
                    'http' => array(
                        'method' => 'POST',
                        'header' => 'Content-type:application/x-www-form-urlencoded',
                        'content' => $postdata,
                        'timeout' => 60 // 超时时间（单位:s）
                    )
                );
                $url = "http://api.zhugefang.com/API/Sendmail/getdynamicpassword";
                $context = stream_context_create($filed);
                $result = json_decode(file_get_contents($url, false, $context), true);
                if ($result['data']['code']) {
                    $this->res = array('发送成功','OK');
                }else{
                    throw new \LogicException('验证码发送失败！');
                }

                break;
            default:
                //处理收件人格式
                self::checkAll($option['name'], $option['title']);

				//处理模版消息
				self::setTpl($option, $body);
                
                $this->res = $this->get('core.'.$this->api)->sendSMS($this->tel,$this->msg);
                break;
        }

        //写入日志文件(msglog模型)
        self::wirteHistory($option);
        return $this->res;
    }
    
    /**
     * 检测模版是否开启
     */
    public function checkTplIsOpen($str, $flag=null)
    {
        $smstpls = $this->get('db.smstpls')->getData($str, $flag);

        if (count($smstpls)<1)
            throw new \LogicException("手机模版 [$str] 不存在");
        
        if (!isset($smstpls['checked']) || $smstpls['checked']!=1)
            return false;
        return true;
    }

    /**
     * 检查汇总
     **/
    public function checkAll($mobiles, $content='')
    {
        $this->setUser('check');
        $this->checkPrefix();

        $this->tela = $this->fmtTel($mobiles);
        $this->msga = $this->fmtMsg($content);

        $this->checkSendMax();
        $this->checkIpMax();
        $this->tel = implode(';',$this->tela);
        $this->msg = $this->msga[0];
    }

    // getVals-模板的值
    public function getVals()
    {
        $row = array();
        $svid = $this->get('request')->get('svid');
        $id = $this->get('request')->get('id');
        if($id=='smscode'){
            $row['code'] = rand(100000,999999);
        }else{
            $whr = array(
                'id'=>$id,
                'findType'=>1,
            ); 
            $row = $this->get($svid)->findOneBy($whr);
        } 
        return $row;
    }
    
    /**
     * 生成随机验证码
     */
    public function getCode()
    {
        return rand(100000,999999);
    }

    /**
     * 检查认证码是否正确
     * @param int $tel
     * @param int $code
     * @param string $type
     * @param number $timeout
     * @return boolean
     */
    public function checkSmscode($tel, $code, $timeout=600)
    {
        if(empty($tel) || empty($code))
            return false;

        $map = array();
        $map['name'] = trim($tel);
        $map['type'] = 'mobile';
        $map['create_time']['gt'] = time() - $timeout;
        
        //查询出最新的那条数据
        $row = $this->get('db.msglog')->findOneBy($map ,array('id'=>'DESC'), false);

        // checked 为1表示此验证码被使用过了
        if(!is_object($row) || $row->getCode()!=trim($code) || $row->getChecked()==1)
            return false;

        // 校验成功，将 checked 置为1
        $row->setChecked(1);
        $this->get('db.msglog')->update($row->getId(), array(), $row, false);

        return true;
    }

    /**
     * 单个号码,一天内最多能接收短信的次数限制检测(错误信息:x08-4x开头)
     **/
    public function checkSendMax()
    {
        $tels = implode(',',$this->tela);
        
        $map = array();
        $map['type'] = $this->msgType;
        $map['create_time'] = array('gt'=>(time()-86400));

        if(strlen($tels)>36)
        {
            $tels = substr($tels,0,24);
            $map['name'] = array('like'=>"$tels%");
        }else{
            $map['name'] = array('eq'=>"$tels");
        }

        $info = $this->get('db.msglog')->findBy($map);

        if(!isset($info['data'])||empty($info['data']))
            return false;

        $cfgmax = $this->get('core.common')->getParameter('sms_lmtday');
        $cfgmax = intval($cfgmax); if($cfgmax<1) $cfgmax = 10; //dump($infos['data']);

        if(count($info['data'])>$cfgmax)
            self::getError('x08-42','24小时内最大发送信息次数不能超过:'.$cfgmax.'次');
    }

    /**
     * 单个IP两次发送信息的最短时间间隔，0为不限制，请根据短信运营商设置。(错误信息:x08-4x开头)
     **/
    public function checkIpMax()
    {
        $cfgip = $this->get('core.common')->getParameter('sms_lmtip');

        if(empty($cfgip))
            return;

        $map = array();
        $map['type'] = $this->msgType;
        $map['uip'] = $_SERVER['REMOTE_ADDR'];
        $map['create_time'] = array('gt'=>(time()-$cfgip));

        $info = $this->get('db.msglog')->findBy($map);

        if(isset($info['data'])&&count($info['data'])>0)
            self::getError('x08-46','信息发送间隔太短:<'.$cfgip.'秒');
    }

    /**
     * 用户设置检查：检查余额等(错误信息:x08-7x开头)
     * @param   string  $uid   用户ID
     * @return  object  $this
     **/
    public function setUser()
    {
        $userInfo = is_object($this->user) ? $this->user->getUserinfo() : "";

        $uid = is_object($userInfo)?$userInfo->getId():0;

        if($this->noCheck==false)
        {
            if($uid<=0)
                self::getError('x08-72','游客禁止发送短信');

            $map = array();
            $map['uid'] = $uid;
            $map['name'] = 'balance';

            $uquote = $this->get('db.user_attr')->findOneBy($map);
            $uq = is_object($uquote)?(int)$uquote->getValue():0;

            if($uq<=0)
                self::getError('x08-72','短信余额不足');
        }

        return true;
    }

    /**
     * 模版设置替换：替换占位符(错误信息:x08-8x开头)
     * @param  string $tpl     支持模版，如：{$subject}{$name}标记
     * @param  array $source  替换源：array('subject'=>'hellow 08cms!','name'=>'peace',)
     * @return object $this
     **/
    public function setTpl($option, $body=null)
    {
        $smstpls = $this->get('db.smstpls')->getData($option['tpl'], 'tid');
        
        if(!isset($smstpls['tpl']) || empty($smstpls['tpl']))
            self::getError('x08-17','无效的模版');

        $option['tpl'] = $smstpls['tpl'];

        if (!preg_match('/.html.twig$/', $option['tpl']))
            $option['tpl'] = $option['tpl'].".html.twig";

        if (!preg_match('/^Mobile/', $option['tpl']))
            $option['tpl'] = "Mobile/".$option['tpl'];

        $this->msg = $body?$body:$this->get('templating')->render($option['tpl'], $option);

        return $this->msg;
    }

    // 发送前-基本检查(错误信息:x08-1x开头)
    public function checkPrefix()
    {    
        if($this->isClosed())
            self::getError('x08-12','接口关闭');

        $balance = self::balance();
        if($balance<=0)
            self::getError('x08-16','接口余额不足:'.$balance);
    }

    /**
     * 电话号码 格式化/过滤(错误信息:x08-2x开头)
     * @param   array   $tel    初始的电话号码array/string
     * @return  array   $re     格式化并过滤后的电话号码
     **/
    public function fmtTel($tel)
    {
        $arr = $tel;

        if(is_string($tel))
        {
            $tel = str_replace(array("-","("," ",')'),'',$tel);
            $tel = str_replace(array("\r\n","\r","\n",';'),',',$tel);
            $arr = explode(';',$tel);
        }

        $arr = empty($arr) ? array() : array_filter($arr);

        $re = array();
        
        /**
         * 手机/^1\d{4,10}$/; 95168合法号码/^[1-9]{1}\d{4,10}$/; 0769-12345678小灵通
         */
        for($i=0;$i<count($arr);$i++){
            if(preg_match('/^1[3|4|5|7|8]{1}\d{9}$/',$arr[$i]))
                $re[] = $arr[$i];
        }

        if(empty($re))
            self::getError('x08-24','电话号码格式错误');

        return $re; 
    }
    /**
     * 短信内容 添加签名,截取/计数(错误信息:x08-3x开头)
     * @param   string  $msg    初始的短信内容
     * @param   int     $slen   最多截取多少文字
     * @return  array   $re     返回array(文字,信息条数,文字个数)
     **/
    public function fmtMsg($msg,$slen=255)
    {
        // 添加签名
        if(!empty($this->msg))
            $msg = $this->msg;
        else
            $this->msg = $msg;

        if(empty($msg))
            self::getError('x08-32','短信内容为空');

        $mconfig = $this->get('db.mconfig')->getData(array('ename'=>'mwebset'));
        $siteName = isset($mconfig['hostname']['value'])?$mconfig['hostname']['value']:'';
        
        if(!strpos($msg,"{$siteName}】"))
            $msg .= "【{$siteName}】";

        //$mcharset=='utf-8' ? 3 : 2; //中文宽度
        $clen = 3;
        
        //最多取255个字
        $cmax = min(array($slen,255));
        
        //php函数原始长度
        $n = strlen($msg);
        
        //指针
        $p = 0;
        
        // 计数,英文算一个字符
        $cnt = 0;
        for($i=0; $i<$n; $i++)
        {
            //结尾
            if($p>=$n)
                break;
            
            //最大文字个数
            if($cnt>=$cmax)
                break;

            if(ord($msg[$p]) > 127)
                $p += $clen;
            else
                $p++;

            $cnt++;
        }

        $msg = substr($msg,0,$p);
        
        // >70字
        if($cnt>$this->cfg_mchar)
            /**
             * (70-3)个字算一条信息
             * (dxton.com开发文档) --- “短信长度”如何收费？
             * 70字符内1条收费，超70字符,按65字符/条，多条收费。(目前运营商行业标准）
             */
            $ncnt = ceil($cnt/($this->cfg_mchar-5));
        else
            $ncnt = 1;

        return array($msg,$ncnt,$cnt); 
    }

    // telConvert: en:加密, de:解密
    public function telConvert($type='en')
    {    
        $this->get('core.rsa')->reset('rsa512');
        $tel = $this->get('request')->get('tel');
        if($type=='en'){
            $stamp = time(); //dump("$stamp:$tel");
            $tel = $this->get('core.rsa')->encrypt("$stamp:$tel"); 
            //$de = $this->get('core.rsa')->decrypt($tel); dump($tel,$de);
        }else{
            $tel = $this->get('core.rsa')->decrypt($tel); 
            $tmp = explode(':',$tel);
            $tel = empty($tmp[1]) ? '' : $tmp[1];
            // 超时... ??? 
        }
        return $tel;
    }
    
    /**
     * 发送记录
     * @param array $data
     */
    private function wirteHistory(array $data)
    {
        unset($data['_form_id']);
        unset($data['csrf_token']);
        $data['content'] = json_encode($data);

        $data['uid'] = is_object($this->user)?$this->user->getId():0;
        $data['uip'] = $this->get('core.common')->getClientIp();

        return $this->get('db.msglog')->add($data, null, false);
    }

    /**
     * 错误检测
     * @param int $no
     * @param string $msg
     * @throws \LogicException
     */
    public function getError($no='x08-xx',$msg='')
    {
        throw new \LogicException("[$no]$msg");
    }
    
    public function setNoCheck($flag) 
    {
        $this->noCheck = $flag;
    }
}