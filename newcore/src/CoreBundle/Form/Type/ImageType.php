<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年2月22日
*/
namespace CoreBundle\Form\Type;

use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Exception\LogicException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ImageType extends BaseType
{
    protected $container;
    protected $filesystem;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->filesystem = new Filesystem();
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (!isset($options['info'])||!is_array($options['info'])||empty($options['info']))
            throw new LogicException('未定义info参数');
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $id = (int)$this->get('request')->get('id',0);
        $urlParam = array();
        $type = isset($options['info']['type'])?$options['info']['type']:'text';
        $urlParam['width'] = isset($options['attr']['width'])?$options['attr']['width']:100;
        $urlParam['height'] = isset($options['attr']['height'])?$options['attr']['height']:100;
        $view->vars['width'] = $urlParam['width'];
        $view->vars['height'] = $urlParam['height'];


        $urlParam['multiple'] = isset($options['attr']['multiple'])?$options['attr']['multiple']:0;

        $urlParam['multiple'] = $urlParam['multiple']==2&&$id>0?0:$urlParam['multiple'];

        $view->vars['multiple'] = $urlParam['multiple'];

        unset($options['attr']['width']);
        unset($options['attr']['height']);
        unset($options['attr']['multiple']);

        //$view->vars['required'] = false;
        //$view->vars['readonly'] = false;
        $view->vars['value'] = array();
        $view->vars['type'] = "hidden";
        $view->vars['attr'] = $options['attr'];
        $view->vars['width'] = $urlParam['width'];
        $view->vars['height'] = $urlParam['height'];
        $view->vars['upparam'] = $options['info']['name'].",".$type;
        $view->vars['url'] = $this->get('core.common')->U('attachment/upload', $urlParam);

        if(isset($options['data'])&&$options['data'])
        {
            if(is_null(json_decode($options['data'])))
            {
                $view->vars['value'][0]['href'] = $options['data'];
                $view->vars['value'][0]['source'] = $options['data'];
                $view->vars['value'][0]['desc'] = "";
                $view->vars['value'][0]['link'] = "";
            }else
                $view->vars['value'] = json_decode($options['data'], true);
        }

        if($urlParam['multiple']>0)
            $view->vars['display'] = "";
        else
            $view->vars['display'] = "style=display:none;";
    }

    /**
     * 处理规则
     * 注：本方法中针对 $obj不要使用原生 method_exists,要使用 parent::method_exists
     * multiple=1 多图，2多图文
     */
    public function handleRule()
    {
        $form = func_get_arg(0);
        $obj = func_get_arg(1);
        $formRequest = func_get_arg(2);

        $nameVal = isset($formRequest[$form['name']])?$formRequest[$form['name']]:'';

        $id = (int)$this->get('request')->get('id',0);
        $attr = $this->get('core.common')->getQueryParam($form['attr']);
        $multiple = isset($attr['multiple'])?(int)$attr['multiple']:0;

        $multiple = $multiple==2&&$id>0?0:$multiple;

        $field = $this->get('core.common')->ucWords($form['name']);

        $data = array();
        $imgHref = $this->get('request')->get($form['name']);
        $imgDesc = $this->get('request')->get($form['name']."desc");
        $imgLink = $this->get('request')->get($form['name']."link");
        $imgWidth = $this->get('request')->get($form['name']."width");
        $imgHeight = $this->get('request')->get($form['name']."height");
        $imgSource = $this->get('request')->get($form['name']."source");


        $imgX = $this->get('request')->get($form['name']."X");
        $imgY = $this->get('request')->get($form['name']."Y");
        $imgW = $this->get('request')->get($form['name']."W");
        $imgH = $this->get('request')->get($form['name']."H");
        $imgRotate = $this->get('request')->get($form['name']."Rotate");
        //$imgScaleX = $this->get('request')->get($form['name']."ScaleX");
        //$imgScaleY = $this->get('request')->get($form['name']."ScaleY");


        // 当类型为缩略图并且值为空 ,获取 content 里的第一张图片
        if ($form['name'] == 'thumb' && $nameVal && $nameVal[0] == '' && isset($formRequest['content']) && $formRequest['content'] != '')
        {
            $imgpreg = "/<img(.*)src=\"([^\"]+)\"[^>]+>/isU";
            // 匹配图片路径，$img[2]为图片路径
            $img = array();
            preg_match_all($imgpreg,$formRequest['content'],$img);
        
            if (!empty($img[2]))
            {
                if (substr($img[2][0], 0, 1) == '/')
                    $nameVal[0] = substr($img[2][0], 1);
                else
                    $nameVal[0] = $img[2][0];
            }
        }
        
        if(is_object($obj) && parent::method_exists($obj, "set{$field}"))
            $obj->{"set".$field}(is_array($nameVal)?$nameVal[0]:$nameVal);

        if(is_array($obj))
            $obj[$form['name']] = is_array($nameVal)?$nameVal[0]:$nameVal;

        if(!$imgSource||!is_array($imgSource))
            return $obj;
        
        $root = $this->get('core.common')->getWebRoot();

        foreach($imgSource as $key=>$imgPath)
        {
            $reCreate = true;

            //如果不存在则跳过
            if(!$this->filesystem->exists($root.$imgPath))
                continue;

            //加载图片处理类
            $img = new \CoreBundle\Services\Image\Image($root.$imgPath);

            $dstFile = $root.$imgPath;


            //判断是否要裁切
            if(isset($imgW[$key])&&isset($imgH[$key])&&$imgW[$key]>0&&$imgH[$key]>0)
            {
                $ext = $img->getExt();

                //原图
                $sourceImg = preg_replace('/_'.$imgWidth.'_'.$imgHeight.'.'.$ext.'$/','.'.$ext, $imgPath);

                //从原图切
                $img = new \CoreBundle\Services\Image\Image($root.$sourceImg);

                //目标图
                $imgHref[$key] = preg_replace('/.'.$ext.'$/','_'.$imgWidth.'_'.$imgHeight.'.'.$ext, $sourceImg);
                $dstFile = $root.$imgHref[$key];

                //加载图片裁切类
                $imgCut = new \CoreBundle\Services\Image\ImageCut($img);
                $imgCut->setDstFile($dstFile);
                $imgCut->setDstWidth($imgW[$key]);
                $imgCut->setDstHeight($imgH[$key]);
                $imgCut->setDstX($imgX[$key]);
                $imgCut->setDstY($imgY[$key]);
                $imgCut->setDstRotate($imgRotate[$key]);

                $imgCut->execute();

                //重新加载图片处理类
                $img = new \CoreBundle\Services\Image\Image($dstFile);

                $reCreate = false;

                $img->makeThumb($dstFile, $imgWidth, $imgHeight);
            }


            //判断是否要加水印
//             if(isset($form['iswatermark'])&&$form['iswatermark'])
//             {
//                 $filePath = $this->get('core.common')->getSiteRoot()."Config".DIRECTORY_SEPARATOR."config_watermark.yml";
//                 $info = $this->get('core.ymlParser')->ymlRead($filePath);

//                 $newIMG=explode('/',strrev($imgPath));
//                 $newimage=explode('_',strrev($newIMG[0]));

//                 //判断是否上传的新图片
//                 if($newimage[0]!=strrev($newIMG[0])) continue;

//                 if(isset($info[$form['iswatermark']])&&$info[$form['iswatermark']]['status']&&$reCreate)
//                 {
//                     if(isset($info[$form['iswatermark']]['type'])&&$info[$form['iswatermark']]['type']==1)
//                         $imgurl = isset($info[$form['iswatermark']]['img'])?($root.$info[$form['iswatermark']]['img']):null;
//                     else
//                         $imgurl = isset($info[$form['iswatermark']]['img'])?$info[$form['iswatermark']]['img']:null;

//                     $img->makeThumbByWater($dstFile
//                         , isset($info[$form['iswatermark']]['type'])?$info[$form['iswatermark']]['type']:1
//                         , isset($info[$form['iswatermark']]['position'])?$info[$form['iswatermark']]['position']:0
//                         , isset($info[$form['iswatermark']]['trans'])?$info[$form['iswatermark']]['trans']:0
//                         , isset($info[$form['iswatermark']]['textcontent'])?$info[$form['iswatermark']]['textcontent']:null
//                         , $imgurl
//                         , isset($info[$form['iswatermark']]['fontcolor'])?$info[$form['iswatermark']]['fontcolor']:'#666666'
//                         , isset($info[$form['iswatermark']]['quality'])?$info[$form['iswatermark']]['quality']:0
//                         , isset($info[$form['iswatermark']]['fontsize'])?$info[$form['iswatermark']]['fontsize']:12);
//                 }
//             }
        }

        foreach($imgHref as $k=>$v)
        {
            $data[$k]['href'] = ltrim($v,"/");
            $data[$k]['desc'] = isset($imgDesc[$k])?$imgDesc[$k]:"";
            $data[$k]['link'] = isset($imgLink[$k])?$imgLink[$k]:"";
            $data[$k]['source'] = ltrim(isset($imgSource[$k])?$imgSource[$k]:"","/");
        }

        if(is_object($obj) && parent::method_exists($obj, "set{$field}"))
        {
            if($multiple>0)
                $obj->{"set".$field}(json_encode($data));
            else
                $obj->{"set".$field}(ltrim($imgHref[0],"/"));
        }

        if(is_array($obj))
        {
            if($multiple>0)
                $obj[$form['name']] = json_encode($data);
            else
                $obj[$form['name']] = ltrim($imgHref[0],"/");

            return $obj;
        }
    }
    
    //[注] 未用//??//!!
    private function _multipleHandle($obj, $field)
    {
        //获取表单ID
        $formId = (int)$this->get('request')->get('_form_id',0);

        $formInfo = $this->get('db.model_form')->findOneBy(array('id'=>$formId));
        $modelInfo = $this->get('db.models')->getData($formInfo->getModelId());

        if(is_object($obj))
        {
            $fields = json_decode($obj->{"get".$field}(), true);

            $endField = array_pop($fields);

            $data = array();
            foreach($fields as $key=>$item)
            {
                $objs = clone($obj);
                $data[$key] = $objs->{"set".$field}($item['source']);
            }

            if($data&&isset($modelInfo['service']))
                $this->get($modelInfo['service'])->batchadd($data);

            $obj->{"set".$field}($endField['source']);
        }else{

        }
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'info'=>array()
            ,'compound'=>false
        ));
    }

    public function getParent()
    {
        return 'text';
    }

    public function getName()
    {
        return 'image';
    }
}