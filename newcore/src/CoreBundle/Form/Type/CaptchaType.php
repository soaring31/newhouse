<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2015-6-26
*/
namespace CoreBundle\Form\Type;

use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Exception\LogicException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CaptchaType extends BaseType
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {

    }

    /**
     * 处理规则
     * 注：本方法中针对 $obj不要使用原生 method_exists,要使用 parent::method_exists
     */
    public function handleRule()
    {
        $form = func_get_arg(0);
        $formRequest = func_get_arg(2);

        $name = isset($form['name'])?$form['name']:'';

        $attr = isset($form['attr'])&&$form['attr']?$this->get('core.common')->getQueryParam($form['attr']):array();

        $length = isset($attr['length'])?$attr['length']:5;

        if(empty($name))
            return false;

        $vid = isset($attr['vid'])&&$attr['vid']&&isset($formRequest[$attr['vid']])?$formRequest[$attr['vid']]:'';
        $sendTo = isset($attr['sendto'])&&$attr['sendto']?$this->get('request')->get($attr['sendto'],''):'';
        $rules = isset($attr['rule'])?$attr['rule']:'';
        $ruleText = isset($attr['ruletext'])?$attr['ruletext']:'';

        $tpl = isset($attr['tpl'])?$attr['tpl']:'';
        
        $mconfig = $this->get('db.mconfig')->getData(array('ename'=>'mwebset'));
        $siteName = isset($mconfig['hostname']['value'])?$mconfig['hostname']['value']:'';

        $content = sprintf($ruleText, $vid)."【".$siteName."】";

        $value = $this->get('request')->get($name,'');
        
        if (!$form['required'] && $value=='')
            return true;

        if(empty($value))
            throw new LogicException(sprintf('%s 不能为空',$form['label']));

        //判断验证码位数
        if(strlen($value)!=$length)
            throw new LogicException(sprintf('请输入%s位的验证码', $length));
        //判断验证码
        $this->get('core.captcha')->setSessionWord($name);

        if($this->get('core.captcha')->check_word($value,false)!=1)
            throw new LogicException(sprintf('%s 验证错误', $form['label']));

        if($rules&&$vid&&$sendTo)
        {
            $rules = explode(',', $rules);

            $option = array();
            $option['name'] = $sendTo;
            $option['title'] = $content;
            $option['code'] = $vid;
            $option['tpl'] = $tpl?trim($tpl):"code.html.twig";

            foreach($rules as $rule)
            {
                switch($rule)
                {
                    //发邮箱
                    case 'email':
                        if(!filter_var($sendTo, FILTER_VALIDATE_EMAIL))
                            continue;
                        
//                         if($tpl)
//                             $contentbody = $this->get('templating')->render("Emails/".$option['tpl'], $option);
//                         else
//                             $contentbody = $content;

                        $this->get('core.mail')->send($option);
                        break;
                    //发手机
                    case 'mobile':
                        if(!$this->get('core.common')->isMobile($sendTo))
                            continue;

                        if($this->get('core.sms')->isClosed())
                            throw new \InvalidArgumentException('手机短信功能已关闭，请联系管理人员！');

                        $sms = $this->get('core.sms');
                        if (isset($attr['reg']) && $attr['reg'] == 1)
                            $sms->setNoCheck(true);
                        $sms->send($option);
                        break;
                }
            }
        }
        
        unset($tpl);
        unset($vid);
        unset($form);
        unset($name);
        unset($attr);
        unset($rules);
        unset($value);
        unset($option);
        unset($length);
        unset($sendTo);
        unset($content);
        unset($ruleText);        
        unset($formRequest);
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'info' => array()
            ,'empty_value'=>''
            ,'compound' => false
        ));
    }

    public function getName()
    {
        return 'captcha';
    }
}