<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package Tripod
* create date 2015-04-20
*/
namespace CoreBundle\Security\Login;

use CoreBundle\Security\User\User;
use CoreBundle\Services\ServiceBase;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * 登陆管理.
 */
class LoginManager extends ServiceBase
{
	protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;                   
    }

    /**
     * 修改密码处理
     */
    public function modifyPassHandle($user)
    {
        $request     = $this->get('request');
        $oldPassWord = $request->get('oldPassWord');
        $newPassWord = $request->get('newPassWord');

        //检测旧密码
        if(!$this->get('security.encoder')->isPasswordValid($user->getPassword(), $oldPassWord, $user->getSalt()))
            throw new \LogicException('原密码错误');
        $user->setPassword($this->get('security.encoder')->encodePassword($newPassWord, $user->getSalt()));
        $this->get('core.user_manager')->updateUser($user);
        return true;
    }

    /**
     * 登陆处理
     * @param $firewallName
     * @return boolean
     */
    public function loginHandle($firewallName="08cmssharekey")
    {
    	$userName  = $this->get('request')->get('userName');
    	$passWord  = $this->get('request')->get('passWord');
    	$codeText  = $this->get('request')->get('codeText','');
        $loginflag = $this->get('request')->get('loginflag');
        $vcode = $this->get('request')->get('vcode','');
        
        if($vcode)
            return self::telLogin($userName,$vcode);
        
    	//$csrfToken	= $this->get('request')->get('csrf_token');
    	//$rememberMe = $request->get('rememberMe');

    	//判断Csrf是否正确
    	//if(!$this->isCsrfTokenValid('authenticate',$csrfToken))
    	//    throw new \LogicException('Csrf参数错误');

        if(!$this->get('core.common')->isMobileClient()&&!$vcode)
        {
        	//判断验证码
        	if($codeText)
        	{
            	if($loginflag)
                {
                    $this->get('core.captcha')->setSessionWord('codeText');
                    if($this->get('core.captcha')->check_word($codeText,false)!=1)
                        throw new \LogicException('验证码失效');
                }else{
                    if($this->get('core.captcha')->check_word($codeText)!=1)
                	    throw new \LogicException('验证码错误');
                }
        	}else{
        	    switch($this->get('request')->getSession()->get('gtserver'))
        	    {
        	        case 1:
        	            if (!$this->get('core.geetest')->success_validate($this->get('request')->get('geetest_challenge', ''), $this->get('request')->get('geetest_validate', ''), $this->get('request')->get('geetest_seccode', ''))) 
                            throw new \LogicException('验证码错误');
                            // throw new \LogicException('验证失败1');
        	            break;
        	        default:
        	            if (!$this->get('core.geetest')->fail_validate($this->get('request')->get('geetest_challenge', ''), $this->get('request')->get('geetest_validate', ''), $this->get('request')->get('geetest_seccode', '')))
                            throw new \LogicException('验证码错误');
                            // throw new \LogicException('验证失败2');
        	            break;
        	    }
        	}
        }

    	//根据用户名或邮箱获取用户信息
    	$userInfo = $this->get('core.user_manager')->findUserByUsernameOrEmail($userName);
    	//根据名称获取系统日志
    	$logList = $this->get('db.system_log')->findBy(array('name'=>'密码错误', 'operation'=>$userInfo->getUsername()), array('id'=>'DESC'));
    	$logNum = count($logList['data']);
    	
    	$mconfig = $this->get('db.mconfig')->getData(array('ename'=>'mvisit', 'area'=>0));

    	$loginError = isset($mconfig['loginerror']['value'])?(int)$mconfig['loginerror']['value']:0;
    	$lockTime = isset($mconfig['locktime']['value'])?(int)$mconfig['locktime']['value']:0;

    	//判断非创始人的密码错误次数
    	if($loginError>0 && $logNum>=$loginError && $userInfo->getMid()!=1)
    	{
    	    $endError = $logList['data'][0]->getCreateTime();
    	    $startError = $logList['data'][$loginError-1]->getCreateTime();
    	    $erroTime = $endError - $startError;
    	    
    	    //判断是否在指定时间
    	    if($erroTime<=60)
    	    {
    	        $unlockTime = $endError + $lockTime * 60;
    	        $nowTime = time();
    	        
    	        //判断是否在锁定时间
    	        if($nowTime<=$unlockTime)
    	        {
    	            $warnTime = ceil(($unlockTime - $nowTime) / 60);
    	            
    	            throw new \LogicException('密码错误次数过多，请'.$warnTime.'分钟后重试！');
    	        } 
    	    }
    	}
    	
    	//检测密码
    	if(!$this->get('security.encoder')->isPasswordValid($userInfo->getPassword(), $passWord, $userInfo->getSalt()))
    	{
    	    //更新数据
    	    $addData = array();
    	    $addData['type'] = 'log';
    	    $addData['name'] = '密码错误';
    	    $addData['title'] = $this->get('core.common')->getClientIp();
    	    $addData['operation'] = $userInfo->getUsername();
    	    
    	    $this->get('db.system_log')->add($addData, null, false);
    	        
    	    throw new \LogicException('密码错误');
    	}

    	if((bool)$userInfo->getStatus()==false)
    	    throw new \LogicException('帐号已被禁用');

    	//用户登陆
    	return $this->get('core.rbac')->loginHandle(new User($userInfo), $firewallName);
    }
    
    /**
     * 验证码登陆处理
     * @param string $userName
     * @param string $vcode
     */
    private function telLogin($userName, $vcode)
    {
        //判断验证码
        if(!$this->get('core.sms')->checkSmscode($userName, $vcode))
            throw new \LogicException(sprintf('动态码错误或已失效'));
        
        //自动注册并登陆
        return $this->get('db.users')->autoLogin($userName);
    }
    
    /**
     * 其它登陆处理
     * @param string $firewallName
     * @param obj $userInfo
     * @param string $user
     * @throws \LogicException
     */
    public function otherLoginHandle($firewallName="08cmssharekey", $userInfo, $user='CoreBundle\Security\User\User')
    {
        $userName  = $this->get('request')->get('userName');
        $passWord  = $this->get('request')->get('passWord');
        $codeText  = $this->get('request')->get('codeText');
        //$csrfToken = $this->get('request')->get('csrf_token');
        //$rememberMe = $request->get('rememberMe');
    
        //判断Csrf是否正确
        //if(!$this->isCsrfTokenValid('authenticate',$csrfToken))
        //    throw new \LogicException('Csrf参数错误');
    
        //判断验证码
        if($this->get('core.captcha')->check_word($codeText)!=1)
            throw new \LogicException('验证码错误');
    
        if(empty($userInfo))
            throw new \LogicException(sprintf("用户名 %s 不存在", $userName));
    
        //检测密码
        if(!$this->get('security.encoder')->isPasswordValid($userInfo->getPassword(), $passWord, $userInfo->getSalt()))
            throw new \LogicException('密码错误');
    
        if((bool)$userInfo->getStatus()==false)
             throw new \LogicException('帐号已被禁用');
    
        $userInfo = new $user($userInfo);
    
        //用户登陆
        return $this->get('core.rbac')->loginHandle($userInfo, $firewallName);
    }

	/**
	 * Checks the validity of a CSRF token
	 *
	 * @param string $id    The id used when generating the token
	 * @param string $token The actual token sent with the request that should be validated
	 *
	 * @return bool
	 */
	protected function isCsrfTokenValid($id, $token)
	{
		return $this->get('security.csrf.token_manager')->isTokenValid(new CsrfToken($id, $token));
	}
}
