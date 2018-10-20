<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年1月12日
*/
namespace ManageBundle\Services\DB;

use CoreBundle\Security\User\User;
use CoreBundle\Services\AbstractServiceManager;
use HouseBundle\Handler\ZhugeSso\UserModel;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Users extends AbstractServiceManager
{
    protected $table = 'Users';

    protected $memTypes,$memGroup;

    protected $mustCheck = true;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);

        //只读数据库
        $this->storagetype = 1;
    }

    // 用于导入 08cms-nv50核心的会员
    public function add_imp08cv50(array $data, $info=null, $isValid=true)
    {
        $user = parent::getUser();
        if(!method_exists($user, 'getMid')||$user->getMid()!=1)
            unset($data['mid']);

        $data['username'] = isset($data['username'])?trim($data['username']):"";
        $data['password'] = isset($data['password'])?trim($data['password']):"";

        $str = uniqid(mt_rand(),1);
        $data['status'] = 1;
        $data['locale'] = "zh_CN";
        $data['loginip'] = "127.0.0.1";
        $data['token'] = md5($str);
        $data['salt'] = ''; // (清空salt，使加密方式与旧系统一致)
        $data['login_flag'] = substr(sha1(sha1($str)), 0, 10);
        $data['userdb'] = isset($data['userdb'])&&$data['userdb']?$data['userdb']:$this->get('core.common')->getUserBundle();

        #$data['password'] = $this->get('security.encoder')->encodePassword($data['password'], $data['salt']);
        unset($data['repassword']);

        // 新增数据同步至 zhugesso
//        if($this->get('house.zhugesso.handler')->isSync()){
//            $zhugeSsoUserData = $data;
//            $this->get('house.zhugesso.handler')->addUser(new UserModel($zhugeSsoUserData));
//        }


        return parent::add($data, $info, $isValid);
    }

    /**
     * 注册会员 $info
     */
    public function add(array $data,  $entity = null, $isValid=true)
    {
        //判断是否关闭注册
        $mconfig = $this->get('db.mconfig')->findOneBy(array('name'=>'siteopenreg', 'area'=>0));

        if(is_object($mconfig)&&(int)$mconfig->getValue()==0)
        {
            $reason = $this->get('db.mconfig')->findOneBy(array('name'=>'regcloreason', 'area'=>0));

            throw new \InvalidArgumentException(is_object($reason)?$reason->getValue():$mconfig->getTitle());
        }

        $data1 = $this->get('request')->request->all();

        if($isValid)
            $data = array_merge($data, $data1);

        $user = parent::getUser();

        if(!method_exists($user, 'getMid')||$user->getMid()!=1)
            unset($data['mid']);

        $data['usertplid'] = isset($data['usertplid'])?$data['usertplid']:'MemberBundle';

        $userType = isset($data['usertype'])?$data['usertype']:11;

        if(!is_object($entity)) {
            $entity = parent::dataToEntity(array(), null);
        }

        $str = uniqid(mt_rand(),1);

        $entity->setStatus(1);

        $entity->setLocale("zh_CN");

        $entity->setLoginip("127.0.0.1");

        $entity->setToken(md5($str));

        $data['userdb'] = isset($data['userdb'])&&$data['userdb']?$data['userdb']:$this->get('core.common')->getUserBundle();

        $username = isset($data['username'])?trim($data['username']):'';

        $data['username'] = self::createUserName($username);

        if(empty($data['username']))
            throw new \InvalidArgumentException('用户名不能为空');


        //用户保留关键字检测
        if($this->get('core.common')->checkUsernameCensor($data['username']))
            throw new \InvalidArgumentException(sprintf('用户名要 %s 为系统保留字，禁止使用', $data['username']));

        if($isValid) {
            //检测用户名是否被占用
            self::checkUserName($data['username']);

            //如果是手机或邮箱，定义随机密码
//             if(filter_var($data['username'], FILTER_VALIDATE_EMAIL)||$this->get('core.common')->isMobile($data['username']))
//                 $data['repassword'] = $data['password'] = substr(md5($str),0,8);

            $data['password'] = isset($data['password'])?$data['password']:substr(md5($str),0,8);
            $data['repassword'] = isset($data['repassword'])?$data['repassword']:$data['password'];

            //判断是否手机
           if($this->get('core.common')->isMobile($username))
            {
                $entity->setTel($username);

                if ($this->mustCheck && isset($data['codeNew']) && $data['codeNew'] == '')
                    throw new \InvalidArgumentException('短信验证码不能为空');
            } elseif(filter_var($username, FILTER_VALIDATE_EMAIL)) {  //判断是否是邮箱
                $entity->setEmail($username);

                if ($this->mustCheck && isset($data['mailcode']) && $data['mailcode'] == '')
                    throw new \InvalidArgumentException('邮箱验证码不能为空');
            } elseif ($this->mustCheck && isset($data['codeText']) && $data['codeText'] == '')
                throw new \InvalidArgumentException('验证码不能为空');
        }

        //if(!$isValid)
        //    $entity = parent::dataToEntity($data, $entity);

        // 新增数据同步至 zhugesso
//        if($this->get('house.zhugesso.handler')->isSync()){
//            $zhugeSsoUserData = $data;
//            $this->get('house.zhugesso.handler')->addUser(new UserModel($zhugeSsoUserData));
//        }

        $user = parent::add($data, $entity, $isValid);

        //会员分类
        self::handleGroup($user,$userType);

        //会员认证
        self::handleUserCert($user);

        //用户信息数据
        $userinfo = array();
        $userinfo['uid'] = $user->getId();
        $userinfo['nicename'] = isset($data['nicename'])?$data['nicename']:'';
        $userinfo['fxpid'] = isset($data['fxpid'])&&(int)$data['fxpid']>0?(int)$data['fxpid']:0;

        $userinfo = self::handleUserInfo($userinfo);

        //将默认积分写入用户属性表
        self::handleAttr($userinfo);

        return $user;
    }

    /**
     * 设置是否判断验证码
     * @param unknown $flag
     */
    public function setMustCheck($flag)
    {
        $this->mustCheck = $flag;
    }

    /**
     * 更新数据
     */
    public function update($id, array $data, $info=null, $isValid=true)
    {
        $zhugeSsoUserData = $data;
        if(isset($data['password'])&&$data['password'])
        {
            $zhugeSsoUserData['originPassword'] = $data['password'];
            $str = uniqid(mt_rand(),1);
            $data['salt'] = substr(sha1($str), 0, 10);
            $data['login_flag'] = substr(sha1(sha1($str)), 0, 10);
            $data['password'] = $this->get('security.encoder')->encodePassword($data['password'], $data['salt']);
        }

        // 同步修改用户信息
        if($this->get('house.zhugesso.handler')->isSync()){
            $this->get('house.zhugesso.handler')->updateUser(new UserModel($zhugeSsoUserData));
        }

        return parent::update($id, $data, $info, $isValid);
    }

    /**
     * 重置密码
     * @param string $information
     * @throws \InvalidArgumentException
     */
    public function resetpwd($information)
    {
        if(!empty($information)){
            $password = $this->get('core.common')->makeOrderId(1);

            if($this->get('core.common')->isMobile($information)){
                $this->get('request')->request->set('password', $password);
                $user = parent::findOneBy(array('tel' => $information));

                if(!is_object($user))
                    throw new \InvalidArgumentException('此号码未注册！');

            }elseif(filter_var($information, FILTER_VALIDATE_EMAIL)){
                $this->get('request')->request->set('password', $password);
                $user = parent::findOneBy(array('email' => $information));

                if(!is_object($user))
                    throw new \InvalidArgumentException('此邮箱未注册！');

                // $message = \Swift_Message::newInstance()
                // ->setSubject('网站重置密码【'.$this->container->getParameter('site_name').'】')
                // ->setFrom($this->get('core.common')->C('mailer_from'))
                // ->setTo($information)
                // ->setDate(time())
                // ->setBody(
                //     $this->get('templating')->render(
                //         'ManageBundle:Emails:baseemail.html.twig',
                //         array('password' => $password)
                //     ),
                //     'text/html',
                //     'utf-8'
                // );
                // $info = $this->get('mailer')->send($message);
            }elseif(is_numeric($information)){
                $user = parent::findOneBy(array('id' => $information));
            }else
                throw new \InvalidArgumentException('输入正确的信息（邮箱、手机）！');

            if(is_object($user))
                $this->setpwd($user, $password);

            return $password;
        }else{
            throw new \InvalidArgumentException('什么都没有输入！');
        }
    }

    private function setpwd($user, $password)
    {
        $str = uniqid(mt_rand(),1);
        $data = array();
        //$data['token'] = md5($str);
        //$data['salt'] = substr(sha1($str), 0, 10);
        //$data['login_flag'] = substr(sha1(sha1($str)), 0, 10);
        $user->setSalt(substr(sha1($str), 0, 10));
        $user->setLoginFlag(substr(sha1($str), 0, 10));
        $user->setPassword($this->get('security.encoder')->encodePassword($password, $user->getSalt()));

        // 同步修改用户信息
        if($this->get('house.zhugesso.handler')->isSync()){
            $this->get('house.zhugesso.handler')->updateUser(new UserModel([
                'username'=>$user->getUsername(),
                'originPassword'=>$password
            ]));
        }

        return parent::update($user->getId(), $data, $user, false);
    }

    public function saveWxUser(array $wxuser)
    {
        $data = array();
        $result = $this->get('weixin.wxfans')->findOneBy(array('openid' => $wxuser['openid']));

        $data['username'] = $this->get('core.common')->makeOrderId(1);
        $data['truepwd'] = '88888888';

        if($result && method_exists($result, 'getUid')){

            $uid = $result->getUid();
            $str = uniqid(mt_rand(),1);
            $data['salt'] = substr(sha1($str), 0, 10);
            $data['password'] = $this->get('security.encoder')->encodePassword($data['truepwd'], $data['salt']);

            // 同步修改用户信息
            if($this->get('house.zhugesso.handler')->isSync()){
                $this->get('house.zhugesso.handler')->updateUser(new UserModel([
                    'username'=>$data['username'],
                    'originPassword'=>$data['truepwd']
                ]));
            }



            parent::update($uid, $data);
            $data['reset'] = '重置账户';
        }else{

            if(isset($wxuser['sex']))
                $wxuser['sex'] = $wxuser['sex'] == 1 ? '男' : '女';
            if(isset($wxuser['tagid_list']))
                $wxuser['tagid_list'] = serialize($wxuser['tagid_list']);
            $this->setMustCheck(false);// 强制不验证验证码
            $data['password'] = $data['repassword'] =$data['truepwd'];
//             $data['usertype'] = 2;
            $user = $this->add($data);
            $data['reset'] = '注册';
            if($user){
                $wxuser['uid'] = $user->getId();
                $this->get('weixin.wxfans')->add($wxuser);
            }else{
                return false;
            }
        }

        return $data;
    }

    /**
     * 修改密码
     */
    public function modPasswd()
    {
        $user = $this->getUser();

        if(!is_object($user))
            throw new \LogicException('帐号已被登出,请重新登陆后再操作!!');

        $user = parent::findOneBy(array('id'=>$user->getId()), array(), false);

        $pwdOld = $this->get('request')->get('opassword','');
        $pwdNew = $this->get('request')->get('password','');
        $pwdNew1 = $this->get('request')->get('repassword','');

        if(empty($pwdNew))
            throw new \LogicException('新密码不能为空');

        if($pwdNew!==$pwdNew1)
            throw new \LogicException('两次密码不一致!!');

        if(strlen($pwdNew)<6||strlen($pwdNew)>18)
            throw new \LogicException('密码请输6到18个字符');

        //checked=0时为第一次修改密码，不作原密码校验，主要用于手机注册的用户
        if ($user->getChecked() != 0)
        {
            if(empty($pwdOld))
                throw new \LogicException('原密码不能为空!!');

            //检测旧密码
            if(!$this->get('security.encoder')->isPasswordValid($user->getPassword(), $pwdOld, $user->getSalt()))
                throw new \LogicException('原密码错误');
        } else
            $user->setChecked(1);

        $str = uniqid(mt_rand(),1);

        $data = array();
        //$data['salt'] = substr(sha1($str), 0, 10);
        //$data['login_flag'] = substr(sha1(sha1($str)), 0, 10);
        //$data['password'] = $this->get('security.encoder')->encodePassword($pwdNew, $data['salt']);
        //$data['modiydate'] = time();
        //return parent::update($user->getId(),$data, null, false);
        $user->setSalt(substr(sha1($str), 0, 10));
        $user->setLoginFlag(substr(sha1($str), 0, 10));
        $user->setPassword($this->get('security.encoder')->encodePassword($pwdNew, $user->getSalt()));

        // 同步修改用户信息
        if($this->get('house.zhugesso.handler')->isSync()){
            $this->get('house.zhugesso.handler')->updateUser(new UserModel([
                'username'=>$user->getUsername(),
                'originPassword'=>$pwdNew
            ]));
        }


        return parent::update($user->getId(), $data, $user, false);
    }

    public function addmanage($data)
    {
        $user = $this->getUser();

        if(!is_object($user))
            throw new \LogicException('帐号已被登出,请重新登陆后再操作!!');

        if(!method_exists($user, 'getMid')||$user->getMid()!=1)
            throw new \LogicException('你的权限不足!!');
    }

    /**
     * 通过邮箱或者手机号码找回密码
     * @param unknown $information 手机号码或者邮箱
     * @param unknown $password    新密码
     * @param unknown $code        验证码
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function retrievepwd($information, $password, $code)
    {
        if(strlen($password)<6||strlen($password)>18)
            throw new \LogicException('密码请输6到18个字符');

        $map = array();
        if($this->get('core.common')->isMobile($information))
        {
            $map['tel'] = $information;
            // 校验短信验证码
            $result = $this->get('core.sms')->checkSmscode($information, $code);

        } elseif (filter_var($information, FILTER_VALIDATE_EMAIL)) {

            $map['email'] = $information;
            // 校验邮箱验证码
            $result = $this->get('core.mail')->checkCode($information, $code);

        } else
            throw new \InvalidArgumentException('输入正确的信息（邮箱、手机）！');

        if (!$result)
            throw new \LogicException('验证码错误');

        $user = parent::findOneBy($map, array(), false);

        if(is_object($user))
            self::setpwd($user, $password);
        else
            throw new \InvalidArgumentException('此用户未注册！');
    }

    /**
     * 检查 $tel 是否已经注册过本网站
     * @param string $email
     */
    public function checkTelExist($tel = null)
    {
        if (empty($tel))
            return false;

        $result = parent::findOneBy(array('tel'=>$tel), array(), false);
        if (is_object($result))
            return true;
        return false;
    }

    /**
     * 检查 $email 是否已经注册过本网站
     * @param string $email
     */
    public function checkEmailExist($email = null)
    {
        if (empty($email))
            return false;

        $result = parent::findOneBy(array('email'=>$email), array(), false);
        if (is_object($result))
            return true;
        return false;
    }

    /**
     * 第三方绑定帐号密码
     */
    public function bindOauth($data)
    {
        $user = $this->get('core.common')->getUser();

        if(!$user->getResourceOwnerName()||!$user->getRawToken())
            throw new \InvalidArgumentException('禁止操作！');

        $map = array();
        $map['type'] = $user->getResourceOwnerName();
        $map['uid'] = $user->getId();
        $map['oauthid'] = $user->getOauthid();

        $userOauth = $this->get('db.user_oauth')->findOneBy($map);

        $userType = isset($data['usertype'])?(int)$data['usertype']:0;

        if(!is_object($userOauth)||$userOauth->getChecked())
            throw new \InvalidArgumentException('禁止操作！！');

        if(!isset($data['username'])||empty($data['username']))
            throw new \InvalidArgumentException('用户名不能为空！');

        if(!isset($data['password'])||empty($data['password']))
            throw new \InvalidArgumentException('密码不能为空！');

        //检测用户名是否被占用
        self::checkUserName($data['username']);

        //会员分类
        self::handleGroup($user, $userType);

        //用户信息数据
        $userinfo = $this->get('db.userinfo')->findOneBy(array('uid'=>$user->getId()));

        $userinfo = self::handleUserInfo(array(), $userinfo);

        //更新帐号密码
        self::update($user->getId(), $data, null, false);

        $userOauth->setChecked(1);

        $this->get('db.user_oauth')->update($userOauth->getId(), array(), $userOauth, false);

        $user = $this->get('db.users')->findOneBy(array('id'=>$user->getId()));

        //重新登陆
        $this->get('core.rbac')->loginHandle(new User($user), '08cmssharekey');
        return true;
    }

    protected function handleGroup($user, $userType)
    {
        //会员分类
        if($userType>0)
            $this->memTypes = $this->get('db.mem_types')->findOneBy(array('id'=>$userType));
        else
            $this->memTypes = $this->get('db.mem_types')->findOneBy(array('allow'=>1));

        if(!is_object($this->memTypes))
            throw new \InvalidArgumentException("会员类型不存在或已被删除!");

        //分员分组
        $this->memGroup = $this->get('db.mem_group')->findOneBy(array('aid'=>$this->memTypes->getId()), array('autoinit'=>'desc'));

        if(!is_object($this->memGroup))
            throw new \InvalidArgumentException("会员分组不存在或已被删除!");

        //审核流数据
        $workflow = array();

        switch((int)$this->memTypes->getIscheck())
        {
            //手动审核
            case 0:
                $workflow['type'] = 1;
                $workflow['uid'] = 1;
                $workflow['group_id'] = $this->memGroup->getId();

                //会员分类
                $this->memTypes = $this->get('db.mem_types')->findOneBy(array('allow'=>1,'ischeck'=>1), array(), false);

                if(!is_object($this->memTypes))
                    throw new \InvalidArgumentException("会员类型不存在或已被删除!");

                //分员分组
                $this->memGroup = $this->get('db.mem_group')->findOneBy(array('aid'=>$this->memTypes->getId()), array('autoinit'=>'desc'),  false);

                if(!is_object($this->memGroup))
                    throw new \InvalidArgumentException("会员分组不存在或已被删除!");
                break;
                //自动审核
            case 1:
                break;
        }


        if(is_object($user)&&$workflow)
        {
            $workflow['uid'] = $user->getId();

            //写入审核流表
            $this->get('db.workflow')->createOneIfNone($workflow);
        }
    }

    /**
     * 处理用户信息表
     * @param array $data
     * @param object $info
     */
    protected function handleUserInfo(array $data, $info=null)
    {
        $data['usertype'] = (int)$this->memTypes->getId();
        $data['groupid'] = (int)$this->memGroup->getId();

        if(!is_object($info)) {
            $info = $this->get('db.userinfo')->createOneIfNone(array('uid' => $data['uid']));
        }
        return $this->get('db.userinfo')->update($info->getId(),$data, null, false);

/*
        //写入用户信息表
        if(is_object($info))
        {
            return $this->get('db.userinfo')->update($info->getId(),$data, $info, false);
        }
        //??会在userinfo表中为同一个uid增加多个记录(uid,usertype、groupid、fxpid、nicename任何一个不同都会有一个不同的记录)//??
        return $this->get('db.userinfo')->createOneIfNone($data);
 * */

    }

    /**
     * 处理用户属性表
     * @param object $userinfo
     */
    public function handleAttr($userinfo)
    {
        $integral = $this->get('db.integral')->findBy(array('_multi'=>false));

        foreach ($integral['data'] as $v)
        {
            $addData = array();
            $addData['name'] = $v->getEname();
            $addData['value'] = (int)$v->getInitial();
            $addData['mid'] = $userinfo->getId();
            $addData['title'] = $v->getName();

            $this->get('db.user_attr')->add($addData, null, false);
        }
    }

    /**
     * 会员认证
     * @param unknown $user
     */
    protected function handleUserCert($user)
    {
        $data = array();
        $data['uid'] = $user->getId();
        $data['checked'] = 1;
        $data['groupid'] = (int)$this->memGroup->getId();

        if ($user->getTel())
        {
            $data['value'] = $user->getTel();
            $data['userauth'] = 1;
            $data['type'] = 0;
        } elseif ($user->getEmail()) {
            $data['value'] = $user->getEmail();
            $data['userauth'] = 2;
            $data['type'] = 1;
        }

        if (isset($data['value']))
        {
            $this->get('house.user_cert')->add($data, null, false);
        }
    }

    /**
     * 检测用户名
     * @param string $username
     */
    public function checkUserName($username)
    {
        if(empty($username))
            throw new \InvalidArgumentException("用户名不能为空!");

        $map = array();
        $map['username']['orX'][]['username'] = $username;
        $map['username']['orX'][]['tel'] = $username;
        $map['username']['orX'][]['email'] = $username;

        //检测用户名是否已存在
        $count = parent::count($map);

        if($count>0&&filter_var($username, FILTER_VALIDATE_EMAIL))
            throw new \InvalidArgumentException('邮箱 '.$username.' 已被占用!');

        if($count>0&&$this->get('core.common')->isMobile($username))
            throw new \InvalidArgumentException('手机号 '.$username.' 已被占用!');

        if($count>0)
            throw new \InvalidArgumentException('用户名'.$username.'已存在');
    }

    /**
     * 创建用户名
     * @param string $username
     */
    public function createUserName($username)
    {
        // 当用户名为手机号或者为邮箱的时候，重新生成一个用户名
        if (filter_var($username, FILTER_VALIDATE_EMAIL) || $this->get('core.common')->isMobile($username)) {
            while (true)
            {
                $str = uniqid(mt_rand(),1);
                $username = 'u'.substr(md5($str),0,9);
                if (!self::_handleCheck($username))
                    return $username;
            }
        } else
            return $username;
    }

    private function _handleCheck($username)
    {
        //检测用户名是否已存在
        $count = parent::count(array('username'=>$username));

        if ($count>0)
            return true;
        return false;
    }

    /**
     * 自动登陆
     * @param string $username
     */
    public function autoLogin($username)
    {
        if(empty($username))
            throw new \InvalidArgumentException("用户名不能为空!");

        $map = array();
        $map['username']['orX'][]['username'] = $username;
        $map['username']['orX'][]['tel'] = $username;
        $map['username']['orX'][]['email'] = $username;

        //检测用户名是否已存在
        $user = parent::findOneBy($map);

        //注册帐号并绑定
        $user = is_object($user)?$user:self::autoReg(array('username'=>$username));

        //登陆
        if(is_object($user))
            $this->get('core.rbac')->loginHandle(new User($user));

        return $user;
        //$userToken = $this->get('core.common')->getTokenAttr();
        //dump($userToken);die();
    }

    protected function autoReg($data)
    {
        $str = uniqid(mt_rand(),1);

        $data['password'] = isset($data['password'])?$data['password']:substr(md5($str),0,8);
        $data['repassword'] = isset($data['repassword'])?$data['repassword']:$data['password'];

        //判断是否手机
        if($this->get('core.common')->isMobile($data['username']))
            $data['tel'] = $data['username'];

        //判断是否是邮箱
        if(filter_var($data['username'], FILTER_VALIDATE_EMAIL))
            $data['email'] = $data['username'];

        //强制设置checked=0,此字段将用是判断第一次改密码是否需要原密码
        $data['checked'] = 0;

        return self::add($data, null, false);
    }

    public function delete($id, $info=null)
    {
        //删除认证
        $this->get('db.userCert')->dbalDelete(array('uid'=>$id));

        // 删除经纪人绑定，更新userinfo
        $result = $this->get('db.userinfo')->findBy(array('cid'=>$id));
        foreach ($result['data'] as $v)
        {
            $this->get('db.userinfo')->delete($v->getId());
        }
        $result = $this->get('db.userinfo')->findBy(array('uid'=>$id));
        foreach ($result['data'] as $v)
        {
            $this->get('db.userinfo')->delete($v->getId());
        }

        return parent::delete($id, $info);
    }
}