<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年11月30日
*/
namespace CoreBundle\Form\Type;

use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PasswordType extends BaseType
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['value'] = '';
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

    /**
     * 处理规则
     * 注：本方法中针对 $obj不要使用原生 method_exists,要使用 parent::method_exists
     */
    public function handleRule()
    {
        $form = func_get_arg(0);
        $obj = func_get_arg(1);
        $formRequest = func_get_arg(2);

        $attr = $this->get('core.common')->getQueryParam($form['attr']);

        $vid = isset($attr['vid'])?$attr['vid']:'';
        $rule = isset($attr['rule'])?$attr['rule']:'';
        
        //jquery校验表单规则
        if(isset($attr['equalTo']))
        {
            $rule = 'comp';
            $vid = trim($attr['equalTo'],'#');
        }

        $nameVal = isset($formRequest[$form['name']])?$formRequest[$form['name']]:'';

        //变更密码
        if($nameVal&&(parent::method_exists($obj, "set".$this->get('core.common')->ucWords($form['name']))))
        {
            $str = uniqid(mt_rand(),1);

            $obj->setSalt(substr(sha1($str), 0, 10));
            $obj->{"set".$this->get('core.common')->ucWords($form['name'])}($this->get('security.encoder')->encodePassword($nameVal, $obj->getSalt()));
        }

        switch($rule)
        {
            case 'chkpwd':
                //检测旧密码
                if(!$this->get('security.encoder')->isPasswordValid($obj->getPassword(), $nameVal, $obj->getSalt()))
                    throw new \LogicException('原密码错误');
                break;
            case 'comp':
                $vidVal = isset($formRequest[$vid])?$formRequest[$vid]:'';
                if($vid&&$nameVal!==$vidVal)
                    throw new \InvalidArgumentException((isset($form['error_info'])&&$form['error_info'])?$form['error_info']:"确认密码不一致");
                break;
            default:
                break;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'text';
    }

    public function getName()
    {
        return 'password';
    }
}