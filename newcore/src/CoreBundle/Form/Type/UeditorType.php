<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年2月29日
*/
namespace CoreBundle\Form\Type;

use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UeditorType extends BaseType
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
        $view->vars['value'] = isset($view->vars['value'])?$this->get('core.common')->htmlToCode($view->vars['value']):"";
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'info' => array()
            ,'compound' => false
        ));
    }
    
    public function getParent()
    {
        return 'textarea';
    }
    
    public function getName()
    {
        return 'ueditor';
    }

    /**
     * 处理规则 
     * 注：本方法中针对 $obj不要使用原生 method_exists,要使用 parent::method_exists
     * @param array $form        表单配置参数
     * @param object $obj        写表对象数据
     * @param array $formRequest 表单写入数据
     * @throws \InvalidArgumentException
     *
     */
    public  function handleRule()
    {
        $form = func_get_arg(0);
        $obj = func_get_arg(1);
        $formRequest = func_get_arg(2);

        $nameVal = isset($formRequest[$form['name']])?$formRequest[$form['name']]:'';

        if($nameVal)
            $nameVal = $this->get('core.common')->htmlToCode($nameVal);
        
        $action = "set".$this->get('core.common')->ucWords($form['name']);

        if(parent::method_exists($obj, $action))
            call_user_func_array(array($obj, $action),array($nameVal));
            //$obj->{$action}($nameVal);
        
        // 添加水印
//         if (isset($form['iswatermark'])&&$form['iswatermark'])
//         {
//             if (is_object($obj) && parent::method_exists($obj, 'getContent'))
//                $content = $obj->getContent();

//             if (isset($content) && $content)
//             {
//                 $imgpreg = "/<img(.*)src=\"([^\"]+)\"[^>]+>/isU";

//                 $img = array();
//                 // 匹配图片路径，$img[2]为图片路径
//                 preg_match_all($imgpreg,$content,$img);
                
//                 $filePath = $this->get('core.common')->getSiteRoot()."Config".DIRECTORY_SEPARATOR."config_watermark.yml";
//                 $info = $this->get('core.ymlParser')->ymlRead($filePath);
                
//                 if(isset($info[$form['iswatermark']])&&$info[$form['iswatermark']]['status'])
//                 {
//                     $root = $this->get('core.common')->getWebRoot();
//                     if(isset($info[$form['iswatermark']]['type'])&&$info[$form['iswatermark']]['type']==1)
//                         $imgurl = isset($info[$form['iswatermark']]['img'])?($root.$info[$form['iswatermark']]['img']):null;
//                     else
//                         $imgurl = isset($info[$form['iswatermark']]['img'])?$info[$form['iswatermark']]['img']:null;
                    
//                     foreach ($img[2] as $path)
//                     {
//                         if (substr($path, 0, 1) == '/')
//                             $path = substr($path, 1);

//                         $img = new \CoreBundle\Services\Image\Image($root.$path);
//                         if ($img->height>$info[$form['iswatermark']]['minheight'] && $img->width>$info[$form['iswatermark']]['minwidth'])
//                         {
//                             $img->makeThumbByWater($root.$path
//                                 , isset($info[$form['iswatermark']]['type'])?$info[$form['iswatermark']]['type']:1
//                                 , isset($info[$form['iswatermark']]['position'])?$info[$form['iswatermark']]['position']:0
//                                 , isset($info[$form['iswatermark']]['trans'])?$info[$form['iswatermark']]['trans']:0
//                                 , isset($info[$form['iswatermark']]['textcontent'])?$info[$form['iswatermark']]['textcontent']:null
//                                 , $imgurl
//                                 , isset($info[$form['iswatermark']]['fontcolor'])?$info[$form['iswatermark']]['fontcolor']:'#666666'
//                                 , isset($info[$form['iswatermark']]['quality'])?$info[$form['iswatermark']]['quality']:0
//                                 , isset($info[$form['iswatermark']]['fontsize'])?$info[$form['iswatermark']]['fontsize']:12);
//                         }
//                     }
//                 }
//             }
//         }

        if(is_array($obj))
            return $obj;
    }
}