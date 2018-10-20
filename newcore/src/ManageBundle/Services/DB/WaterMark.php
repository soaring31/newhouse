<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年5月25日
*/
namespace ManageBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class WaterMark extends AbstractServiceManager
{
    protected  $table = 'WaterMark';

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }

    /**
     * 单条数据查询
     * !CodeTemplates.overridecomment.nonjd!
     * @see \CoreBundle\Services\AbstractServiceManager::findOneBy()
     */
    public function findOneBy(array $criteria, array $orderBy = array(), $flag=true)
    {
        $filePath = $this->get('core.common')->getSiteRoot();
        $filePath .= "Config".DIRECTORY_SEPARATOR."config_watermark.yml";
    
        $info = $this->get('core.ymlParser')->ymlRead($filePath);
        $info = is_array($info)?$info:array();
        return isset($info[$criteria['id']])?$info[$criteria['id']]:array();
    }
    
    /**
     * 添加
     * @param array $data
     * @param \stdClass $info
     * @throws \InvalidArgumentException
     */
    public function add(array $data,  $info=null, $isValid=true)
    {
        self::_handleData($data);

        $filePath = $this->get('core.common')->getSiteRoot();
        $filePath .= "Config".DIRECTORY_SEPARATOR."config_watermark.yml";

        $info = $this->get('core.ymlParser')->ymlRead($filePath);
        $info = is_array($info)?$info:array();
        
        $info[$data['ename']] = $data;

        //写入配置文件
        $this->get('core.ymlParser')->ymlWrite($info, $filePath);

        return true;
    }

    /**
     * 更新
     * @see \CoreBundle\Services\AbstractServiceManager::update()
     */
    public function update($id, array $data, $info=null, $isValid=true)
    {
        self::_handleData($data);

        $ename = $data['ename'];
        unset($data['ename']);
        
        $filePath = $this->get('core.common')->getSiteRoot();
        $filePath .= "Config".DIRECTORY_SEPARATOR."config_watermark.yml";
        
        $info = $this->get('core.ymlParser')->ymlRead($filePath);
        $info = is_array($info)?$info:array();
        
        if(empty($ename)||!isset($info[$ename]))
            throw new \LogicException('该水印方案不存在或已被删除！！');

        $info[$ename] = array_merge($info[$ename], $data);

        //写入配置文件
        $this->get('core.ymlParser')->ymlWrite($info, $filePath);
        
        return true;
    }

    /**
     * 删除
     */
    public function delete($id, $info='')
    {
        $filePath = $this->get('core.common')->getSiteRoot();
        $filePath .= "Config".DIRECTORY_SEPARATOR."config_watermark.yml";
        
        $info = $this->get('core.ymlParser')->ymlRead($filePath);
        $info = is_array($info)?$info:array();
        
        if(empty($id)||!isset($info[$id]))
            return true;
        
        unset($info[$id]);
        
        //写入配置文件
        $this->get('core.ymlParser')->ymlWrite($info, $filePath);
        
        return true;
    }

    /**
     * 处理数据
     */
    private function _handleData(&$data)
    {
        unset($data['csrf_token']);

        if(!isset($data['position'])||empty($data['position']))
            throw new \LogicException('水印位置至少勾选一项！！');
        
        if(count($data['position'])>3)
            throw new \LogicException('水印位置最多只能勾选三项！！');
        
        if(!isset($data['name'])||empty($data['name']))
            throw new \LogicException('方案名称不能为空！！');
        
        if(!isset($data['ename'])||empty($data['ename']))
            throw new \LogicException('英文名称不能为空！！');
        
        if(!isset($data['minwidth'])||(int)$data['minwidth']<100)
            throw new \LogicException('超过以下宽度的图片加水印值  请输入不小于100的整数！！');
        
        if(!isset($data['minheight'])||(int)$data['minheight']<100)
            throw new \LogicException('超过以下高度的图片加水印值  请输入不小于100的整数！！');
        
        if(!isset($data['offsetx'])||(int)$data['offsetx']<5||(int)$data['offsetx']>100)
            throw new \LogicException('水印图片的水平边矩  请输入5至100的整数！！');
        
        if(!isset($data['offsety'])||(int)$data['offsety']<5||(int)$data['offsety']>100)
            throw new \LogicException('水印图片的垂直边矩  请输入5至100的整数！！');
        
        //属性表单数据
        $map = array();
        $map['model_form_id'] = $this->get('request')->get('_form_id',0);
        $map['status'] = 1;
        $map['findType'] = 1;
        $map['_multi'] = false;
        $formAttr = $this->get('db.model_form_attr')->findBy($map);
        
        $formData = $this->get('request')->request->all();

        //validateTime验证时间,3始终,1新增,2编辑
        foreach($formAttr['data'] as $item)
        {
            if(!isset($data[$item['name']]))
                continue;
            
            //处理规则
            $typeForm = $this->get('form.registry')->getType($item['type']?$item['type']:'text');
            $innerType = $typeForm->getInnerType();
            if(method_exists($innerType, 'handleRule'))
                $data = $innerType->handleRule($item, $data, $formData);
        }
        
        if(isset($data['type'])&&$data['type']!=2)
        {           
            unset($data['imgX']);
            unset($data['imgY']);
            unset($data['imgW']);
            unset($data['imgH']);
            unset($data['imgdesc']);
            unset($data['imglink']);
            unset($data['imgwidth']);
            unset($data['imgheight']);
            unset($data['imgsource']);
            unset($data['imgRotate']);
            unset($data['imgScaleX']);
            unset($data['imgScaleY']);

            if(!isset($data['trans'])||(int)$data['trans']<1||(int)$data['trans']>100)
                throw new \LogicException('图片水印融合度  请输入1至100的整数！！');
        
            if(!isset($data['quality'])||(int)$data['quality']>100)
                throw new \LogicException('JPEG图片水印后质量  请输入0至100的整数！！');
        
            if(!isset($data['img'])||empty($data['img']))
                throw new \LogicException('水印图片不能为空！！');
        }else{
            if(!isset($data['textcontent'])||empty($data['textcontent']))
                throw new \LogicException('文本水印内容不能为空！！');
        
            if(!isset($data['fontsize'])||(int)$data['fontsize']<0)
                throw new \LogicException('文本水印字体大小  请输入大于0的整数！！');
        }
    }
}