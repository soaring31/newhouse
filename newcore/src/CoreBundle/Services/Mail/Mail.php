<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年5月2日
*/
namespace CoreBundle\Services\Mail;

use CoreBundle\Services\ServiceBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Mail extends ServiceBase implements MailInterface
{
    protected $name = array();
    protected $cc = array();
    protected $mm = array();

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    public function send(array $option)
    {
        if (self::isClosed())
            throw new \LogicException("邮箱接口已被关闭");
        
        $option['title'] = isset($option['title'])?$option['title']:$this->get('request')->get("name","");
        $option['name'] = isset($option['name'])?$option['name']:$this->get('request')->get("mail","");
        $option['tpl'] = isset($option['tpl'])?$option['tpl']:$this->get('request')->get("tpl","testEmail.html.twig");

        if(!isset($option['name'])||empty($option['name']))
            throw new \LogicException("收件人[name]不能为空");

        if(!self::checkTplIsOpen($option['tpl']))
            throw new \LogicException("模版不存在或被禁用");
        
        $option['type'] = 'mail';

        $mconfig = $this->get('db.mconfig')->getData(array('ename'=>'mwebset'));
        $option['product'] = isset($mconfig['hostname']['value'])?$mconfig['hostname']['value']:'';

        
//         if($option['tpl']=='comcode')
//             $option['tpl'] = "testEmail.html.twig";
        
        if (!preg_match('/.html.twig$/', $option['tpl']))
            $option['tpl'] = $option['tpl'].".html.twig";
        
        if (!preg_match('/^Emails/', $option['tpl']))
            $option['tpl'] = "Emails/".$option['tpl'];

        //处理收件人格式
        self::handleName($option['name'], 'name');

        //处理抄送人格式
        if(isset($option['cc'])&&$option['cc'])
            self::handleName($option['cc'], 'cc');

        //处理秘送人格式
        if(isset($option['mm'])&&$option['mm'])
            self::handleName($option['mm'], 'mm');

        $user = $this->get('core.common')->getUser();
        
        $option['uid'] = is_object($user)?$user->getId():0;
        $option['uip'] = $this->get('core.common')->getClientIp();
        
        //生成6位验证码
        $option['code'] = isset($option['code'])?$option['code']:$this->get('core.common')->getRandStr(6);

        $mailerFrom = $this->get('core.common')->C('mailer_from');

        $mconfig = $this->get('db.mconfig')->getData(array('ename'=>'mwebset'));
        $siteName = isset($mconfig['hostname']['value'])?$mconfig['hostname']['value']:'';
        
        $message = \Swift_Message::newInstance();
        $message->setSubject($option['title']."[".$siteName."]");
        $message->setFrom($mailerFrom);
        $message->setTo($this->name);

        if($this->cc)
            $message->setCc($this->cc);
        if($this->mm)
            $message->setBcc($this->mm);

        $message->setDate(time());
        $message->setBody($this->get('templating')->render($option['tpl'], $option),'text/html');
        $this->get('mailer')->send($message);
        
        //写日志
        self::wirteHistory($option);
    }
    
    /**
     * 处理收件人
     * @param string $cc
     */
    private function handleName($name, $flag)
    {
        if(!empty($name))
        {
            $name = is_array($name)?$name:explode(';', $name);
            foreach($name as $val)
            {
                switch($flag)
                {
                    case 'cc':
                        $this->cc[$val] = "";
                        break;
                    case 'mm':
                        $this->mm[$val] = "";
                        break;
                    default:
                        $this->name[$val] = "";
                        break;
                }

                if(!filter_var($val, FILTER_VALIDATE_EMAIL))
                    throw new \LogicException($val."不是有效的邮箱格式");
            }
        }
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

        return $this->get('db.msglog')->add($data, null, false);
    }
    
    /**
     * 校验验证码
     * @param string $mail
     * @param string $code
     * @param int $timeout 
     */
    public function checkCode($mail, $code, $timeout = 600)
    {
        if(empty($mail) || empty($code))
            return false;

        $map = array();
        $map['name'] = trim($mail);
        $map['type'] = 'mail';
        $map['create_time']['gt'] = time() - $timeout;
        
        //查询出最新的那条数据
        $row = $this->get('db.msglog')->findOneBy($map ,array('id'=>'DESC'), false);

        // checked 为1表示此验证码被使用过了
        if(!is_object($row) || strtolower($row->getCode())!=strtolower(trim($code)) || $row->getChecked()==1)
            return false;

        // 校验成功，将 checked 置为1
        $row->setChecked(1);
        $this->get('db.msglog')->update($row->getId(), array(), $row, false);

        return true;
    }
    
    /**
     * 判断接口是否关闭
     */
    public function isClosed()
    {
        return $this->get('cc')->C('mailer_transport')?false:true;
    }
    
    /**
     * 判断模版是否关闭
     * @param unknown $tpl
     * @throws \LogicException
     */
    public function checkTplIsOpen($tpl)
    {
        $result = $this->get('db.emailtpls')->findOneBy(array('tid'=>$tpl));
        
        if (!is_object($result))
            throw new \LogicException("手机模版 [$tpl] 不存在");

        if (method_exists($result,'getChecked')&&$result->getChecked()==1)
            return true;
        return false;
    }
}