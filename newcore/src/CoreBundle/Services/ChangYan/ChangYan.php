<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年12月6日
*/
namespace CoreBundle\Services\ChangYan;

use CoreBundle\Services\ServiceBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

define('CHANGYAN_API_AES', 'http://changyan.api.dedecms.com/');
define('CHANGYAN_JQUERY', 'http://js.sohu.com/library/jquery-1.7.1.min.js');
define('CHANGYAN_API_SIGN', 'http://s.changyan.kuaizhan.com/admin/api/open/get-sign');
define('CHANGYAN_API_REG', 'http://s.changyan.kuaizhan.com/admin/api/open/reg');
define('CHANGYAN_API_AUTOREG', 'http://s.changyan.kuaizhan.com/admin/api/open/auto-reg');
define('CHANGYAN_API_LOGIN', 'http://s.changyan.kuaizhan.com/admin/api/open/validate');
define('CHANGYAN_API_SETCOOKIE', 'http://s.changyan.kuaizhan.com/admin/api/open/set-cookie');
define('CHANGYAN_API_ISNEW', 'http://s.changyan.kuaizhan.com/admin/api/recent-comment-topics');
define('CHANGYAN_API_LATESTS', 'http://s.changyan.kuaizhan.com/api/2/comment/latests');
define('CHANGYAN_API_RECENT', 'http://s.changyan.kuaizhan.com/admin/api/recent-comment-topics');
define('CHANGYAN_API_CODE', 'http://s.changyan.kuaizhan.com/admin/api/open/get-code');
define('CHANGYAN_API_ADDSITE', 'http://s.changyan.kuaizhan.com/admin/api/open/add-isv');
define('CHANGYAN_API_CHANGE_ISV', 'http://s.changyan.kuaizhan.com/change-isv/');
define('CHANGYAN_API_CHECK_LOGIN', 'http://s.changyan.kuaizhan.com/check-login');
define('CHANGYAN_API_GETAPPKEY', 'http://s.changyan.kuaizhan.com/ admin/api/open/get-appkey');
define('CHANGYAN_API_COMMENTS', 'http://s.changyan.sohu.com/api/2/topic/comments');
define('CHANGYAN_API_TOPICCOMMENTS', 'http://s.changyan.sohu.com/api/2/topic/load');
define('CHANGYAN_API_IMPORT', 'http://s.changyan.kuaizhan.com/admin/api/import/comment');
define('CHANGYAN_API_GETISVS', 'http://s.changyan.kuaizhan.com/admin/api/open/get-isvs');
define('CHANGYAN_API_GETISVS_JSONP', 'http://s.changyan.kuaizhan.com/admin/api/open/get-isvs-jsonp');
define('CHANGYAN_API_BINDACC', 'http://s.changyan.kuaizhan.com/admin/api/open/mod-opinfo');
define('CHANGYAN_API_FORGET_PWD', 'http://s.changyan.kuaizhan.com/platform/forget-pwd');
define('CHANGYAN_REG_URL','http://www.kuaizhan.com/passport/?refer=http://changyan.kuaizhan.com/audit/comments/TOAUDIT/1');
define('CHANGYAN_FORGET_PWD_URL','http://www.kuaizhan.com/passport/?refer=http://changyan.kuaizhan.com/audit/comments/TOAUDIT/1');
define('CHANGYAN_UPDATE_URL','http://changyan.sohu.com/download/dede/');

define('CHANGYAN_CLIENT_ID', 'cysIbJDJE');
define('CHANGYAN_CLIENT_KEY', 'prod_bf8c292e8110c14f2920a7dc9b0a89d2');

//define('CHANGYAN_JQUERY_SRC', '<script>window.jQuery || document.write(unescape(\'%3Cscript src="http://changyan.api.dedecms.com/assets/js/jquery.min.js"%3E%3C/script%3E\'))</script>');
define('CHANGYAN_JQUERY_SRC', '<script>window.jQuery || document.write(unescape(\'%3Cscript src="http://js.sohu.com/library/jquery-1.7.1.min.js"%3E%3C/script%3E\'))</script>');

define('CHANGYAN_VER', '0.0.17');

class ChangYan extends ServiceBase
{
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    public function init()
    {
        $user = $this->get('core.common')->getUser();
        
        if(!is_object($user))
            return true;

        $user = $user->getEmail();
        $pwd = 'AA123654';
        $isv_name = $user;
        $url = '';
        
        $sign = self::changyan_gen_sign($user);
        
        
        $paramsArr=array(
            'client_id'=>CHANGYAN_CLIENT_ID,
            'user'=>self::changyan_autoCharset($user),
            'password'=>$pwd,
            'isv_name'=>self::changyan_autoCharset($isv_name),
            'url'=>$url,
            'sign'=>$sign);
        $rs = self::changyan_http_send(CHANGYAN_API_REG,0,$paramsArr);

        $result=json_decode($rs,TRUE);
        
        $errorinfo = array();
        $errorinfo['appid not exist']='client_id不存在';
        $errorinfo['sign error']='签名验证失败';
        $errorinfo['user name exist']='注册用户已经存在';

        if($result['status']==0)
        {
            // 保存appid,id信息
            self::changyan_set_setting('user', $user);
            self::changyan_set_setting('appid', $result['appid']);
            self::changyan_set_setting('id', $result['id']);
            self::changyan_set_setting('isv_id', $result['isv_id']);
            self::changyan_clearcache();
            throw new \LogicException("您已经成功注册，现在进行登录！",'?');
        }

        throw new \LogicException("无法正常注册，错误信息：".$errorinfo[$result['msg']]);
    }
    
    public function autoreg()
    {
        $cfg_webname = "08CMS";
        $step = 1;
        $step = empty($step)? 0 : $step;
        $db_user = $this->get('core.common')->getUser();;//changyan_get_setting('user');
        if(!empty($db_user)) die('Error:User name is not empty!');
        
        //$chars = 'abcdefghigklmnopqrstuvwxwyABCDEFGHIGKLMNOPQRSTUVWXWY0123456789';

        $sign = self::changyan_gen_sign(CHANGYAN_CLIENT_ID);

        $url = $_SERVER['SERVER_NAME'];
        $isv_name = self::cn_substr($cfg_webname,20);
        $paramsArr=array(
            'client_id'=>CHANGYAN_CLIENT_ID,
            'isv_name'=>self::changyan_autoCharset($isv_name),
            'url'=>'http://'.$url,
            'sign'=>$sign);
        
        $rs = self::changyan_http_send(CHANGYAN_API_AUTOREG,0,$paramsArr);
        //var_dump($rs);exit;
        $result=json_decode($rs,TRUE);
        if($result['status']==0)
        {
            // 保存appid,id信息
            self::changyan_set_setting('user', $result['user']);
            self::changyan_set_setting('appid', $result['appid']);
            self::changyan_set_setting('id', $result['id']);
            self::changyan_set_setting('isv_app_key', $result['isv_app_key']);
            self::changyan_set_setting('isv_id', $result['isv_id']);
            self::changyan_clearcache();
            $passwd = self::changyan_mchStrCode($result['passwd'], 'ENCODE');
            self::changyan_set_setting('pwd', $passwd);
            header('Location:?');
            exit();
        } else {
            if($step > 10)
                throw new \LogicException("无法自动分配账号，请手动进行注册！",'?dopost=reg&nocheck=yes');

            $step++;
            header('Location:?dopost=autoreg&nocheck=yes&i='.$step);
            exit();
        }
    }
    
    public function binduser()
    {
        $type = 'reg';
        
        $action = 'do';
        
        $user = '';
        $pwd = 'aa123654';
        $repwd = 'aa123654';
        
        $type = empty($type)? 'reg' : $type;

        if($action=='do')
        {
            if($type!='reg') $repwd=$pwd;
            if(empty($user) OR empty($pwd) OR empty($repwd))
                throw new \LogicException("您需要填写E-mail和密码，请重新填写");

            if(!filter_var($user, FILTER_VALIDATE_EMAIL))
                throw new \LogicException("您的E-mail格式错误，请重新填写");

            if($pwd != $repwd)
                throw new \LogicException("填写两次密码不同，请返回重新输入！",-1);

            if($type=='reg')
            {
                $errorInfo='';
                if(self::changyan_bind_account($user, $pwd, &$errorInfo))
                    throw new \LogicException("绑定成功，下面进行账号切换……！","?dopost=quick_login&nocheck=yes");
                 else
                    throw new \LogicException("账号未绑定成功，请检查您输入的信息是否有误：{$errorInfo}！",-1);
            } else {
                //var_dump("Location:?dopost=login&user={$user}&pwd={$pwd}");exit;
                header("Location:?dopost=login&user={$user}&pwd={$pwd}&clear=yes");
                exit();
            }
            exit();
        }
    }
    
    public function quick_login()
    {
        $clear = '';
        $forward = '';
        $user = self::changyan_get_setting('user');
        $pwd=self::changyan_mchStrCode(self::changyan_get_setting('pwd'), 'DECODE') ;
        $sign=self::changyan_gen_sign($user);
        $paramsArr=array(
            'client_id'=>CHANGYAN_CLIENT_ID,
            'user'=>$user,
            'password'=>$pwd,
            'sign'=>$sign);
        $rs=self::changyan_http_send(CHANGYAN_API_LOGIN,0,$paramsArr);
        $result=json_decode($rs,TRUE);
        if($result['status']==0)
        {
            if(!empty($clear)) self::changyan_set_setting('isv_id', '');
            //$appid = self::changyan_get_setting('appid');
            $isv_id = self::changyan_get_setting('isv_id');
            $isvs = self::changyan_get_isvs();
            $isv_in = FALSE;
            if(!empty($isv_id) ) foreach($isvs as $isv){ if($isv['id']==$isv_id) $isv_in=TRUE; }
            $_SESSION['changyan']=$result['token'];
            $_SESSION['user']=$user;
            if(!$isv_in)
                throw new \LogicException("尚未设置站点APP信息，请进行配置……",'?dopost=change_appinfo');
            else {
                header('Location:?forward='.$forward);
                exit();
            }
        } else {
            self::changyan_set_setting('pwd', '');
            header('Location:?');
            exit();
        }
    }
    
    public function login()
    {
        $user = '';
        $pwd = '';
        $clear = '';
        //$rmpwd = empty($rmpwd)? '' : $rmpwd;

        if(empty($user) OR empty($pwd))
            throw new \LogicException("您需要填写E-mail和密码，请重新填写");

        if(!filter_var($user, FILTER_VALIDATE_EMAIL))
            throw new \LogicException("您的E-mail格式错误，请重新填写");
        
        $sign=self::changyan_gen_sign($user);
        $paramsArr=array(
            'client_id'=>CHANGYAN_CLIENT_ID,
            'user'=>$user,
            'password'=>$pwd,
            'sign'=>$sign);
        $rs=self::changyan_http_send(CHANGYAN_API_LOGIN,0,$paramsArr);
        $result=json_decode($rs,TRUE);
        if($result['status']==1)
            throw new \LogicException("无法登录，请检查您的帐号信息是否填写正确！",-1);
        elseif ($result['status']==0)
        {
            $db_user = self::changyan_get_setting('user');
        
            if($db_user != $user)
            {
                self::changyan_set_setting('user', $user);
                self::changyan_set_setting('isv_app_key', '');
                //$isv_app_key = self::changyan_get_isv_app_key();
            }
            if(!empty($clear)) self::changyan_set_setting('isv_id', '');
            $isv_id = self::changyan_get_setting('isv_id');
            $isvs = self::changyan_get_isvs();
            $isv_in = FALSE;
            if(!empty($isv_id)) foreach($isvs as $isv){ if($isv['id']==$isv_id) $isv_in=TRUE; }
            $_SESSION['changyan']=$result['token'];
            $_SESSION['user']=$user;
            $login_url=CHANGYAN_API_SETCOOKIE.'?client_id='.CHANGYAN_CLIENT_ID.'&token='.$result['token'];
        
            $pwd = self::changyan_mchStrCode($pwd, 'ENCODE');
        
            self::changyan_set_setting('pwd', $pwd);
        
            echo <<<EOT
<iframe src="{$login_url}" scrolling="no" width="0" height="0" style="border:none"></iframe>
EOT;
            if(!$isv_in)
                throw new \LogicException("尚未设置站点APP信息，请进行配置……",'?dopost=change_appinfo');
            else {
                header('Location:?');
                exit();
            }
        } else
            throw new \LogicException("无法登录，未知错误！",-1);
    }
    
    public function changyan_gen_sign($user)
    {
        $phpv = phpversion();
        $sp_os = PHP_OS;
        $nurl = $_SERVER['HTTP_HOST'];
        if( preg_match("#[a-z\\-]{1,}\\.[a-z]{2,}#i",$nurl) ) {
            $nurl = urlencode($nurl);
        }
        else {
            $nurl = "test";
        }

        $aes_url=CHANGYAN_API_SIGN.'?platform=dedecms'.'&input='.$user."&formurl={$nurl}&phpver={$phpv}&os={$sp_os}&cyver=".CHANGYAN_VER;

        return self::changyan_http_send($aes_url);
    }
    
    public function changyan_autoCharset($msg,$type='out')
    {
        return $msg;
    }
    
    public function changyan_http_send($url, $limit=0, $post='', $cookie='', $timeout=15)
    {
        return $this->get('core.common')->getContents($url, 0, $post, $cookie, $timeout);
    }
    
    public function changyan_set_setting($skey, $svalue)
    {
        global $dsql;
        $stime=time();
        $skey=addslashes($skey);
        $svalue=addslashes($svalue);
        $sql="UPDATE `#@__plus_changyan_setting` SET svalue='{$svalue}',stime='{$stime}' WHERE skey='{$skey}' ";
        $dsql->ExecuteNoneQuery($sql);
    }
    
    public function changyan_mchStrCode($string, $operation = 'ENCODE')
    {
        $key_length = 4;
        $expiry = 0;
        $key = md5($GLOBALS['cfg_cookie_encode']);
        $fixedkey = md5($key);
        $egiskeys = md5(substr($fixedkey, 16, 16));
        $runtokey = $key_length ? ($operation == 'ENCODE' ? substr(md5(microtime(true)), -$key_length) : substr($string, 0, $key_length)) : '';
        $keys = md5(substr($runtokey, 0, 16) . substr($fixedkey, 0, 16) . substr($runtokey, 16) . substr($fixedkey, 16));
        $string = $operation == 'ENCODE' ? sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$egiskeys), 0, 16) . $string : base64_decode(substr($string, $key_length));
    
        $i = 0; $result = '';
        $string_length = strlen($string);
        for ($i = 0; $i < $string_length; $i++){
            $result .= chr(ord($string{$i}) ^ ord($keys{$i % 32}));
        }
        if($operation == 'ENCODE') {
            return $runtokey . str_replace('=', '', base64_encode($result));
        } else {
            if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$egiskeys), 0, 16)) {
                return substr($result, 26);
            } else {
                return '';
            }
        }
    }
    
    function changyan_bind_account($new_user,$new_pwd, $errorinfo='')
    {
        $old_user = self::changyan_get_setting('user');
        $old_password = self::changyan_mchStrCode(self::changyan_get_setting('pwd'), 'DECODE') ;
        $sign = self::changyan_gen_sign($old_user);
        $paramsArr=array(
            'client_id'=>CHANGYAN_CLIENT_ID,
            'old_user'=>$old_user,
            'old_password'=>$old_password,
            'new_user'=>$new_user,
            'new_password'=>$new_pwd,
            'sign'=>$sign);
        $rs=json_decode(self::changyan_http_send(CHANGYAN_API_BINDACC.'?'.http_build_query($paramsArr)),TRUE);
        if(!isset($rs['status'])) return FALSE;
        if($rs['status']==0)
        {
            $new_pwd = self::changyan_mchStrCode($new_pwd, 'ENCODE');
            self::changyan_set_setting('user', $new_user);
            self::changyan_set_setting('pwd', $new_pwd);
            self::changyan_set_setting('isv_app_key', '');
            //$isv_app_key = self::changyan_get_isv_app_key();
            self::changyan_clearcache();
            return TRUE;
        } else {
            $errorinfo = $rs['msg'];
            return FALSE;
        }
    }
    
    public function changyan_get_setting($skey, $time=false, $real=false)
    {
        global $dsql;
        static $setting = array();
        $skey=addslashes($skey);
        if (empty($setting[$skey]) || $real) {
            $row = $dsql->GetOne("SELECT * FROM `#@__plus_changyan_setting` WHERE skey='{$skey}'");
            $setting[$skey]['svalue']=$row['svalue'];
            $setting[$skey]['stime']=$row['stime'];
        }
        if (!isset($setting[$skey])) return $time ? array() : null;
        return $time ? $setting[$skey] : $setting[$skey]['svalue'];
    }
    
    public function changyan_get_isv_app_key()
    {
        global $client_id;
        $isv_app_key= self::changyan_get_setting('isv_app_key');
        if (!empty($isv_app_key)) {
            return $isv_app_key;
        } else {
            return FALSE;
        } //接口下线，直接通过设定appinfo接口
        $user = self::changyan_get_setting('user');
    
        if (!empty($isv_app_key)) {
            return $isv_app_key;
        }
        $isv_app_key='';
        $sign = self::changyan_gen_sign($user);
        $paramsArr=array(
            'client_id'=>CHANGYAN_CLIENT_ID,
            'user'=>$user,
            'user_appid'=>$client_id,
            'sign'=>$sign);
        $rs = self::changyan_http_send(CHANGYAN_API_GETAPPKEY.'?'.http_build_query($paramsArr));
        $rs = json_decode($rs,TRUE);
        if (isset($rs['isv_app_key'])) {
            $isv_app_key=$rs['isv_app_key'];
        }
        self::changyan_set_setting('isv_app_key', $isv_app_key);
        return $isv_app_key;
    }
    
    public function changyan_get_isvs()
    {
        $user = self::changyan_get_setting('user');
        $sign = self::changyan_gen_sign($user);
        $paramsArr=array(
            'client_id'=>CHANGYAN_CLIENT_ID,
            'user'=>$user,
            'sign'=>$sign);
        $rs = json_decode(self::changyan_http_send(CHANGYAN_API_GETISVS.'?'.http_build_query($paramsArr)),TRUE);
        if(isset($rs['isvs'])) return json_decode($rs['isvs'],TRUE);
        else return FALSE;
    }
    
    public function changyan_clearcache()
    {
        //$prefix = 'changyan';
        //$key = 'code';
        //DelCache($prefix, $key);
    }
    
    public function cn_substr($str,$name)
    {
        
    }
}