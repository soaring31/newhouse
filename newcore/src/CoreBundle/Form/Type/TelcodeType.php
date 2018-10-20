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

class TelcodeType extends BaseType
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
        if (self::isClosed())
            return true;
        $form = func_get_arg(0);
        $formRequest = func_get_arg(2);
        $formInfoArr = func_get_arg(3);

        $name = isset($form['name'])?$form['name']:'';

        $attr = isset($form['attr'])?$this->get('core.common')->getQueryParam($form['attr']):array();
        $length = isset($attr['length'])?$attr['length']:4;

        //判断模板是否关闭
        if (isset($attr['data-codetpl']) && !$this->get('cc')->checkTplIsOpen($attr['data-codetpl'], self::getName()))
            return true;
        
        if(empty($name))
            return false;
        
        if(!isset($attr['data-tel'])||empty($attr['data-tel']))
            throw new LogicException('未定义 data-tel 参数');
        
        $tel = $formRequest[$attr['data-tel']];

        $value = isset($formRequest[$name])?$formRequest[$name]:'';
        
        if (!$form['required'] && $value=='')
            return true;
        
        if(empty($tel))
            throw new LogicException(sprintf('%s 不能为空',$formInfoArr[$attr['data-tel']]['label']));
        
        if(!$this->get('core.common')->isMobile($tel))
            throw new LogicException(sprintf('%s 不是有效的手机号码',$formInfoArr[$attr['data-tel']]['label']));

        if(empty($value))
            throw new LogicException(sprintf('%s 不能为空',$form['label']));

        //判断验证码位数
        if(strlen($value)!=$length)
            throw new LogicException(sprintf('%s 为 %d 位的验证码', $form['label'], $length));
        
        //判断验证码
        if(!$this->get('core.sms')->checkSmscode($tel, $value))
            throw new LogicException(sprintf('%s 验证错误',$form['label']));
        
        //自动注册并登陆
        if(isset($attr['autologin']))
            $this->get('db.users')->autoLogin($tel);
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
            ,'isClose' => self::isClosed()
            ,'type'=>self::getName()
        ));
    }

    public function getName()
    {
        return 'telcode';
    }
    
    private function isClosed()
    {
        return $this->get('core.sms')->isClosed();
    }
}