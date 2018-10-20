<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年02月09日
*/
namespace HouseBundle\Controller;

/**
* 邮箱验证
* @author house
*/
class MwebconfigmailController extends Controller
{



    /**
    * 邮箱验证
    * house
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