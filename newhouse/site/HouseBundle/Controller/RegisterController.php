<?php
/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2016年05月19日
 */

namespace HouseBundle\Controller;

use CoreBundle\Security\User\User;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 *
 * @author house
 */
class RegisterController extends Controller
{
    /**
     * 注册
     * admin
     */
    public function indexAction()
    {
        if ($this->get('house.zhugepassport.handler')->isSync()) {
            return new RedirectResponse('http://id.zhuge.com/Passport/Register/register');
        }

        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
     * 注册子页面
     */
    public function regformAction()
    {
        $url                      = $this->get('router')->generate('auto_register');
        $mchidx                   = $this->get('request')->get('mchidx', 1);
        $form                     = $this->getFormFieldAttr('reguser_' . $mchidx, array(), 'POST', null, $url);
        $this->parameters['form'] = $form->createView();
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
     * 微信注册
     * admin
     */
    public function wxregAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
     * 找回密码
     * house
     */
    public function resetpwdAction()
    {
        if ($this->get('request')->getMethod() == "POST") {
            $information = $this->get('request')->request->get('tel', '');
            $this->get('db.users')->resetpwd($information);

            return $this->success('重置成功');
        }

        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
     * 实现的save方法
     * house
     */
    public function saveAction()
    {
        if ($this->get('request')->getMethod() == "POST") {
            $data = $this->get('request')->request->all();
            $user = $this->get('db.users')->add($data);

            $this->get('core.rbac')->loginHandle(new User($user));
            return $this->success('注册成功');
        }

        return $this->error('操作失败');
    }
}