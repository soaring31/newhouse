<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年10月10日
*/
namespace HouseBundle\Controller;

/**
* 
* @author house
*/
class SmsController extends Controller
{

    /**
    * 实现的telEncode方法
    * house
    */
    public function telEncodeAction()
    {
        $tel = $this->get('core.sms')->telConvert('en');
        return $this->show($tel); //array('OK',$tel)
    }

    /**
    * 实现的tpl方法
    * house
    */
    public function smstplAction()
    {
        $option = $this->get('request')->request->all();

        $option['tpl'] = $this->get('request')->get('tpl','');
        $option['id'] = $this->get('request')->get('id','');
        $option['name'] = $this->get('request')->get('tel','');
        $option['title'] = $this->get('request')->get('msg','');
        $option['reg'] = (int)$this->get('request')->get('reg',0);
        
        unset($option['tel']);
        unset($option['msg']);
        // checked=1时说明这个验证码已经被使用过，强制设置为0
        $option['checked'] = 0;
        if(!$this->get('core.common')->isMobile($option['name']))
            return parent::error('不是手机号码！');

        //生成随机验证码
        $option['code'] = $this->get('core.sms')->getCode();

        if(isset($option['id'])&&$option['id'] == 'smscode'&&$option['reg'] == 1&&!$this->get('db.users')->checkTelExist($option['name']))
            throw new \LogicException('用户不存在！');

        $this->get('core.sms')->setNoCheck(true);

        $res = $this->get('core.sms')->send($option);

        return self::show($res);
    }

    /**
     * 邮件验证码
     */
    public function mailtplAction()
    {
        if($this->get('request')->getMethod() == "POST")
        {
            $this->get('core.mail')->send($this->get('request')->request->all());
            return $this->success('发送成功');
        }
        
        if ($this->get('request')->get('id','') == 'smscode')
        {
            $option = $this->get('request')->request->all();
            
            $reg = (int)$this->get('request')->get('reg',0);
            
            $option['name'] = isset($option['name'])?$option['name']:$this->get('request')->get("mail","");
            // 检查是否已经注册过本网站
            if ($reg==1 && !$this->get('db.users')->checkEmailExist($option['name']))
                throw new \LogicException("用户[".$option['name']."]不存在！");

            $this->get('core.mail')->send($option);
            return $this->success('发送成功');
        }
        
        return $this->error('发送失败');
    }

    /**
    * 实现的user方法
    * house
    */
    public function smsuserAction()
    {
        $tel = $this->get('request')->get('tel');
        $user = '(now)'; //$this->get('request')->get('user');
        $msg = $this->get('request')->get('msg');
        $res = $this->get('core.sms')->setUser($user)->send($tel,$msg); 
        return $this->show($res); 
    }

    /**
    * 实现的show方法
    * house
    */
    public function show($res=array())
    {
        $debug = $this->get('request')->get('debug');
        if($debug){
            dump($res);
            die();
        }else{
            $str = is_string($res) ? $res : "$res[1]";

            if (strtolower($str) == 'ok')
                return $this->success('发送成功');
            if(isset($res[0])&&$res[0]<=0)
                return $this->error($str);
            return $this->success('发送成功');
        }
    }

    // array(1,'操作成功'): send($mobiles,$content='',$type=0,$smscode=0)

}