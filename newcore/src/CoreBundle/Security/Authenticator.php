<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年3月7日
*/
namespace CoreBundle\Security;

use CoreBundle\Security\User\User;
use CoreBundle\Services\ServiceBase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authentication\SimpleFormAuthenticatorInterface;

/**
 * 暂未使用??
 */
class Authenticator extends ServiceBase implements SimpleFormAuthenticatorInterface 
{
    protected $container;
    
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        if(!$this->get('core.common')->isMobileClient())
        {
            $codeText  = $this->get('request')->get('codeText','');
            $loginflag = $this->get('request')->get('loginflag','');

            //判断验证码
            if($codeText)
            {
                if($loginflag)
                {
                    //让验证码失效
                    $this->get('core.captcha')->setSessionWord('codeText');
                    if($this->get('core.captcha')->check_word($codeText,false)!=1)
                        throw new \LogicException('验证码失效');
                }elseif($this->get('core.captcha')->check_word($codeText)!=1)
                    throw new \LogicException('验证码错误');
            }else{
                switch($this->get('request')->getSession()->get('gtserver'))
                {
                    case 1:
                        if (!$this->get('core.geetest')->success_validate($this->get('request')->get('geetest_challenge', ''), $this->get('request')->get('geetest_validate', ''), $this->get('request')->get('geetest_seccode', '')))
                            throw new \LogicException('验证失败');
                        break;
                    default:
                        if (!$this->get('core.geetest')->fail_validate($this->get('request')->get('geetest_challenge', ''), $this->get('request')->get('geetest_validate', ''), $this->get('request')->get('geetest_seccode', '')))
                            throw new \LogicException('验证失败');
                        break;
                }
            }
        }

        try {
            $user = $userProvider->loadUser($token->getUsername());
            
            if(!is_object(($user)))
                throw new \Exception('无效的用户名');

            //根据名称获取系统日志
            $map = array();
            $map['name'] = '密码错误';
            $map['operation'] = $user->getUsername();

            $orderBy = array();
            $orderBy['id'] = 'desc';

            $logList = $this->get('db.system_log')->findBy($map, $orderBy, 20);
            
            $logNum = count($logList['data']);
             
            $mconfig = $this->get('db.mconfig')->findOneBy(array('name'=>'loginerror'));
            
            $loginError = is_object($mconfig)?$mconfig->getValue():0;
            
            //判断非创始人的密码错误次数
            if($logNum>=$loginError&&$user->getMid()!=1)
            {
                $endError = $logList['data'][0]->getCreateTime();
                $startError = $logList['data'][$loginError-1]->getCreateTime();
                $erroTime = $endError - $startError;
                	
                //判断是否在指定时间
                if($erroTime<=60)
                {
                    $lockTime = $this->get('db.mconfig')->findOneBy(array('name'=>'locktime'))->getValue();
                    $unlockTime = $endError + $lockTime * 60;
                    $nowTime = time();
                     
                    //判断是否在锁定时间
                    if($nowTime<=$unlockTime)                        
                        throw new \LogicException('密码错误次数过多，请'.ceil(($unlockTime - $nowTime) / 60).'分钟后重试！');
                }
            }            
        } catch (UsernameNotFoundException $e) {

            throw new \Exception('无效的用户名');
        }
        
        //检测密码
        if(!$this->get('security.encoder')->isPasswordValid($user->getPassword(), $token->getCredentials(), $user->getSalt()))
        {
            //更新数据
            $addData = array();
            $addData['type'] = 'log';
            $addData['name'] = '密码错误';
            $addData['title'] = $this->get('core.common')->getClientIp();
            $addData['operation'] = $user->getUsername();
            	
            $this->get('db.system_log')->add($addData, null, false);
             
            throw new \LogicException('密码错误');
        }
        
        if((bool)$user->getStatus()==false)
            throw new \LogicException('帐号已被禁用');
        
        //用户登陆
        return $this->get('core.rbac')->loginHandle(new User($user), $providerKey);
    }
    
    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof UsernamePasswordToken
        && $token->getProviderKey() === $providerKey;
    }
    
    public function createToken(Request $request, $username, $password, $providerKey)
    {
        return new UsernamePasswordToken($username, $password, $providerKey);
    }
}
