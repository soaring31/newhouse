<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年3月23日
*/
namespace CoreBundle\Form\Type;

use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Exception\LogicException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MailcodeType extends BaseType
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
        
        //判断模板是否关闭
        if (isset($attr['data-codetpl']) && !$this->get('cc')->checkTplIsOpen($attr['data-codetpl'], self::getName()))
            return true;

        if(empty($name))
            return false;
        
        if(!isset($attr['data-mail'])||empty($attr['data-mail']))
            throw new LogicException('未定义 data-mail 参数');
        
        $mail = $formRequest[$attr['data-mail']];

        $value = isset($formRequest[$name])?$formRequest[$name]:'';
        
        if (!$form['required'] && $value=='')
            return true;

        if(empty($mail))
            throw new LogicException(sprintf('%s 不能为空',$formInfoArr[$attr['data-mail']]['label']));

        if(!filter_var($mail, FILTER_VALIDATE_EMAIL))
            throw new LogicException(sprintf('%s 不是有效的邮箱格式',$formInfoArr[$attr['data-mail']]['label']));

        if(empty($value))
            throw new LogicException(sprintf('%s 不能为空',$form['label']));
        
        //判断验证码
        if(!$this->get('core.mail')->checkCode($mail, $value))
            throw new LogicException(sprintf('%s 验证错误',$form['label']));
        
        //自动注册并登陆
        if(isset($attr['autologin']))
            $this->get('db.users')->autoLogin($mail);
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
        return 'mailcode';
    }
    
    private function isClosed()
    {
        return $this->get('core.mail')->isClosed();
    }
}