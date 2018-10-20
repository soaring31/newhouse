<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年03月30日
*/
namespace ManageBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
* 推送文档
* 
*/
class Pushs extends AbstractServiceManager
{
    protected $table = 'Pushs';
    protected $stag, $filePath;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
        
        //缓存标识,以数据库名称区别
        $this->stag = $this->get('core.common')->C('database_name')."_pushs";
        
        $this->filePath = $this->get('core.common')->getSiteRoot()."Config".DIRECTORY_SEPARATOR."config_pushs.yml";
    }
    
    /**
     * 新增
     * @see \CoreBundle\Services\AbstractServiceManager::add()
     */
    public function add(array $data,  $info=null, $isValid=true)
    {
        return self::handleYmlData(parent::add($data, $info, $isValid));
    }
    
    /**
     * 修改
     * @see \CoreBundle\Services\AbstractServiceManager::update()
     */
    public function update($id, array $data, $info=null, $isValid=true)
    {
        return self::handleYmlData(parent::update($id, $data, $info, $isValid));
    }
    
    /**
     * 批量新增
     * @see \CoreBundle\Services\AbstractServiceManager::batchadd()
     */
    public function batchadd(array $data)
    {
        return self::handleYmlData(parent::batchadd($data));
    }
    
    protected function handleYmlData($data)
    {
        $cateArr = array('yqlj', 'index_dblp', 'dbes', 'dbsy', 'dbzx', 'dbgj');
        
        //批量添加返回对象数组，单个添加或更新，则返回对象
        if(is_object($data)) {
            if(method_exists($data, 'getCatePushs') && !in_array($data->getCatePushs(), $cateArr)) {
                return $data;
            }
        }elseif(is_array($data) && !empty($data)) {
            foreach ($data as $v) {
                if(method_exists($v, 'getCatePushs') && !in_array($v->getCatePushs(), $cateArr)) {
                    return $data;
                }
            }
        }else return $data;

        $result = array();
        $map = array();
        $map['status'] = 1;
        $map['area'] = '_isnull';
        $map['order'] = 'sort|asc,id|asc';
        
        foreach($cateArr as $v)
        {
            $map['cate_pushs']['orX'][]['cate_pushs'] = $v;
        }

        $info = parent::findBy($map, null, 5000);

        if(isset($info['data']))
        {
            $modelsAtrr = array();//service  &&isset($modelsAtrr[$item->getModels()]['service'])

            foreach($info['data'] as $item)
            {
                if($item->getModels()&&!isset($modelsAtrr[$item->getModels()]))
                    $modelsAtrr[$item->getModels()] = $this->get('db.models')->getData($item->getModels(), 'name');
                
                if(!$item->getName()&&$item->getFromid()>0)
                {
                    $info = $this->get($modelsAtrr[$item->getModels()]['service'])->findOneBy(array('id'=>(int)$item->getFromid()));
                    $item->setName(is_object($info)?$info->getName():'');
                }
                
                $urlParams = $this->get('core.common')->getQueryParam($item->getUrlparams());
                
                if(isset($modelsAtrr[$item->getModels()]['type'])&&$modelsAtrr[$item->getModels()]['type']==1)
                {
                   
                    $urlParams['id'] = $item->getFromid();
                    
                    // 根据 url 进行判断，为空则为手动添加(需要加上detail后缀)，否则为加载推送
                    if ($item->getUrl() == '')
                        $url = $item->getModels().'/detail';
                    else 
                        $url = $item->getUrl();
                }else{
                    $urlParams['category'] = $item->getFromid();
                    $url = ($item->getUrl()?$item->getUrl():$item->getModels());//."/index";
                }

                $result[$item->getArea()][$item->getCatePushs()][$item->getId()]['id'] = $item->getId();
                $result[$item->getArea()][$item->getCatePushs()][$item->getId()]['fromid'] = $item->getFromid();
                $result[$item->getArea()][$item->getCatePushs()][$item->getId()]['cate_pushs'] = $item->getCatePushs();
                $result[$item->getArea()][$item->getCatePushs()][$item->getId()]['models'] = $item->getModels();
                $result[$item->getArea()][$item->getCatePushs()][$item->getId()]['start_time'] = strtotime($item->getStartTime()->format('Y-m-d'));
                $result[$item->getArea()][$item->getCatePushs()][$item->getId()]['end_time'] = strtotime($item->getEndTime()->format('Y-m-d'));
                $result[$item->getArea()][$item->getCatePushs()][$item->getId()]['name'] = $item->getName();
                $result[$item->getArea()][$item->getCatePushs()][$item->getId()]['url'] = $url;
                $result[$item->getArea()][$item->getCatePushs()][$item->getId()]['sort'] = $item->getSort();
                $result[$item->getArea()][$item->getCatePushs()][$item->getId()]['urlparams'] = $urlParams;
                $result[$item->getArea()][$item->getCatePushs()][$item->getId()]['thumb'] = $item->getThumb();
                $result[$item->getArea()][$item->getCatePushs()][$item->getId()]['ad'] = $item->getAd();
                $result[$item->getArea()][$item->getCatePushs()][$item->getId()]['html'] = $item->getHtml();
                $result[$item->getArea()][$item->getCatePushs()][$item->getId()]['pid'] = $item->getPid();
                $result[$item->getArea()][$item->getCatePushs()][$item->getId()]['menuid'] = $item->getMenuid();
                $result[$item->getArea()][$item->getCatePushs()][$item->getId()]['navicon'] = $item->getNavicon();
                $result[$item->getArea()][$item->getCatePushs()][$item->getId()]['abstract'] = $item->getAbstract();
                $result[$item->getArea()][$item->getCatePushs()][$item->getId()]['active'] = $item->getActive();
                $result[$item->getArea()][$item->getCatePushs()][$item->getId()]['style'] = $item->getStyle();
                $result[$item->getArea()][$item->getCatePushs()][$item->getId()]['target'] = $item->getTarget();
                $result[$item->getArea()][$item->getCatePushs()][$item->getId()]['thumbsm'] = $item->getThumbsm();
            }
        }
    
        $this->get('core.ymlParser')->ymlWrite($result, $this->filePath);

        //重置缓存
        $this->get('core.common')->S($this->stag, $result, 86400);
        
        unset($url);
        unset($info);
        unset($result);
        unset($urlParams);
        return $data;
    }
    
    /**
     * 直接从文件中读取
     * @param array $criteria
     * @return multitype:
     */
    public function getData($param)
    {
        $result = array();

        $info = $this->get('core.common')->S($this->stag);

        if(empty($info))
        {
            $info = $this->get('core.ymlParser')->ymlRead($this->filePath);

            $this->get('core.common')->S($this->stag, $info, 86400);
        }

        if(empty($info))
            return array();

        $area = isset($param['area'])?$param['area']:0;

        if(!isset($info[$area]))
            return array();
        
        $_result = isset($info[$area][$param['name']])?$info[$area][$param['name']]:array();
        
        $i = 0;

        foreach($_result as $item)
        {
            $startTime = (int)$item['start_time'];
            $endTime = (int)$item['end_time'];
            
            if($startTime>(int)time()||$endTime<(int)time()||$i>=$param['pageSize'])
                continue;


            $result[] = $item;
            
            $i++;
        }

        unset($info);
        unset($_result);

        return $result;
    }
}