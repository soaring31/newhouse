<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年03月15日
*/
namespace ManageBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
* 邮件发送记录
* 
*/
class Emslogs extends AbstractServiceManager
{
    protected $table = 'Emslogs';
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
    }

    public function createEmailSms($mail=null, $tpl=null,$cc=null,$mm=null)
    {
        $name = $mail?$mail:$this->get('request')->get("name","");
        $mail = $mail?$mail:$this->get('request')->get("mail","");
        $cc = $cc?$cc:$this->get('request')->get("cc","");
        $mm = $mm?$mm:$this->get('request')->get("mm","");
    
        $mail = !is_array($mail)&&$mail?explode(',',$mail):$mail;
        $cc = !is_array($cc)&&$cc?explode(',',$cc):$cc;
        $mm = !is_array($mm)&&$mm?explode(',',$mm):$mm;
    
        $mailArr = array();
    
        if(is_array($mail))
        {
            foreach($mail as $val)
            {
                $mailArr[$val] = "";
                if(!filter_var($val, FILTER_VALIDATE_EMAIL))
                    throw new \LogicException("不是有效的邮箱格式");
            }
        }
    
        $ccArr = array();
    
        if(is_array($cc))
        {
            foreach($cc as $val)
            {
                $ccArr[$val] = "";
                if(!filter_var($val, FILTER_VALIDATE_EMAIL))
                    throw new \LogicException("不是有效的邮箱格式");
            }
        }
    
        $mmArr = array();
    
        if(is_array($mm))
        {
            foreach($mm as $val)
            {
                $mmArr[$val] = "";
                if(!filter_var($val, FILTER_VALIDATE_EMAIL))
                    throw new \LogicException("不是有效的邮箱格式");
            }
        }

        $content = $this->get('request')->get("content","");

        $tpl = $tpl?$tpl:$this->get('request')->get("tpl","");

        if (!empty($tpl) && !(strstr($tpl,'Emails/') || strstr($tpl,'Emails\\')))
            $tpl = 'Emails/'.$tpl;
        if (!empty($tpl) && !strstr($tpl, '.html.twig'))
            $tpl .= '.html.twig';

        //消息内容
        $msgInfo = array('name'=>$content);
    
        //生成6位验证码
        $msgInfo['emscode'] = $this->get('core.common')->getRandStr(6);

        $template = $this->get('templating')->render($tpl?$tpl:'Emails/testEmail.html.twig', $msgInfo);
    
        $message = \Swift_Message::newInstance();
        $message->setSubject("{$name}[08cms]");
        $message->setFrom($this->get('core.common')->C('mailer_from'));
        $message->setTo($mailArr);
    
        if($ccArr)
            $message->setCc($ccArr);
        if($mmArr)
            $message->setBcc($mmArr);
        $message->setDate(time());
        $message->setBody($template,'text/html');
        $this->get('mailer')->send($message);

        self::endSave($msgInfo['emscode'], $content, $tpl);
    
        return true;
    }
    
    /**
     * 发送记录
     * @param unknown $emscode
     * @param unknown $content
     */
    public function endSave($emscode, $content, $tpl)
    {
        $data = array();
        $data['mail'] = $this->get('request')->get('mail','');
        $data['cc'] = $this->get('request')->get('cc','');
        $data['mm'] = $this->get('request')->get('mm','');
        $data['uip'] = $this->get('request')->getClientIp();
        $data['tpl'] = $tpl;
        $data['emscode'] = $emscode;
        $data['content'] = $content;

        parent::add($data, null, false);
    }
}