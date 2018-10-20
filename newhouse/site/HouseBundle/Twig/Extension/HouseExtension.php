<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年5月31日
*/
namespace HouseBundle\Twig\Extension;

use CoreBundle\Twig\Extension\BaseExtension;
use Symfony\Component\DependencyInjection\ContainerInterface;

class HouseExtension extends BaseExtension
{
    protected $container;

    public function __construct(ContainerInterface $container)
	{
	    $this->container = $container;
	}
	

	public function getName()
	{
	    return 'house.extension';
	}

	/**
	 * 目录表
	 * @see Twig_Extension::getFilters()
	 */
	public function getFilters() {
	    return array(
	        new \Twig_SimpleFilter('getShowBytes', array($this, 'getShowBytes')),
	        new \Twig_SimpleFilter('getPushs', array($this, 'getPushss')),
	    );
	}
	
	public function getShowBytes($num, $type='Byte')
	{
		$b = 0;
		if(is_numeric($num)){ $b = $num; }
		if($type==='Byte'){
			if($b>pow(1024,4)){
				$b = number_format($b/(pow(1024,4)),2)." (TB) ";
			}else if($b>pow(1024,3)) {
				$b = number_format($b/(pow(1024,3)),2)." (GB) ";
			}else if($b>pow(1024,2)) {
				$b = number_format($b/(pow(1024,2)),2)." (MB) ";
			}else if($b>pow(1024,1)) {
				$b = number_format($b/(pow(1024,1)),2)." (KB) ";
			}else{
				$b = $b." (B) ";
			}
			return $b;
		}else{
			return number_format($b,$type);
		}
	}
	

	/**
	 * 获取推送位
	 * @param string $str
	 * @param array $params
	 */
	public function getPushss($str, array $params=array())
	{
	    $info = array();
	    $params = array_merge(array(
	        'pageIndex' => 1,
	        'pageSize' => 8,
	    ), $params);


	    
	    $info['data'] = array();
	    $info['pageIndex'] = $params['pageIndex'];
	    $info['pageSize'] = $params['pageSize'];
	    $info['pageCount'] = 0;

	    $params = parent::getQueryParam($params);
	    
	    $cate_pushs = $this->get('house.cate_pushs')->findOneBy(array('ename'=>$str));
	    
	    if(!is_object($cate_pushs))
	        return $info;
	    
	    $fromData = isset($params['criteria']['_from_data'])?(int)$params['criteria']['_from_data']:$cate_pushs->getFromData();

	    switch($fromData)
	    {
	        //推送位
	        case 0:	            
	            $params['criteria']['cate_pushs'] = $str;
	            if(!isset($params['criteria']['_from_data']))
	            {
	                $params['criteria']['start_time']['lte'] = date('Y-m-d');
	                $params['criteria']['end_time']['gte'] = date('Y-m-d');
	            }

	            unset($params['criteria']['_from_data']);
	            return self::_pushHandle($info, $params);
	            break;
	        //模型数据
	        case 1:
	            return self::_modelHandle($cate_pushs, $params);
	            break;
	    }

	    return $info;
	}
	
	/**
	 * 基于推送位推送的处理
	 */
	private function _pushHandle($info, $params)
	{
	    $params['criteria']['findType'] = 1;
	    $params['order']['sort_fixed'] = 'desc';
	    $params['order']['sort'] = 'asc';
	    $params['order']['create_time'] = 'desc';

	    $pushs = $this->get('house.pushs')->findBy($params['criteria'], $params['order'], $params['criteria']['pageSize'], $params['criteria']['pageIndex']);
	    
        $info['pageCount'] = isset($pushs['pageCount'])?$pushs['pageCount']:0;

	    if(isset($pushs['data']))
	    {

	        //固位排序
	        $pushs['data'] = $this->get('core.common')->array_sortfixed($pushs['data'], 'sort_fixed');

	        foreach($pushs['data'] as $key=>$item)
	        {
	            $info['data'][$key] = array();
	            $model = $item['models'];
	            $item['url'] = isset($item['url'])?$item['url']:'';
	            if($model)
	            {
    	            try {
    	                $serviceName = "house.".$model;

    	                $map = array();
    	                $map['id'] = $item['fromid'];
    	                $map['findType'] = 1;
    	                $modelInfo = $this->get($serviceName)->findOneBy($map);

    	                if($modelInfo)
    	                    $item['name'] = $item['name']?$item['name']:$modelInfo['name'];

    	                $info['data'][$key] = array_merge($modelInfo, $item);

    	                $fromid = $this->get('core.common')->encode($model.','.(int)$item['fromid']);
    	                
    	                $modelsInfo = $this->get('db.models')->findOneBy(array('name'=>$model));

    	                if(is_object($modelsInfo)&&$modelsInfo->getType()==1)
    	                    $item['url'] = $item['url']?$item['url']."?id=".$item['fromid']:"detail/{$fromid}";
    	                else
    	                    $item['url'] = $item['url']?$item['url']."?category=".$item['fromid']:$item['models']."/index?category=".$item['fromid'];

    	                unset($modelInfo);
    	            } catch (\Exception $e) {
    	                throw new \LogicException("HouseExtension:[".$e->getMessage()."]", false);
    	            }
	            }
	            
	            $item['url'] = $item['url']?$this->get('core.common')->U($item['url']):'';

	            $map = array();
	            $map['puid'] = $item['id'];
	            $map['findType'] = 1;
	            $pushAttr = $this->get('house.pushs_attr')->findBy($map);

	            if(isset($pushAttr['data']))
	            {
	                foreach($pushAttr['data'] as $items)
	                {
	                    if(isset($items['value'])&&$items['value'])
	                    {
	                        $info['data'][$key][$items['name']] = $items['value'];
	                    }
	                }
	            }
	            $info['data'][$key] = array_merge($info['data'][$key], $item);
	        }
	    }

	    return $info;
	}
	
	/**
	 * 基于模型推送的处理
	 */
	private function _modelHandle($cate_pushs, $params)
	{
	    $params['order']['create_time'] = isset($params['order']['create_time'])?$params['order']['create_time']:'asc';
	    try {
	        $models = explode(',', $cate_pushs->getModels());

	        $info = $this->get('core.table_manage')->getUNIONSearch($models, $params);
	        
	        if(isset($info['data']))
	        {
    	        foreach($info['data'] as &$v)
    	        {
    	            $v['url'] = '';

    	            if(!isset($v['models'])||empty($v['models']))
    	                continue;

    	            $fromid = $this->get('core.common')->encode($v['models'].','.(int)$v['id']);
    	            
    	            $v['url'] = "detail/{$fromid}";
    	            
    	            $v['url'] = $this->get('core.common')->U("detail/{$fromid}");
    	        }
	        }
	        
	        return $info;
	    } catch (\Exception $e) {
	        throw new \LogicException("HouseExtension:[".$e->getMessage()."]", false);
	    }
	}
}