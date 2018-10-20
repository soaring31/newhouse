<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年02月18日
*/
namespace ManageBundle\Controller;
        
/**
* 邮件管理
* @author admin
*/
class WebconfigmailController extends Controller
{
        
    /**
    * 实现的mail方法
    */
    public function mailAction()
    {
        $message = \Swift_Message::newInstance()
	    ->setSubject('测试邮件[08cms]')
	    ->setFrom($this->C('mailer_from'))
	    ->setTo($this->C('mailer_from'))
	    ->setDate(time())
	    ->setBody(
	        $this->renderView(
	            'Emails/testEmail.html.twig',
	            array('name' => 'test')
	        ),
	        'text/html'
	    );
	    $this->get('mailer')->send($message);
	    
	    return $this->success('发送成功');
    }
        
}