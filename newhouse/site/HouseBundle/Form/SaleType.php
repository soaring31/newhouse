<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年10月24日
*/
namespace HouseBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SaleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder     
            ->add('lpmc', 'text', array('label_attr'=>array('class'=>'w150 rtd txtright',),'required'=>'1','label'=>'小区名称','attr'=>array('autocomplete'=>'off','data-tpl'=>'%x%<div class="tip">输入小区名称或地址进行搜索,只有选择搜索到的小区才能关联</div>','class'=>'ka220','data-autocomplete'=>1,),))     
            ->add('zj', 'integer', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'总价','attr'=>array('data-tpl'=>'%x%<div class="tip">万元 [留空或0表示面议]</div>',),))     
            ->add('mj', 'integer', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'建筑面积','attr'=>array('data-tpl'=>'%x%<div class="tip">单位：M²</div>',),))     
            ->add('dj', 'integer', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'单价','attr'=>array('data-tpl'=>'%x%<div class="tip">单位：元/M²,自动由总价和面积计算出来</div>',),))     
            ->add('name', 'text', array('label_attr'=>array('class'=>'w150 rtd txtright',),'required'=>'1','label'=>'房源标题','attr'=>array('data-tpl'=>'%x%<div class="tip">很重要,好的标题（至少包含 路段、楼盘名、户型 便于用户搜索）能有效提升房源点击率</div>','class'=>'ka330',),))     
            ->add('address', 'text', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'地址','attr'=>array('class'=>'ka330',),))     
            ->add('map', 'baidumap', array('label_attr'=>array('class'=>'w150 rtd txtright',),'info'=>array('model_form_id'=>166,),'label'=>'地图','attr'=>array('class'=>'ka280',),))     
            ->add('fwpt', 'checkbox', array('label_attr'=>array('class'=>'w150 rtd txtright',),'info'=>array('choices'=>'1=厨房
2=床
3=家具
4=有线电视
5=热水器
6=宽带网
7=电话
8=饮水机
9=电视机
10=空调
11=洗衣机
12=冰箱
13=煤气
14=暖气
15=车库
16=天然气','model_form_id'=>166,'value'=>1,),'label'=>'房屋配套',))     
            ->add('content', 'ueditor', array('label_attr'=>array('class'=>'w150 rtd txtright',),'info'=>array('model_form_id'=>166,'iswatermark'=>'textFun',),'label'=>'房源描述','attr'=>array('zIndex'=>10,'autoFloatEnabled'=>'true','class'=>'dist','data-ueditor-opt'=>'{type:"simple", autoHeightEnabled: "true"}',),))     
            ->add('cid', 'hidden', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'经纪公司id',))     
            ->add('zxcd', 'entity', array('label_attr'=>array('class'=>'w150 rtd txtright',),'info'=>array('entitypath'=>'house.category','property'=>'name','model_form_id'=>166,'query_builder'=>'ename=zxcd',),'label'=>'装修程度','placeholder'=>'装修程度',))     
            ->add('esflx', 'entity', array('label_attr'=>array('class'=>'w150 rtd txtright',),'info'=>array('required'=>'1','entitypath'=>'house.category','property'=>'name','model_form_id'=>109,'query_builder'=>'ename=esflx','value'=>'[esflx]?94',),'label'=>'二手房类型','attr'=>array('type'=>'radio',),))     
            ->add('uid', 'hidden', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'会员id',))     
            ->add('tel', 'text', array('label_attr'=>array('class'=>'w150 rtd txtright',),'required'=>'1','label'=>'联系人电话','attr'=>array('data-tpl'=>'%x%<div class="tip">例：13423045276</div>','isTel'=>1,),))     
            ->add('xingming', 'text', array('label_attr'=>array('class'=>'w150 rtd txtright',),'required'=>'1','label'=>'联系人',))     
            ->add('cx', 'entity', array('label_attr'=>array('class'=>'w150 rtd txtright',),'info'=>array('entitypath'=>'house.category','property'=>'name','model_form_id'=>166,'query_builder'=>'ename=cx',),'label'=>'朝向','placeholder'=>'朝向',))     
            ->add('fl', 'entity', array('label_attr'=>array('class'=>'w150 rtd txtright',),'info'=>array('choices'=>'0=房龄
1=不详
2015=2015 年
2014=2014 年
2013=2013 年
2012=2012 年
2011=2011 年
2010=2010 年
2009=2009 年
2008=2008 年
2007=2007 年
2006=2006 年
2005=2005 年
2004=2004 年
2003=2003 年
2002=2002 年
2001=2001 年
2000=2000 年
1999=1999 年
1998=1998 年
1997=1997 年
1996=1996 年
1995=1995 年
1994=1994 年
1993=1993 年
1992=1992 年
1991=1991 年
1990=1990 年
1989=1989 年
1988=1988 年
1987=1987 年
1986=1986 年
1985=1985 年
1984=1984 年
1983=1983 年
1982=1982 年
1981=1981 年
1980=1980 年
1979=1979 年
1978=1978 年
1977=1977 年
1976=1976 年
1975=1975 年
1974=1974 年
1973=1973 年
1972=1972 年
1971=1971 年
1970=1970 年
1969=1969 年
1968=1968 年
1967=1967 年
1966=1966 年
1965=1965 年
1964=1964 年
1963=1963 年
1962=1962 年
1961=1961 年
1960=1960 年
1959=1959 年
1958=1958 年
1957=1957 年
1956=1956 年
1955=1955 年
1954=1954 年
1953=1953 年
1952=1952 年
1951=1951 年','entitypath'=>'house.category','property'=>'name','model_form_id'=>166,'query_builder'=>'ename=fl',),'label'=>'房龄','placeholder'=>'房龄',))     
            ->add('shi', 'entity', array('label_attr'=>array('class'=>'w150 rtd txtright',),'info'=>array('choices'=>'1=1室
2=2室
3=3室
4=4室
5=5室
6=6室','entitypath'=>'house.category','property'=>'name','model_form_id'=>217,'query_builder'=>'ename=shi',),'label'=>'0室','placeholder'=>'0室',))     
            ->add('ting', 'entity', array('label_attr'=>array('class'=>'w150 rtd txtright',),'info'=>array('choices'=>'1=1厅
2=2厅
3=3厅
4=4厅
5=5厅
100=0厅','entitypath'=>'house.category','property'=>'name','model_form_id'=>217,'query_builder'=>'ename=ting',),'label'=>'0厅','placeholder'=>'0厅',))     
            ->add('chu', 'entity', array('label_attr'=>array('class'=>'w150 rtd txtright',),'info'=>array('choices'=>'1=1厨
2=2厨
3=3厨
4=4厨
5=5厨
100=0厨','entitypath'=>'house.category','property'=>'name','model_form_id'=>217,'query_builder'=>'ename=chu',),'label'=>'0厨','placeholder'=>'0厨',))     
            ->add('wei', 'entity', array('label_attr'=>array('class'=>'w150 rtd txtright',),'info'=>array('choices'=>'1=1卫
2=2卫
3=3卫
4=4卫
5=5卫
100=0卫','entitypath'=>'house.category','property'=>'name','model_form_id'=>217,'query_builder'=>'ename=wei',),'label'=>'0卫','placeholder'=>'0卫',))     
            ->add('yangtai', 'entity', array('label_attr'=>array('class'=>'w150 rtd txtright',),'info'=>array('choices'=>'1=1阳台
2=2阳台
3=3阳台
4=4阳台
5=5阳台
100=0阳台','entitypath'=>'house.category','property'=>'name','model_form_id'=>217,'query_builder'=>'ename=yangtai',),'label'=>'0阳台','placeholder'=>'0阳台',))     
            ->add('szlc', 'text', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'楼层','attr'=>array('data-tpl'=>'<div class="dib mr10">第%x%层</div>','style'=>'width:60px','class'=>'ml5 mr5','number'=>1,'errorLabelContainer'=>'.error-wrap',),))     
            ->add('zlc', 'text', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'共','attr'=>array('data-tpl'=>'共%x%层','style'=>'width:60px','class'=>'mr5 ml5','number'=>1,'errorLabelContainer'=>'.error-wrap',),))     
            ->add('group_type', 'text', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'类型/属性','attr'=>array('data-compound'=>'fwjg,zxcd,cx,fl',),'compound'=>'1',))     
            ->add('fwjg', 'entity', array('label_attr'=>array('class'=>'dn',),'info'=>array('entitypath'=>'house.category','property'=>'name','model_form_id'=>199,'query_builder'=>'ename=fwjg',),'label'=>'房屋结构','placeholder'=>'房屋结构',))     
            ->add('group_lc', 'text', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'楼层','attr'=>array('data-compound'=>'szlc,zlc','data-tpl'=>'%x%<div class="tip">请输入整数,-1表示地下第1层。</div>',),'compound'=>'1',))     
            ->add('models', 'hidden', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'模型',))     
            ->add('usertype', 'hidden', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'会员组',))     
            ->add('valid', 'radio', array('label_attr'=>array('class'=>'w150 rtd txtright',),'info'=>array('choices'=>'<@gettoday|86400|sale>=上架
<@gettoday>=下架','model_form_id'=>166,'value'=>'<@gettoday|86400|sale>',),'label'=>'上下架','attr'=>array('data-tpl'=>'%x% <div class=tip>上架则在前台显示, 下架只在仓库显示</div>',),))     
            ->add('eid', 'hidden', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'委托房源id',))     
            ->add('region', 'ajaxbind', array('label_attr'=>array('class'=>'w150 rtd txtright',),'info'=>array('entitypath'=>'db.area','property'=>'name','model_form_id'=>316,'query_builder'=>'pid={area}',),'label'=>'区域','attr'=>array('src-service'=>'house.cate_circle','src-field'=>'pid','src-name'=>'cate_circle','src-title'=>'商圈','data-select-opt'=>'{name:"level1,level2"}','data-name'=>'level1,level2',),'placeholder'=>'区域',))     
            ->add('cate_line', 'ajaxbind', array('label_attr'=>array('class'=>'w150 rtd txtright',),'info'=>array('entitypath'=>'house.cate_line','property'=>'name','model_form_id'=>318,'validate_rule'=>'closesubway|mother','query_builder'=>'checked=1',),'label'=>'地铁','attr'=>array('src-service'=>'house.cate_metro','src-field'=>'pid','src-name'=>'cate_metro',),'placeholder'=>'地铁',))     
            ->add('group_huxing', 'text', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'户型','attr'=>array('data-compound'=>'shi,ting,wei,chu,yangtai',),'compound'=>'1',))     
            ->add('aid', 'hidden', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'楼盘id',))     
            ->add('refreshdate', 'hidden', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'默认刷新时间',))     
            ->add('fdnote', 'textarea', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'内部备注',))     
            ->add('fdtel', 'text', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'房东电话',))     
            ->add('submit', 'submit', array('label'=>'提交','attr'=>array('class'=>'btn font-ico-22','data-role'=>'submit','data-reload'=>1,'data-close'=>1,),))     
            ->add('group_line', 'text', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'地铁/地铁站','attr'=>array('data-compound'=>'cate_line,cate_metro',),'compound'=>'1',))     
            ->add('group_area', 'text', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'区域/商圈','attr'=>array('data-compound'=>'region,cate_circle','name'=>'cate_circle',),'compound'=>'1',))     
            ->add('fdname', 'text', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'房东姓名','attr'=>array('data-isonlys'=>'fdname,fdtel',),))     
            ->add('group_attr', 'text', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'二手房-基本属性','attr'=>array('data-compound'=>'jian,lpmc,group_area,group_line,zj,mj,dj,group_huxing',),'compound'=>'1',))     
            ->add('thumb', 'image', array('label_attr'=>array('class'=>'w150 rtd txtright',),'info'=>array('model_form_id'=>166,'iswatermark'=>'giffun',),'label'=>'略缩图','attr'=>array('width'=>150,'height'=>'150 ','thumbType'=>1,),))     
            ->add('group_set', 'text', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'详情设置','attr'=>array('data-compound'=>'name,thumb,tujis,address,map,group_type,group_lc,esflx,fwpt,content',),'compound'=>'1',))     
            ->add('tujis', 'image', array('label_attr'=>array('class'=>'w150 rtd txtright',),'info'=>array('model_form_id'=>166,'iswatermark'=>'giffun',),'label'=>'房源图片','attr'=>array('width'=>150,'height'=>'150 ','thumbType'=>1,'multiple'=>1,),))     
            ->add('group_other', 'text', array('label_attr'=>array('class'=>'w150 rtd txtright',),'label'=>'其他设置','attr'=>array('data-compound'=>'valid,xingming,tel,keywords,abstract,fdname,fdtel,fdnote,clicks',),'compound'=>'1',))        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sale';
    }
}
