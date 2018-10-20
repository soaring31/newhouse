<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年08月21日
*/
namespace ManageBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ModelsformattrshowType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder     
            ->add('name', 'text', array('label_attr'=>array('class'=>'w150 rtd txtright',),'required'=>'1','label'=>'标识名称','attr'=>array('class'=>'w300','data-tpl'=>'%x% <div class=tip>只能输入英文字母</div>',),))     
            ->add('label', 'text', array('label_attr'=>array('class'=>'w150 rtd txtright',),'required'=>'1','label'=>'中文名称','attr'=>array('class'=>'w300',),))     
            ->add('attr', 'textarea', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'属性','attr'=>array('class'=>'w300',),))     
            ->add('choices', 'textarea', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'参数','attr'=>array('class'=>'w300','placeholder'=>'字段类型为布尔、单选、复选、枚举时的定义数据,其它字段类型为空','maxlength'=>'2000',),))     
            ->add('type', 'choice', array('label_attr'=>array('class'=>'w150 rtd txtright',),'choices'=>array('text'=>'text_字符串','integer'=>'integer_数字','password'=>'password_密码','geetestlib'=>'geetestlib_滑动验证码','captcha'=>'captcha_图形验证码','telcode'=>'telcode_短信验证码','mailcode'=>'mailcode_邮箱验证码','textarea'=>'textarea_文本框','hidden'=>'hidden_隐藏字段','datetime'=>'datetime_日期时间','date'=>'date_日期','idate'=>'idate_时间戳','radio'=>'radio_单选框','choice'=>'choice_下拉框','checkbox'=>'checkbox_复选框','email'=>'email_email格式','submit'=>'submit_submit按钮','switch'=>'switch_开关按钮','file'=>'file_上传附件','image'=>'image_上传图片','entity'=>'entity_entity源','ymlfile'=>'ymlfile_yml源','push'=>'push_推送源','ajaxbind'=>'ajaxbind_ajax关联','bundle'=>'bundle_bundle源','clonetpl'=>'clonetpl_克隆模版','mailtpl'=>'mailtpl_邮箱模版','mobiletpl'=>'mobiletpl_手机模版','fragmentstpl'=>'fragmentstpl_外调模版','ueditor'=>'ueditor_编辑器','ormlist'=>'ormlist_多库列表','position'=>'position_水印位置','textcolor'=>'textcolor_颜色选择器','abstracttext'=>'abstracttext_摘要','baidumap'=>'baidumap_Baidu地图',),'label'=>'字段类型','attr'=>array('chosen-opt'=>'{width: 310}',),))     
            ->add('iswatermark', 'ymlfile', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'水印方案',))     
            ->add('dealhtml', 'choice', array('label_attr'=>array('class'=>'w150 rtd txtright',),'choices'=>array('clearhtml'=>'清除Html标签','disablehtml'=>'仅显示文本','safehtml'=>'保护性过滤Html','html_decode'=>'HTML反编码',),'label'=>'清除HTML代码',))     
            ->add('dealhtmltags', 'choice', array('label_attr'=>array('class'=>'w150 rtd txtright',),'choices'=>array('a'=>'链接','tbody'=>'表格体','form'=>'表单','table'=>'表格','img'=>'图片','frame'=>'框架','tr'=>'表格行','script'=>'脚本','li_ul_dd_dt'=>'列表','td'=>'表格单元','b_strong'=>'加粗','tab'=>'换行','p'=>'段落','br'=>'换行','trim'=>'去首尾空白字符','font'=>'字体','nbsp'=>'空格','iframe'=>'内框架','div'=>'层','h'=>'H标签','sub_sup'=>'上下标','span'=>'Span','hr'=>'hr标签','all'=>'所有标签',),'label'=>'HTML处理项','attr'=>array('chosen-opt'=>'{width:"100%"}',),))     
            ->add('bundle', 'checkbox', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'所属bundle',))     
            ->add('required', 'choice', array('label_attr'=>array('class'=>'w150 rtd txtright',),'required'=>'1','choices'=>array('1'=>'是',),'label'=>'是否必填','attr'=>array('chosen-opt'=>'{disable_search:true}',),))     
            ->add('model_form_id', 'entity', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'所属表单','attr'=>array('data-tpl'=>'%x% <div class=tip>必须选择所属表单</div>',),))     
            ->add('entitypath', 'text', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'查询服务','attr'=>array('class'=>'w300',),))     
            ->add('property', 'text', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'显示字段','attr'=>array('class'=>'w300',),))     
            ->add('query_builder', 'textarea', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'查询规则','attr'=>array('class'=>'w300',),))     
            ->add('error_info', 'text', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'出错提示','attr'=>array('class'=>'w300',),))     
            ->add('validate_rule', 'text', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'开关标识','attr'=>array('data-tpl'=>'%x%<div class="tips">控制此字段是否显示,配置方式：填写系统设置里的配置字段标识,比如开启商圈,此处填写closesqcircle</div>','class'=>'w300',),))     
            ->add('validate_time', 'choice', array('label_attr'=>array('class'=>'w150 rtd txtright',),'required'=>'1','choices'=>array('3'=>'始终','1'=>'新增','2'=>'编辑',),'label'=>'生效状态','attr'=>array('chosen-opt'=>'{disable_search:true}',),))     
            ->add('auto_type', 'choice', array('label_attr'=>array('class'=>'w150 rtd txtright',),'required'=>'1','choices'=>array('function'=>'函数','field'=>'字段','string'=>'字符串',),'label'=>'自动完成方式','attr'=>array('chosen-opt'=>'{disable_search:true}',),))     
            ->add('auto_rule', 'text', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'自动完成规则','attr'=>array('class'=>'w300',),))     
            ->add('isonly', 'choice', array('label_attr'=>array('class'=>'w150 rtd txtright',),'required'=>'1','choices'=>array('1'=>'是',),'label'=>'是否唯一','attr'=>array('chosen-opt'=>'{disable_search:true}',),))     
            ->add('value', 'text', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'默认值',))     
            ->add('sort', 'text', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'排序',))     
            ->add('submit', 'submit', array('label'=>'submit','attr'=>array('class'=>'search-btn ajax-post reload','target-form'=>'form',),))     
            ->add('status', 'choice', array('label_attr'=>array('class'=>'w150 rtd txtright',),'required'=>'1','choices'=>array('1'=>'启用',),'label'=>'状态','attr'=>array('chosen-opt'=>'{disable_search:true}',),))        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ManageBundle\Entity\ModelFormAttr'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'modelsformattrshow';
    }
}
