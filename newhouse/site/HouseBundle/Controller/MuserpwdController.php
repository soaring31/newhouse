<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年10月20日
*/
namespace HouseBundle\Controller;

/**
* 账号管理
* @author house
*/
class MuserpwdController extends Controller
{
    /**
    * 实现的save方法
    * house
    */
    public function saveAction()
    {
        if($this->get('request')->getMethod() == "POST")
        {
            $ids = $this->get('request')->get('id', '');
            $data = $this->get('request')->request->all();
            if($ids)
            {
                $ids = explode(',', $ids);
                foreach($ids as $id)
                {
                    $info = $this->get('db.users')->update($id, $data);
                }
            }else
                $info = $this->get('db.users')->add($data);

            return $this->success('操作成功', '', false, array('id' => $info->getId()));
        }

        return $this->error('操作失败');
    }
    /**
    * 实现的delete方法
    * house
    */
    public function deleteAction()
    {
        $ids = $this->get('request')->get('id', '');

        if($ids)
        {
            $ids = explode(',', $ids);
            foreach($ids as $id)
            {
                $this->get('db.users')->delete($id);
            }
        }
        return $this->success('操作成功');
    }

    /**
    * 修改密码
    * house
    */
    public function muserpwdmanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
    
    /**
     * 修改密码
     * house
     */
    public function passwordAction()
    {
        if($this->get('request')->getMethod() == "POST")
        {
            $this->get('db.users')->modPasswd();
            return $this->success('操作成功');
        }
    }
    
    /**
     * 邮箱或者手机找回密码
     */
    public function retrievepwdAction()
    {
        if($this->get('request')->getMethod() == "POST")
        {
            $info = $this->get('request')->get('tel',0);
            if (empty($info))
                $info = $this->get('request')->get('email',0);

            $pwd = $this->get('request')->get('password');
            $repwd = $this->get('request')->get('repassword');
            if ($pwd != $repwd)
                throw new \LogicException('两次密码不一致！');
            
            // 验证码
            $code = $this->get('request')->get('codeNew',0);

            $this->get('db.users')->retrievepwd($info, $pwd, $code);
            
            return $this->success('操作成功');
        }

        return $this->error('操作失败');
    }
}