<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2015-6-3
*/
namespace CoreBundle\Twig\Extension;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * twig模版扩展功能
 */

class VarsExtension extends BaseExtension
{
    protected $param;
    protected $metadata;
    protected $container;
    protected $pushId = array();
    protected $modelList = array();

	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}

	public function getName()
	{
		return 'vars.extension';
	}

	/**
	 * 目录表
	 * @see Twig_Extension::getFilters()
	 */
	public function getFilters()
	{
	    return array(
			new \Twig_SimpleFilter('searchRange', array($this, 'searchRange')),
	        new \Twig_SimpleFilter('U', array($this, 'U')),
	        new \Twig_SimpleFilter('UU', array($this, 'UU')),
	        new \Twig_SimpleFilter('C', array($this, 'C')),
	        new \Twig_SimpleFilter('P', array($this, 'isPermission')),
	        new \Twig_SimpleFilter('pager', array($this, 'pager')),
	        new \Twig_SimpleFilter('ceil', array($this, 'ceil')),
	        new \Twig_SimpleFilter('getRule', array($this, 'getRule')),
	        new \Twig_SimpleFilter('getRow', array($this, 'getRow')),
	        new \Twig_SimpleFilter('getCol', array($this, 'getCol')),
	        new \Twig_SimpleFilter('getOne', array($this, 'getOne')),
	        new \Twig_SimpleFilter('getCount', array($this, 'getCount')),
            new \Twig_SimpleFilter('getAll', array($this, 'getAll')),
            new \Twig_SimpleFilter('getAllNews', array($this, 'getAllNews')),
            new \Twig_SimpleFilter('getNodeSearch', array($this, 'getNodeSearch')),
	        new \Twig_SimpleFilter('getTreeSearch', array($this, 'getTreeSearch')),
	        new \Twig_SimpleFilter('getNodeById', array($this, 'getNodeById')),
	        new \Twig_SimpleFilter('getTree', array($this, 'getTree')),
	        new \Twig_SimpleFilter('getParents', array($this, 'getParents')),
	        new \Twig_SimpleFilter('in_array', array($this, 'in_array')),
	        new \Twig_SimpleFilter('is_numeric', array($this, 'is_numeric')),
	        new \Twig_SimpleFilter('is_array', array($this, 'is_array')),
	        new \Twig_SimpleFilter('is_string', array($this, 'is_string')),
	        new \Twig_SimpleFilter('isMobile', array($this, 'isMobile')),	        
	        new \Twig_SimpleFilter('array_sum', array($this, 'array_sum')),
	        new \Twig_SimpleFilter('array_search', array($this, 'array_search')),
	        new \Twig_SimpleFilter('json_decode', array($this, 'json_decode')),
	        new \Twig_SimpleFilter('unserialize', array($this, 'unserialize')),
	        new \Twig_SimpleFilter('pregReplace', array($this, 'pregReplace')),
	        new \Twig_SimpleFilter('isPermission', array($this, 'isPermission')),
	        new \Twig_SimpleFilter('isChecked', array($this, 'isChecked')),
	        new \Twig_SimpleFilter('isSelected', array($this, 'isSelected')),
	        new \Twig_SimpleFilter('isDisabled', array($this, 'isDisabled')),
	        new \Twig_SimpleFilter('tabStructure', array($this, 'tabStructure')),
	        new \Twig_SimpleFilter('storageTypes', array($this, 'storageTypes')),
	        new \Twig_SimpleFilter('tableColumn', array($this, 'tableColumn')),
	        new \Twig_SimpleFilter('tripodEncode', array($this, 'tripodEncode')),
	        new \Twig_SimpleFilter('tripodDecode', array($this, 'tripodDecode')),
	        new \Twig_SimpleFilter('encode', array($this, 'tripodEncode')),
	        new \Twig_SimpleFilter('decode', array($this, 'tripodDecode')),
	        new \Twig_SimpleFilter('operationSymbol', array($this, 'operationSymbol')),
	        new \Twig_SimpleFilter('getField', array($this, 'getField')),
	        new \Twig_SimpleFilter('getFormField', array($this, 'getFormField')),
	        new \Twig_SimpleFilter('unSerializer', array($this, 'unSerializer')),
	        new \Twig_SimpleFilter('arrayIntersectKey', array($this, 'arrayIntersectKey')),
	        new \Twig_SimpleFilter('noImgwh', array($this, 'noImgwh')),
	        new \Twig_SimpleFilter('badKeywords', array($this, 'badKeywords')),
	        new \Twig_SimpleFilter('hotKeywords', array($this, 'hotKeywords')),
	        new \Twig_SimpleFilter('sourceImg', array($this, 'sourceImg')),
	        new \Twig_SimpleFilter('targetImg', array($this, 'targetImg')),
	        new \Twig_SimpleFilter('getPushss', array($this, 'getBlock')),
	        new \Twig_SimpleFilter('getBlock', array($this, 'getBlock')),
	        new \Twig_SimpleFilter('getTop', array($this, 'getTop')),
	        new \Twig_SimpleFilter('newpath', array($this, 'newPath')),
	        new \Twig_SimpleFilter('getPush', array($this, 'getPush')),
	        new \Twig_SimpleFilter('getPushMuti', array($this, 'getPushMuti')),	        
            new \Twig_SimpleFilter('getForm', array($this, 'getForm')),
	        new \Twig_SimpleFilter('getIpArea', array($this, 'getIpArea')),
	        new \Twig_SimpleFilter('getQuery', array($this, 'getQuery')),
	        new \Twig_SimpleFilter('getQrcode', array($this, 'getQrcode')),
	        new \Twig_SimpleFilter('getMemAttach', array($this, 'getMemAttach')),
	        new \Twig_SimpleFilter('getMconfig', array($this, 'getMconfig')),
	        new \Twig_SimpleFilter('getCategory', array($this, 'getCategory')),
	        new \Twig_SimpleFilter('getCategoryById', array($this, 'getCategoryById')),
	        new \Twig_SimpleFilter('getPush2', array($this, 'getPush2')),
	        new \Twig_SimpleFilter('getAreaName', array($this, 'getAreaName')),
	        new \Twig_SimpleFilter('getWxConfig', array($this, 'getWxConfig')),
	        new \Twig_SimpleFilter('getRefreshs', array($this, 'getRefreshs')),
	        new \Twig_SimpleFilter('showarray', array($this, 'showarray')),
	        new \Twig_SimpleFilter('toArray', array($this, 'toArray')),
	        new \Twig_SimpleFilter('is_fireallow', array($this, 'is_fireallow')),
	        new \Twig_SimpleFilter('getReleated', array($this, 'getReleated')),
	        new \Twig_SimpleFilter('match', array($this, 'match'))
	    );
	}

	public function getQrcode($str=null)
	{
		return '';
		$qrcode = array(
            "action_name" => "QR_LIMIT_SCENE",
            "action_info" => array(
                "scene" => array("scene_id" => 1000)
                )
            );
        $jasoninfo = $this->get('oauth.weixin_account')->getTicket($qrcode);

        if(!isset($jasoninfo['ticket']))
            return '';

        $ticket = $jasoninfo['ticket'];
        $url = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".$ticket;

		return $url;
	}

	public function getQuery($str)
	{
		return $this->get('core.common')->getQueryParam($str);
	}

    public function getIpArea($ip = '')
    {
        return $this->get('core.area')->getIpArea($ip);
    }

	/**
	 * URL组装 支持不同URL模式
	 * @param string $url URL表达式，格式：'[分组/模块/操作@域名]?参数1=值1&参数2=值2...'
	 * @param string|array $vars 传入的参数，支持数组和字符串
	 * @param boolean $domain 是否显示域名
	 * @return string
	 */
	public function U($url=null, $vars='', $domain=true)
	{
	    if($url=='')
	        $url = $this->get('core.common')->getActionName();

	    $vars = $vars?$this->get('core.common')->getQueryArray($vars):'';

	    return $this->get('core.common')->U($url, $vars, $domain);
	}
	
	/**
	 * app的url组装
	 * @param string $url
	 * @param string $vars
	 * @param string $domain
	 */
	public function UU($url=null, $vars='', $domain=false)
	{
	    $_isApp = (int)$this->get('request')->get('_isApp',0);

	    $_url = self::U($url, $vars, $domain);

	    if((bool)$_isApp)
	    {
    	    $routeName = str_replace("/", "_", $url);
    	    $match = $this->get('router')->getRouteCollection()->get($routeName);
    
    	    if(is_object($match))
    	    {
        	    $defaults = $match->getdefaults();
        	    $action = $this->get('core.common')->getActionName($defaults['_controller']);
        	    $action .= '.html';
        	    $controller = $this->get('core.common')->getControllerName($defaults['_controller']);
        	    
        	    if ($controller&&$action)
        	       return '..'.$this->get('core.common')->setUrlParam("/".$controller."/".$action,$vars,null);
//         	    if($controller&&$action)
//         	        return "/".$controller."/".$action;
    	    }
    	    
    	    $bundle = strtolower($this->get('core.common')->getBundleName());
    	    
    	    if (preg_match('/bundle$/', $bundle))
    	        $bundle = substr($bundle, 0, -6);

    	    $url = $this->get('request')->getBaseUrl()?str_replace(array($this->get('request')->getBaseUrl()."/".$bundle."/"),"",$_url):$_url;
            $url = ltrim($url, '/');
    	    $url = explode('/', $url);
    	    
    	    if(!isset($url[0]))
    	        $url[0] = 'index';
    	    
    	    if(!isset($url[1]))
    	        $url[1] = 'index';
    	    
    	    // 当$url最后一个有参数的时候， .html后缀不能加在最后面
    	    // 原先：show?formName=mob_sale&models=sale
    	    // 改后：show.html?formName=mob_sale&models=sale
    	    if (strpos(end($url),'?')!==false)
    	    {
    	        $temp = end($url);
    	        $temp = explode('?', $temp);

    	        // array(0=>"show",1=>"formName=mob_sale&models=sale")
    	        if (count($temp)==2)
    	        {
    	            //移除最后一个数组元素
    	            array_pop($url);
    	            //添加最后一个数组元素,拼接上 .html
    	            array_push($url, $temp[0].'.html?'.$temp[1]);
    	            
        	        if ($url[0]=='http:' || $url[0]=='https:')
        	            return implode('/', $url);
        	        else 
        	            return '../'.implode('/', $url);
    	        }
    	    }
    	    
    	    if ($url[0]=='http:' || $url[0]=='https:')
    	       return implode('/', $url);
    	    
    	    return '../'.implode('/', $url).'.html';
	    }
	
	    return $_url;
	}

	/**
	 * 读配置文件
	 * @param string $str
	 */
	public function C($str)
	{
	    return $this->get('core.common')->C($str);
	}

	/**
	 * 读表单
	 */
	public function getForm($formId)
	{
	    $initInfo = array();
	    
	    // $form为数字或字符串时$flag对应的值
	    $flag = 'name';
	    if (is_numeric($formId))
	        $flag = null;

	    $modelForm = $this->get('db.model_form')->getData($formId, $flag);

	    if($modelForm)
	    	$this->get('core.common')->getForm($modelForm, $initInfo, false, 0, 1);

	    return isset($initInfo['form'])?$initInfo['form']:array();
	}

	/**
	 * 分页
	 * @param int $pageIndex
	 * @param int $pageSize
	 * @param int $pageCount
	 * @param int $parameter
	 */
	public function pager($pageIndex, $pageSize, $pageCount, $parameter=array())
	{
	    $parMap = array();
	    $parMap['varPage'] = 'pageIndex';
	    $parMap['totalRows'] = $pageCount;
	    $parMap['pageSize'] = $pageSize;
	    $parMap['pageIndex'] = $pageIndex;
	    $parMap['parameter'] = $parameter;

	    $this->get('core.page')->setParam($parMap);

	    return $this->get('core.page')->show();
	}

	/**
	 * 调用php的array_sum方法
	 * @param string $str
	 * @param array $arr
	 */
	public function array_sum(array $arr)
	{
	    return array_sum($arr);
	}

	/**
	 * 调用php的array_search方法
	 * @param string $str
	 * @param array $arr
	 */
	public function array_search($str, array $arr, $strict=false)
	{
	    return array_search($str, $arr, $strict);
	}

	/**
	 *
	 * 调用php的in_array方法
	 * @param string $str
	 * @param array $arr
	 */
	public function in_array($str, array $arr)
	{
	    return in_array($str, $arr);
	}

	/**
	 *
	 * 调用php的is_array方法
	 * @param string $str
	 * @param array $arr
	 */
	public function is_array($str)
	{
	    return is_array($str);
	}

	/**
	 * 调用php的is_numeric方法
	 * @param string $str
	 */
	public function is_numeric($str)
	{
		return is_numeric($str);
	}

	/**
	 * 调用php的is_string方法
	 * @param string $str
	 */
	public function is_string($str)
	{
		return is_string($str);
	}

	/**
	 * 调用php的json_decode方法
	 * @param json $str
	 * @return mixed
	 */
	public function json_decode($str)
	{
	    $str = is_object($str)?$this->get('serializer')->normalize($str):$str;
		return json_decode($str, true);
	}

	/**
	 * 调用php的unserialize方法
	 * @param string $str
	 */
	public function unserialize($str)
	{
	    if(empty($str))
	        return '';
	    return unserialize($str);
	}
	
	/**
	 * 调用php的preg_match方法
	 * @param unknown $pattern
	 * @param unknown $subject
	 * @return array
	 */
	public function match($subject, $pattern)
	{
	    if (preg_match($pattern, $subject, $matches))
	       return $matches;
	    
	    return array();
	}

	/**
	 * 正则表达式的搜索...
	 * @param mixed $pattern
	 * @param mixed $replacement
	 * @param mixed $subject
	 * @return mixed
	 */
	public function pregReplace($pattern, $replacement, $subject)
	{
		return preg_replace($pattern, $replacement, $subject);
	}

	/**
	 * 许可检测
	 * @param string $str   url
	 */
	public function isPermission($str)
	{
	    $str = parse_url($str);
	    $request = $this->get('request');
	    $str['path'] = str_replace(array($request->getBaseUrl()),"",$str['path']);

	    $str = $str['path']?explode('/', $str['path']):array();

		$action = $str?array_pop($str):null;
		$controller = $str?array_pop($str):null;
		$bundle = $str?array_pop($str):null;

	    // //判断是否登陆
	    // if(!$this->get('core.common')->getUser())
	    //     return false;

	    //从rbac权限控制服务里面读取
	    //开始检测
	    return $this->get('core.rbac')->isGranted($action, $controller, $bundle);
	}

	/**
	 * 判断$item值是否在$str里面,如果在返回checked，否则返回空值
	 * @param string $item  要比对的对象
	 * @param string $str   要比对的对象组
	 * @return string
	 */
	public function isChecked($item, $str)
	{
	    if(!isset($item)||!isset($str))
	        return '';

	    $str = explode(",",trim($str,","));

	    if(in_array($item,$str))
	        return 'checked';
	    return '';
	}

	/**
	 * 判断$item值是否在$str里面,如果在返回Selected，否则返回空值
	 * @param string $item  要比对的对象
	 * @param string $str   要比对的对象组
	 * @param string $p1    true时返回的参数
	 * @param string $p2    false时返回的参数
	 * @return string
	 */
	public function isSelected($item, $str, $p1='selected', $p2='')
	{
	    if(!isset($item)||!isset($str))
	        return $p2;

	    $str = explode(",",trim($str,","));

	    if(in_array($item,$str)) return $p1;
	    return $p2;
	}

	/**
	 * 判断$item值是否在$str里面,如果在返回disabled，否则返回空值
	 * @param string $item		要比对的对象
	 * @param string $str		要比对的对象组
	 * @return string
	 */
	public function isDisabled($item, $str)
	{
	    if(!isset($item)||!isset($str))
	        return '';

	    $str = explode(",",trim($str,","));

	    if(in_array($item,$str))
	        return 'disabled';

	    return '';
	}

	/**
	 * 表结构
	 * @param string $tableName
	 * @param array $joinparams
	 * @param int $pageIndex
	 * @param int $pageSize
	 * @return multitype:multitype: number Ambigous <number, unknown> |multitype:multitype: number Ambigous <number, unknown> NULL
	 */
	public function tabStructure($tableName, $pageIndex=1, $pageSize=8)
	{
	    //初始化
	    $result = array();
	    $result['data'] = array();
	    $result['pageCount'] = 0;
	    $result['pageIndex'] = $pageIndex?$pageIndex:1;

	    if(empty($tableName))
	        return $result;

	    //获得表结构
	    $classMetadata = $this->get('doctrine.orm.entity_manager')->getClassMetadata($tableName);

	    $result['data'] = $classMetadata->fieldMappings;

	    return $result;
	}

	/**
	 * 存储类型
	 * @param string $name
	 */
	public function storageTypes($name=null)
	{
	    return $this->get('core.common')->storageTypes($name);
	}

	/**
	 * 从entity中取表字段
	 * @param string $str
	 */
	public function tableColumn($name)
	{
	    //默认bundle
	    $defaultBundle = $this->get('core.common')->getDefaultBundle();
	    $tableClass = $defaultBundle.":".$this->get('core.common')->ucWords($name);

	    //取表Metadata
	    $classMetadata = $this->get('doctrine.orm.entity_manager')->getClassMetadata($tableClass);

	    return $classMetadata->columnNames;
	}

	/**
	 * 加密
	 * @param string $str
	 */
	public function tripodEncode($str)
	{
	    return $this->get('core.common')->encode($str);
	}

	/**
	 * 解密
	 * @param string $str
	 */
	public function tripodDecode($str)
	{
	    return $this->get('core.common')->decode($str);
	}

    /**
     * 运算符
     * @param string $name
     */
    public function operationSymbol($name='')
    {
        return $this->get('core.common')->operationSymbol($name);
    }

    /**
     * php 的ceil
     * @param float $str
     */
    public function ceil($str)
    {
        return ceil($str);
    }

    /**
     * 根据服务获取一行数据
     * @param string $service
     */
    public function getRow($service, array $param=array(), array $pageParam=array())
    {
        $arrs = array();
    	$param = parent::getQueryParam($param);
    	$param['criteria']['findType'] = true;//强制模板标识的数据全为数组返回，不使用对象返回
        $info = $this->get($service)->findOneBy($param['criteria'], $param['order'], true);//强制调属性表
     
        if($info&&isset($pageParam['field'])&&$pageParam['field'])
        {
            /*
            if(is_object($info))
            {
                $field = "get".$this->get('core.common')->ucWords($pageParam['field']);
                $content = method_exists($info, $field)?$info->$field():"";
            }else{
                $content = $info[$pageParam['field']];
            }
             * */


            if($content = @$info[$pageParam['field']])
            {
                
                $absoluteUrl = $this->get('core.common')->ensureUrlIsAbsolute('/');
                $patterns = "/<p>#p#([\s\\S]*?)#e#<\/p>/";
                
                //正则抓取副标题
                preg_match_all($patterns, $content, $arrs);
                
                //替换成ueditor通用分页符
                $content = preg_replace($patterns," _ueditor_page_break_tag_ ",$content);
    
                //如果是手机端缩小图片尺寸
    			if($this->get('core.common')->isMobileClient())
    				$content = preg_replace('/<img/',"<img width='370'",$content);
    			
    			$content = array_filter(explode('_ueditor_page_break_tag_', $content));
            }else{
                $content = array();
            }

            //初始化
            $result = array();
            $result['data'] = $info;
            $result['pageCount'] = 0;
            $result['pageIndex'] = 1;
            $result['pageSize'] = 1;
            
            //嵌入副标题导航
            $result['pageNav'] = isset($arrs[1])?$arrs[1]:array();
            
            if(count($content)>0)
            {
                $pageIndex = isset($pageParam['pageIndex'])?(int)$pageParam['pageIndex']:1;
                $pageIndex = $pageIndex>0?$pageIndex:1;
                $pageIndex = $pageIndex>count($content)?count($content):$pageIndex;
                
                $result['pageIndex'] = $pageIndex;
                $result['pageCount'] = count($content);
                
                $info[$pageParam['field']] = $content[$pageIndex-1];
/*
                if(is_object($info))
                {
                    $field = "set".$this->get('core.common')->ucWords($pageParam['field']);
                    if(method_exists($info, $field))
                        $info->$field($content[$pageIndex-1]);
                }else
                    $info[$pageParam['field']] = $content[$pageIndex-1];
 * */
                $result['data'] = $info;
             }
//dump($result);
//exit;
             return $result;
        }
        //dump($info);
        //exit;        
        return $info;
    }

	/**
	 * 根据坐标获取相应范围
	 * @param string $service
	 */
	public function searchRange($service,$lat,$lng,$ran=10)
	{
		$around = $this->get('core.square')->returnSquarePoint($lat,$lng,$ran);

		$wherefind = array();
		$wherefind['findType'] = true;//强制模板标识的数据全为数组返回，不使用对象返回
		$wherefind['lat']['between']=array($around['right-bottom']['lat'],$around['left-top']['lat']);
		$wherefind['lng']['between']=array($around['left-top']['lng'],$around['right-bottom']['lng']);

		return $this->get($service)->findBy($wherefind);
	}

    /**
     * 根据服务获取指定字段数据
     * @param string $service   服务名
     * @param string $name      字段名
     * @param array $param      查询参数
     */
    public function getOne($service, $name, array $param=array())
    {

        //$name = "get".$this->get('core.common')->ucWords($name);
        $param = parent::getQueryParam($param);

        $param['criteria']['findType'] = true;//强制模板标识的数据全为数组返回，不使用对象返回
        $_attribute = empty($param['criteria']['_attribute']) ? false : true;
        unset($param['criteria']['_attribute']);

        $info = $this->get($service)->findOneBy($param['criteria'], $param['order'], $_attribute);  
        
        if(isset($info[$name])) {
            return $info[$name];
        }       
        //if(method_exists($info, $name))
            //return $info->$name();
        return '';
    }

    /**
     * 取总数
     * @param string $service
     * @param array $param
     */
    public function getCount($service, array $param=array())
    {
        $param = parent::getQueryParam($param);
        return $this->get($service)->count($param['criteria']);
    }

    /**
     * 根据服务获取指定字段列数据(多行)
     * @param string $service   服务名
     * @param string $name      字段名
     * @param array $param      查询参数
     */
    public function getCol($service, $name, array $param=array())
    {
        $data = array();
        //$nameStr = "get".$this->get('core.common')->ucWords($name);

        $param = parent::getQueryParam($param);
        $param['criteria']['findType'] = true;//强制模板标识的数据全为数组返回，不使用对象返回
        $info = $this->get($service)->findBy($param['criteria'], $param['order']);

        if(isset($info['data']))
        {
            foreach($info['data'] as $k => $v)
            {
                $data[$k] = isset($v[$name]) ? $v[$name] : '';
                //if(method_exists($v, $nameStr)) $data[$k] = $v->$nameStr();
            }
        }
        return $data;
    }

    /**
     * 根据服务获取数据(多行)
     * @param string $service
     */
    public function getAll($service, array $param=array())
    {
        $order = $this->get('request')->get('order','');
        if ($order)
        {
            $order = explode('|', $order);
            $param['order'] = isset($order[0])?$order[0]:($param['order']?$param['order']:'');
            $param['orderBy'] = isset($order[1])?$order[1]:($param['orderBy']?$param['orderBy']:'');
        }
    	$param = parent::getQueryParam($param);
    	$pageIndex = isset($param['criteria']['pageIndex'])?(int)$param['criteria']['pageIndex']:1;
    	$pageSize = isset($param['criteria']['pageSize'])?(int)$param['criteria']['pageSize']:8;
    	unset($param['criteria']['pageIndex']);
    	unset($param['criteria']['pageSize']);

    	$param['criteria']['findType'] = true;//强制模板标识的数据全为数组返回，不使用对象返回
    	$param['criteria']['_multi'] = isset($param['criteria']['_multi'])?$param['criteria']['_multi']:false;
        $info = $this->get($service)->findBy($param['criteria'], $param['order'], $pageSize, $pageIndex);

        unset($order);
        unset($param);

        if(empty($info))
            return $info;

        foreach($info['data'] as $key=>$item)
        {
            if(is_object($item) && method_exists($item, 'getUrlParams'))
                $item->setUrlParams($this->get('core.common')->getQueryParam($item->getUrlParams()));
            else if(is_array($item) && isset($item['urlparams']))
                $item['urlparams'] = $this->get('core.common')->getQueryParam($item['urlparams']);
            elseif(is_array($item))
                $item['urlparams'] = array();

            $info['data'][$key] = $item;
        }

        return $info;
    }

    /**
     * 根据服务获取主站和当前分站数据(多行)
     * @param string $service
     */
    public function getAllNews($service, array $param=array())
    {
        $order = $this->get('request')->get('order','');
        if ($order)
        {
            $order = explode('|', $order);
            $param['order'] = isset($order[0])?$order[0]:($param['order']?$param['order']:'');
            $param['orderBy'] = isset($order[1])?$order[1]:($param['orderBy']?$param['orderBy']:'');
        }
        $param = parent::getQueryParam($param);
        $area = $_SESSION['_sf2_attributes']['area'];
        $param['criteria']['area'] = ['in'=>"0,{$area}"];
        $pageIndex = isset($param['criteria']['pageIndex'])?(int)$param['criteria']['pageIndex']:1;
        $pageSize = isset($param['criteria']['pageSize'])?(int)$param['criteria']['pageSize']:8;
        unset($param['criteria']['pageIndex']);
        unset($param['criteria']['pageSize']);

        $param['criteria']['findType'] = true;//强制模板标识的数据全为数组返回，不使用对象返回
        $param['criteria']['_multi'] = isset($param['criteria']['_multi'])?$param['criteria']['_multi']:false;
        $info = $this->get($service)->findBy($param['criteria'], $param['order'], $pageSize, $pageIndex);

        unset($order);
        unset($param);

        if(empty($info))
            return $info;

        foreach($info['data'] as $key=>$item)
        {
            if(is_object($item) && method_exists($item, 'getUrlParams'))
                $item->setUrlParams($this->get('core.common')->getQueryParam($item->getUrlParams()));
            else if(is_array($item) && isset($item['urlparams']))
                $item['urlparams'] = $this->get('core.common')->getQueryParam($item['urlparams']);
            elseif(is_array($item))
                $item['urlparams'] = array();

            $info['data'][$key] = $item;
        }

        return $info;
    }

    /**
     * 二叉树子查询
     * @param string $service
     */
    public function getNodeSearch($service, array $param=array())
    {
        $param['findType'] = true;//强制模板标识的数据全为数组返回，不使用对象返回
        $info = $this->get($service)->getNode($param);
        $info['data'] = $this->get('core.common')->getTree((isset($info['data'])&&is_array($info['data']))?$info['data']:array());
        return $info;
    }

    public function getNodeById($service, $id, $params=array())
    {
        $param['findType'] = true;//强制模板标识的数据全为数组返回，不使用对象返回
		$info = $this->get($service)->getNodeById($id, $params);
        $info = $this->get('core.common')->getTree(isset($info['data'])?$info['data']:array());
        return $info?end($info):array();
    }

    /**
     * 二叉树叶子查询
     * @param string $service
     */
    public function getTreeSearch($service, array $master=array(), array $slave=array(), array $param=array())
    {
        $param['findType'] = true;//强制模板标识的数据全为数组返回，不使用对象返回
        $param['query'] = $this->get('core.common')->getQueryArray($master);
        $param['query1'] = $this->get('core.common')->getQueryArray($slave);
        return $this->get($service)->getBinaryTreeSearch($param);
    }

    /**
     * 获限树结构数据
     * @param string $service
     * @param array $param
     * @param array $ext 当$ext['type']='array';nodes: {1: {a}}，否则时  nodes: {{a}}
     * @return array
     */
    public function getTree($service, array $param=array(), array $ext=array())
    {
        $param = parent::getQueryParam($param);

        $pageIndex = isset($param['criteria']['pageIndex'])?$param['criteria']['pageIndex']:1;
        $pageSize = isset($param['criteria']['pageSize'])?$param['criteria']['pageSize']:100;
        $limit = isset($param['criteria']['limit'])?$param['criteria']['limit']:2000;

        unset($param['criteria']['pageIndex']);
        unset($param['criteria']['pageSize']);

        $param['criteria']['findType'] = 1;//强制模板标识的数据全为数组返回，不使用对象返回
        $info = $this->get($service)->findBy($param['criteria'], $param['order'], $limit, 1);

        if(!isset($info['data']))
            return array();
        
        if (isset($ext['type']) && $ext['type']=='array')
        {
            $tmp = array();
            // getTree() 第五个参数为true
            $info['data'] = $this->get('core.common')->getTree($info['data'],0,$tmp,true,true);
        } else 
            $info['data'] = $this->get('core.common')->getTree($info['data'],0);
        $info['pageCount'] = count($info['data']);
        $info['pageSize'] = $pageSize;
        $info['data'] = $this->get('core.common')->pagination($pageIndex, $pageSize, $info['data']);

        return $info;
    }

    /**
     * 获取角色参数
     * @param array $param
     * @return multitype:
     */
    public function getRule(array $param=array())
    {
        //判断是否登陆
        if(!($user = $this->get('core.common')->getUser())) {
            return array();
        }

        $param = parent::getQueryParam($param);
        $param['findType'] = true;//强制模板标识的数据全为数组返回，不使用对象返回
        /*
         *  取消只列出当前会员权限内节点的限制。目前本方法只用于角色配置界面，只要具备角色配置权限，则可全盘配置。        
        $mid = method_exists($user, 'getMid')?(int)$user->getMid():0;

        if($mid!=1)
        {
            $rules = $user->getRules();
            $param['criteria']['id']['in'] = array_keys($rules);
        }
         **/
        return $this->get('db.auth_rule')->findBy($param['criteria'], $param['order']);
    }

    /**
     * 获取表单指定字段属性
     * @param string $name      表单名称
     * @param string $fieldName 字段名称
     * @return array()
     */
    public function getFormField($name, $fieldName)
    {
    	$data = array();
        $modelForm = $this->get('db.model_form')->findOneBy(array('name'=>$name));

        if(!is_object($modelForm))
        	return $data;

        $map = array();
        $map['model_form_id'] = $modelForm->getId();
		$map['name'] = $fieldName;

        $modelFormAttr = $this->get('db.model_form_attr')->findOneBy($map);

		if(!is_object($modelFormAttr))
			return $data;
        $choices = $modelFormAttr->getChoices();

        if(empty($choices))
        	return $data;

        $info = $this->get('core.common')->getQueryParam($choices);

        foreach($info as $k=>$v)
        {
			$data[$k]['name'] = $v;
			$data[$k]['id'] = $k;
        }

        return $data;
    }

    /**
     * 获取模型指定字段属性
     * @param string $name      模型服务名称如：db.wxusers
     * @param string $fieldName 字段名称
     * @return array()
     */
    public function getField($name, $fieldName)
    {
		$data = array();
        $models = $this->get('db.models')->findOneBy(array('service_name'=>$name));

        if(!is_object($models))
        	return $data;

        $map = array();
        $map['model_id'] = $models->getId();
		$map['name'] = $fieldName;

        $modelAttr = $this->get('db.model_attribute')->findOneBy($map);

		if(!is_object($modelAttr))
			return $data;

        $extra = $modelAttr->getExtra();

        if(empty($extra))
        	return $data;

        $info = $this->get('core.common')->getQueryParam($extra);

        foreach($info as $k=>$v)
        {
			$data[$k]['title'] = $v;
			$data[$k]['value'] = $k;
        }

        return $data;
    }

    /**
     * 数组交集
     * @param array $p1
     * @param array $p2
     */
    public function arrayIntersectKey(array $p1, array $p2)
	{
		foreach(array_keys($p1) as $k)
		{
			if(!isset($p2[$k]))
				unset($p1[$k]);
			else
				$p1[$k] = $p2[$k];
		}
		return $p1;
	}

	public function unSerializer($data)
	{
	    return $this->get('serializer')->normalize($data);
	}

	public function badKeywords($data)
	{
		$kwrepa = $kwbada = array();
		$badkws = $this->get('db.badkeywords')->findBy(array('_multi' => false));
		foreach ($badkws['data'] as $value) {
			$kwbad = $value->getKwbad();
			$kwrep = $value->getKwrep();
			//echo "\n<br>$kwbad,$kwrep";
			if(empty($kwbad) || empty($kwrep)) continue;
			$kwbada[] = '/'.preg_replace("/\\\\{(\\d+)\\\\}/", ".{0,\\1}", preg_quote($kwbad,'/')).'/i';
			$kwrepa[] = $kwrep;
		} //dump($kwbada,$kwrepa);
		$data = preg_replace($kwbada,$kwrepa,$data);
		return $data;
	}

	/**
	 * 热门关键词 替换成a链接
	 */
	public function hotKeywords($source)
	{
		$kwhota = $kwurla = array();
		$hotkws = $this->get('db.hotkeywords')->getData();
		foreach ($hotkws as $value)
		{
			$kwhot = $value['keyword'];
			$kwurl = $value['url'];

			if(empty($kwhot))
			    continue;

			$kwhota[] = '/'.$kwhot.'/i';

			if($kwurl)
			    $kwurla[] = "<a href='".$kwurl."' class='p_wordlink' target='_blank'>".$kwhot."</a>";
			else
			    $kwurla[] = "<span class='p_wordlink'>".$kwhot."</span>";
		}

		if(!empty($kwhota))
		{
		    $matchs = array();
			if(preg_match_all("/<.*?>/s", $source, $matchs))
			{
				$matchs = array_unique($matchs[0]);
				foreach($matchs as $k => $v) $source = str_replace($v,":::$k:::", $source);
				$source = preg_replace($kwhota,$kwurla,$source, 1);
				$source = preg_replace_callback("/:::(\\d+):::/s", function($match)use($matchs){
				    return $matchs[$match[1]];
				}, $source);
// 				$source = preg_replace("/:::(\\d+):::/se", '$matchs[$1]', $source);
			}
		}

		return $source;
	}

    /**
     * 去掉图片宽高属性
     */
	public function noImgwh($data)
	{
		$reg = "/(<img.*?)(src=[\"|']?([^\"|\']{1,255})[\"|']?).*?([^>]+>)/is";
		$data = preg_replace($reg,'<img $2 />', $data);
	    return $data;
	}


	public function targetImg($obj)
	{
	    //判断是否为json数据
	    if(is_null(json_decode($obj)))
	        return explode(',', $obj);

	    $obj = json_decode($obj, true);

	    $info = array();

	    foreach($obj as $v)
	    {
	        if(!isset($v['target'])||empty($v['target']))
	            continue;
	        $info[] = $v['target'];
	    }

	    return $info;
	}

	public function sourceImg($obj)
	{
	    //判断是否为json数据
	    if(is_null(json_decode($obj)))
	        return array();

	    return json_decode($obj, true);
	}
	
	/**
	 * 置顶
	 * @param string $service
	 * @param string $str
	 * @param array $params
	 */
	public function getTop($service, array $params=array())
	{
	    
	}
	
	public function getPush($str, $pageSize=8)
	{
	    
	    $params = array();
	    $params['checked'] = 1;
	    $params['pageIndex'] = 1;
	    $params['pageSize'] = $pageSize;
	    $params['_multi'] = false;
	    
	    $strArr = array();
	    $strArr['ename'] = $str;
	    
	    $info = self::getBlockView('db.pushs', $strArr, $params);
	    
	    return isset($info[$str])?$info[$str]:array();
	}
	
	public function getPushMuti($str, $pageSize=8)
	{
	    $params = array();
	    $params['checked'] = 1;
	    $params['pageIndex'] = 1;
	    $params['pageSize'] = $pageSize;
	    $params['_multi'] = false;
	    $str = explode(',', $str);
	    $strArr = array();

	    foreach($str as $item)
	    {
	        $strArr['ename']['orX'][]['ename'] = $item;
	    }

	    $info = self::getBlockView('db.pushs', $strArr, $params);
	     
	    return $info;
	}
	
	/**
	 * 创建视图
	 * @param string $service
	 */
	public function createView($service)
	{
	    $paramt = "is_delete=0";
	    $tableSchema = $this->get('core.common')->C('database_name');
	    $tableName = $this->get($service)->getTableName(true);
	    $this->metadata = $this->get($service)->getClassMetadata(null, true);
	    $stag = $tableSchema."_".$tableName."_view";

	    if($this->metadata->hasField('start_time')|| $this->metadata->hasAssociation('start_time'))
	        $paramt .= " and start_time<= curdate()";
	    
	    if($this->metadata->hasField('end_time')|| $this->metadata->hasAssociation('end_time'))
	        $paramt .= " and end_time>= curdate()";
	    
	    $viewCount = (int)$this->get('core.common')->S($stag);

	    
	    if($viewCount<=0)
	    {
	        //检测视图是否存在
	        $SQL = "SELECT count(1) FROM information_schema.tables WHERE TABLE_SCHEMA = '".$tableSchema."' AND TABLE_NAME='".$tableName."_view' AND TABLE_TYPE = 'VIEW'";
	        $connection = $this->get('core.table_manage')->getConnection();
	        $stmt = $connection->prepare($SQL);
	        $stmt->execute();
	        $viewCount = (int)$stmt->fetchColumn();
            $this->get('core.common')->S($stag, $viewCount, 86400);
	    }

	    //创建视图,视图名=原表名+_view
	    if((int)$viewCount<=0)
	    {
	        $SQL = "CREATE or REPLACE VIEW `".$tableName."_view` AS SELECT * FROM {$tableName} WHERE {$paramt}";
	        $stmt = $connection->prepare($SQL);
	        $stmt->execute();
	    }
	    
	    return $tableName."_view";
	}
	
	/**
	 * 创建存储
	 * @param string $service
	 */
	public function createProcedure($service)
	{
	    $tableSchema = $this->get('core.common')->C('database_name');
	    //$tableName = $this->get($service)->getTableName(true);
	    $this->metadata = $this->get($service)->getClassMetadata(null, true);
	    
	    $tableName = "get_push_name";

	    $stag = $tableSchema."_".$tableName;
	    
	    $procedureCount = (int)$this->get('core.common')->S($stag);

	    
	    if($procedureCount<=0)
	    {
	        //检测过程是否存在
	        $SQL = "SELECT count(1) FROM  mysql.proc WHERE db = '".$tableSchema."' AND name='{$tableName}' AND type ='PROCEDURE'";
	       
	        $connection = $this->get('core.table_manage')->getConnection();
	        $stmt = $connection->prepare($SQL);
	        $stmt->execute();
	        $procedureCount = (int)$stmt->fetchColumn();

	        $this->get('core.common')->S($stag, $procedureCount, 86400);
	    }
	    
	    //创建过程,过程名=原表名+_procedure
	    if((int)$procedureCount<=0)
	    {
	        $SQL = "
                create procedure {$tableName}(IN `p1` varchar(100),IN `p2` varchar(200))
BEGIN
  set @id = `p2`; 
  set @table_name = `p1`;
  SET @sql_create_table_1 = 'SELECT * FROM ';
  SET @sql_create_table_2 = ' WHERE id in (';
  set @sql_create_table_3 = ')';
  set @sql_create_table = concat(@sql_create_table_1, @table_name, @sql_create_table_2,@id, @sql_create_table_3);
  PREPARE stmt FROM @sql_create_table;
  EXECUTE stmt;
  deallocate prepare stmt;
END";
	        $stmt = $connection->prepare($SQL);
	        $stmt->execute();
	    }
	    
	    return $tableName;
	}
	
	/**
	 * 读推送视图
	 */
	public function getBlockView($service, $str, array $params=array())
	{
	    $info = array();
	    
	    $sResult = array();
	    
	    $params['start_time']['lte'] = date('Y-m-d');
	    $params['end_time']['gte'] = date('Y-m-d');

	    //创建视图
	    //$viewTable = self::createView($service);
	    
	    //创建过程
	    //$procedureTable = self::createProcedure($service);
	    
	    //if(empty($viewTable)||empty($procedureTable))
	    //    return $info;
	    
	    //赋值元数据
	    $this->metadata = is_object($this->metadata)?$this->metadata:$this->get($service)->getClassMetadata(null, true);

	    if(is_array($str))
	        $map = $str;
	    else
	        $map['ename'] = $str;
	    
	    $map['_multi'] = false;
	    
	    $catePushs = $this->get('db.cate_pushs')->findBy($map);

	    if(empty($catePushs['data']))
	        return $info;

	    $cateList = array();
	    $cateListItem = array();

	    foreach($catePushs['data'] as $item)
	    {
	        $urlparams = method_exists($item, 'getUrlparams')&&$item->getUrlparams() ? $this->get('core.common')->getQueryParam($item->getUrlparams()) : array();
	        $urlparams = array_merge($params, $urlparams);
	        $urlparams['cate_pushs'] = $item->getEname();
	        $fromData = (int)$item->getFromData();
	        $cateList[$fromData][$item->getEname()] = $urlparams;
	        $cateListItem[$item->getEname()] = $item;
        }

	    foreach($cateList as $key=>$items)
	    {
	        foreach($items as $k=>$item)
	        {
	            //0为普通推送位,1为基于模型的推送位
	            switch($key)
	            {
	                case 0:
	                    $info[$k] = self::_pushHandleView($service, $item);
	                    break;
	                case 1:
	                    $info[$k] = self::_modelHandleView($service, $cateListItem[$k],$item);
	                    break;
	            }
	        }
	    }

	    if(!isset($this->pushId['sid']))
	        return $info;

	    $map = array();
	    $map['_multi'] = false;
	    $map['area'] = '_isnull';
	    $map['findType'] = 1;

        foreach($this->pushId['sid'] as $model=>$inArr)
        {
            $inArr = array_flip($inArr);
            $inArr = array_flip($inArr);
            if(!isset($this->modelList[$model]))
                continue;

            $map['id']['in'] = $inArr;

            $sInfo = $this->get($this->modelList[$model]['service'])->findBy($map);

            if(!isset($sInfo['data']))
                continue;

            foreach($sInfo['data'] as $item)
            {
                $sResult[$model][$item['id']] = $item;
            }
        }
	    
	    if(!isset($this->pushId['sname']))
	        return $info;
	    
	    foreach($this->pushId['sname'] as $keys=>$items)
	    {
	        foreach($items as $key=>$item)
	        {
	            foreach($item as $ik=>$fromid)
	            {
	                if(!isset($info[$keys][$ik]))
	                    continue;

	                if(!isset($sResult[$info[$keys][$ik]['models']][$fromid]))
	                    continue;
	                
	                foreach($info[$keys][$ik] as $kkkk=>$vvvv)
	                {
	                    if(empty($vvvv)&&isset($sResult[$info[$keys][$ik]['models']][$fromid][$kkkk]))
	                        unset($info[$keys][$ik][$kkkk]);
	                }
	                
	                $info[$keys][$ik] += $sResult[$info[$keys][$ik]['models']][$fromid];
	            }	            
	        }
	    }

	    unset($map);
	    unset($inArr);
	    unset($items);
	    unset($sInfo);
	    unset($sResult);
	    unset($cateList);
	    unset($this->pushId);

	    return $info;
	}

	/**
	 * 基于推送位推送的处理
	 */
	private function _pushHandleView($service, $criteria, $attributes=0)
	{
	    $criteria['findType'] = 1;

	    $order = array();
	    $order['sort_fixed'] = 'desc';
	    $order['sort'] = 'asc';
	    $order['create_time'] = 'desc';

	    $result = $this->get($service)->findBy($criteria, $order, $criteria['pageSize'], $criteria['pageIndex']);
	    $result = isset($result['data'])?$result['data']:array();
	    if($result)
	    {
	        //固位排序
    	    if ($this->metadata->hasField('sort_fixed') || $this->metadata->hasAssociation('sort_fixed'))
    	        $result = $this->get('core.common')->array_sortfixed($result, 'sort_fixed');

    	    $pushData = array();

    	    foreach($result as $key=>&$item)
    	    {

    	        if(isset($item['models'])&&$item['models'])
    	        {
    	            $this->modelList[$item['models']] = isset($this->modelList[$item['models']])?$this->modelList[$item['models']]:$this->get('db.models')->getData($item['models'],'name');
    	            
    	            if(isset($item['fromid'])&&(int)$item['fromid']>0)
    	            {
    	                $this->pushId['sid'][$item['models']][] = (int)$item['fromid'];
    	                $this->pushId['sname'][$item['cate_pushs']][$item['models']][$key] = (int)$item['fromid'];
    	            }
    	        }

    	        if(isset($this->modelList[$item['models']]['type'])&&$this->modelList[$item['models']]['type']==1)
    	        {
    	        	$item['urlparams'] = array();
    	            $item['urlparams']['id'] = $item['fromid'];
    	            $item['url'] = $this->get('core.common')->U(isset($item['url'])&&$item['url']?$item['url']:$item['models']."/detail",$item['urlparams']);
    	        }else{
    	            if($item['fromid']>0)
    	                $item['urlparams']['category'] = $item['fromid'];
    	            $item['url'] = $this->get('core.common')->U(isset($item['url'])&&$item['url']?$item['url']:$item['models']."/index",$item['urlparams']);
    	        }
    	    }
	    }

	    unset($item);
	    unset($pushData);	    
	    unset($criteria);

	    return $result;
	}
	
	/**
	 * 基于模型推送的处理
	 */
	private function _modelHandleView($service, $catePush, $params, $attributes=0)
	{
        try {
            $models = explode(',', $catePush->getModels());
            
            $this->get('core.table_manage')->setTables($this->get('cc')->prefixEntityName($models[0]));

            $info = $this->get('core.table_manage')->getUNIONSearch($models, $params, array());

            if(isset($info['data']))
            {
                foreach($info['data'] as &$v)
                {
                    $v['urlparams'] = isset($v['urlparams'])?$this->get('core.common')->getQueryParam($v['urlparams']):array();

                    $v['url'] = '';

                    if(!isset($v['models'])||empty($v['models']))
                        continue;

                    $fromid = $this->get('core.common')->encode($v['models'].','.(int)$v['id']);

                    $v['url'] = "detail/{$fromid}";

                    if (method_exists($catePush, 'getUrl') && $catePush->getUrl()!='')
                        $v['url'] = $this->get('core.common')->U($catePush->getUrl(), array_merge($v['urlparams'],array('id'=>$v['id'])));
                    else
                        $v['url'] = $this->get('core.common')->U("{$v['models']}/detail", array_merge($v['urlparams'],array('id'=>$v['id'])));
                }
            }

            return isset($info['data'])?$info['data']:array();
        } catch (\Exception $e) {
            throw new \LogicException("HouseExtension:[".$e->getMessage()."]", false);
        }
	}

	/**
	 * 区块
	 * @param string $service
	 * @param string $str
	 * @param array $params
	 */
	public function getBlock($service, $str, array $params=array(), array $params1=array())
	{
	    //默认禁用数据库统计
	    $params['_multi'] = isset($params['_multi'])?(bool)$params['_multi']:false;
	    
	    $params = array_merge(array(
	        'pageIndex' => 1,
	        'pageSize' => 8,
	        'groupBy' =>''
	    ), $params);

	    $map = array();
	    $info = array();
	    $fromData = 0;

	    if(is_array($str))
	        $map = $this->get('core.common')->getQueryParam($str);
	    else
	        $map['ename'] = $str;

	    $map['_multi'] = $params['_multi'];
	    $map['pageSize'] = isset($map['pageSize'])?(int)$map['pageSize']:$params['pageSize'];
	    $attributes = isset($map['attributes'])?1:0;
	    
	    unset($map['attributes']);

	    $info['data'] = array();
	    $info['pageIndex'] = $params['pageIndex'];
	    $info['pageSize'] = $params['pageSize'];
	    $info['pageCount'] = 0;
	    
	    $params1['_multi'] = $params['_multi'];

	    $params = parent::getQueryParam($params);
	    $params1 = parent::getQueryParam($params1);

	    $cate_pushs = $this->get('db.cate_pushs')->findBy($map);

	    if(!isset($cate_pushs['data'])||empty($cate_pushs['data']))
	        return $info;

	    $catePushs = array();
	    
	    foreach($cate_pushs['data'] as $item)
	    {
	        if(!method_exists($item, 'getFromData'))
	            continue;

	        if(method_exists($item, 'getEname'))
	            $catePushs[] = $item->getEname();
	        	//$params['criteria']['cate_pushs'] = $item->getEname();
	            //$params['criteria']['cate_pushs']['orX'][]['cate_pushs']['eq'] = $item->getEname();

            if(method_exists($item, 'getUrlparams')){
                $urlparams = $item->getUrlparams() ? $this->get('core.common')->getQueryParam($item->getUrlparams()) : array();
                $params['criteria'] = array_merge($params['criteria'], $urlparams);
            }

	        $fromData = !isset($params['criteria']['from_data'])?(int)$item->getFromData():(int)$params['criteria']['from_data'];
	    }
	    
	    if(count($catePushs)==0)
	    {
	        $params['criteria']['cate_pushs'] = end($catePushs);
	    }else{
	        foreach($catePushs as $item)
	        {
	            $params['criteria']['cate_pushs']['orX'][]['cate_pushs']['eq'] = $item;
	        }
	    }

	    switch($fromData)
	    {
	        //推送位
	        case 0:
	            if(!isset($params['criteria']['from_data'])&&!isset($params1['criteria']['timeflag']))
	            {
	            	// unset($params['criteria']['checked']);
	                $params['criteria']['start_time']['lte'] = date('Y-m-d');
	                $params['criteria']['end_time']['gte'] = date('Y-m-d');
	                //$params['criteria']['end_time']['orX'][]['end_time']['eq'] = "1970-01-01";
	            }

	            unset($params['criteria']['from_data']);

	            //资讯类需要显示主站和分站的推送
                if ($str['ename'] === "zhugezixun" || $str['ename'] === "fczx" || $str['ename'] === "zg_rhzx" ){
                    $params1['criteria']['area'] = ["in" => ["{$_SESSION['_sf2_attributes']['area']}",'0']];
                }

	            return self::_pushHandle($service, $info, $params, $params1, $attributes);

	            break;
	        //模型数据
	        case 1:
	            return self::_modelHandle($service, $cate_pushs['data'], $params, $params1, $attributes);
	            break;
	    }

	    return $info;
	}

	/**
	 * 基于推送位推送的处理
	 */
	private function _pushHandle($service, $info, $params, $params1, $attributes=0)
	{
	    $_service = $service;
	    $params['criteria']['findType'] = 1;//强制模板标识的数据全为数组返回，不使用对象返回
	    $params['order']['sort_fixed'] = 'desc';
	    $params['order']['sort'] = 'asc';
	    $params['order']['create_time'] = 'desc';
	    
	    if (isset($params['criteria']['fields']))
	    {
	        $fields = explode(',',$params['criteria']['fields']);
	        unset($params['criteria']['fields']);
	    }

	    $pushs = $this->get($service)->findBy($params['criteria'], $params['order'], $params['criteria']['pageSize'], $params['criteria']['pageIndex']);

	    $info['pageCount'] = isset($pushs['pageCount'])?$pushs['pageCount']:0;

	    if(isset($pushs['data']))
	    {
	        //固位排序
	        $metadata = $this->get($service)->getClassMetadata();
	         
	        if(isset($metadata->column['sort_fixed']))
	            $pushs['data'] = $this->get('core.common')->array_sortfixed($pushs['data'], 'sort_fixed');
	         
	        $models = array();
	        $models1 = array();
	        $fromid = array();

	        //获取模型数据
	        foreach($pushs['data'] as $key=>$item)
	        {
	            $pushs['data'][$key]['url'] = isset($item['url'])?$item['url']:'';
	            $pushs['data'][$key]['urlparams'] = isset($item['urlparams'])?$this->get('core.common')->getQueryParam($item['urlparams']):array();

	            if(isset($item['models'])&&$item['models'])
	            {
	                if(!isset($models[$item['models']]))
	                    $models[$item['models']] = $this->get('db.models')->getData($item['models'],'name');
	                
	                if(empty($models[$item['models']]))
	                    continue;
	                
	                if(isset($item['fromid'])&&(int)$item['fromid']>0)
	                    $fromid[$models[$item['models']]['service']][] = $item['fromid'];
	            }
	        }

	        foreach($fromid as $service=>$ids)
	        {
	            //反转去重
	            $ids = array_flip($ids);
	            $ids = array_flip($ids);
	            
	            $map = array();
	            $map['id']['in'] = $ids;
	            $map['findType'] = 1;
	            $map['pageSize'] = $params['criteria']['pageSize'];

	            //合并查询参数
	            $map = $map+$params1['criteria'];

	            //查询推送源
	            $mInfo = $this->get($service)->findBy($map);

	            if(!isset($mInfo['data'])||empty($mInfo['data']))
	                continue;
	            
	            foreach($mInfo['data'] as $key=>$val)
	            {
	                $models1[$service][$val['id']] = $val;
	                
	                $models1[$service][$val['id']]['name'] = isset($models1[$service][$val['id']]['name'])?$models1[$service][$val['id']]['name']:(isset($models1[$service][$val['id']]['nicename'])&&$models1[$service][$val['id']]['nicename']?$models1[$service][$val['id']]['nicename']:(isset($models1[$service][$val['id']]['username'])?$models1[$service][$val['id']]['username']:""));
	            }

	            
	            unset($mInfo);
	        }

	        $empData = array();
	        foreach($pushs['data'] as $key=>$item)
	        {
	            if(isset($item['fromid'])&&isset($item['models'])&&$item['models']&&isset($models[$item['models']]['service'])&&isset($models1[$models[$item['models']]['service']][$item['fromid']]))
	            {
	                $service = $models[$item['models']]['service'];

    	            foreach($item as $kkk=>$vvv)
    	            {
    	                if($vvv===''&&isset($models1[$models[$item['models']]['service']][$item['fromid']][$kkk]))
    	                    unset($item[$kkk]);
    	            }

    	            if(isset($models[$item['models']]['type'])&&$models[$item['models']]['type']==1)
    	            {
    	                $item['urlparams']['id'] = $item['fromid'];
    	                $item['url'] = $this->get('core.common')->U(isset($item['url'])&&$item['url']?$item['url']:$item['models']."/detail",$item['urlparams']);
    	            }else{
    	                if($item['fromid']>0)
    	                    $item['urlparams']['category'] = $item['fromid'];
    	                $item['url'] = $this->get('core.common')->U(isset($item['url'])&&$item['url']?$item['url']:$item['models']."/index",$item['urlparams']);
    	            }
	            }else{
	                $service = isset($models[$item['models']]['service'])?$models[$item['models']]['service']:$_service;

	                if(isset($models[$item['models']]['type'])&&$models[$item['models']]['type']==1)
	                {
	                    if(isset($item['fromid'])&&$item['fromid']>0)
	                        $item['urlparams']['id'] = $item['fromid'];
	                    $item['url'] = $this->get('core.common')->U(isset($item['url'])&&$item['url']?$item['url']:$item['models']."/detail",$item['urlparams']);
	                }else{
	                    if(isset($item['fromid'])&&$item['fromid']>0)
	                        $item['urlparams']['category'] = $item['fromid'];
	                    $item['url'] = $this->get('core.common')->U(isset($item['url'])&&$item['url']?$item['url']:$item['models']."/index",$item['urlparams']);
	                }
	            }

	            if(isset($item['fromid'])&&isset($models1[$service][$item['fromid']])){
	                unset($models1[$service][$item['fromid']]['id']);
	                unset($models1[$service][$item['fromid']]['fromid']);
	                unset($models1[$service][$item['fromid']]['checked']);
	                unset($models1[$service][$item['fromid']]['start_time']);
	                unset($models1[$service][$item['fromid']]['end_time']);
	                unset($models1[$service][$item['fromid']]['create_time']);
	                $info['data'][$key] = array_merge($models1[$service][$item['fromid']], $item);
	            }else{
	                if(isset($item['fromid'])&&(int)$item['fromid']>0)
	                    $empData[$item['id']] = $item['id'];
	                else
	                    $info['data'][$key] = $item;
	            }
	        }

	        //清除已经失效的推送
	        if($empData)
	            $this->get($_service)->dbalDelete(array('id'=>array('in'=>$empData)));

	        unset($item);
	        unset($models);
	        unset($models1);
	        unset($fromid);
	        unset($pushs);
	        unset($params);
	        unset($params1);
	        unset($_service);
	    }

	    return $info;
	}

	/**
	 * 基于模型推送的处理
	 */
	private function _modelHandle($service, $cate_pushs, $params, $params1, $attributes=0)
	{
	    $params['order']['create_time'] = isset($params['order']['create_time'])?$params['order']['create_time']:'asc';
        foreach ($cate_pushs as $cate_push)
        {
    	    try {
    	        $models = explode(',', $cate_push->getModels());

    	        $info = $this->get('core.table_manage')->getUNIONSearch($models, $params, $params1);

    	        if(isset($info['data']))
    	        {
    	            foreach($info['data'] as &$v)
    	            {
    	                $v['urlparams'] = isset($v['urlparams'])?$this->get('core.common')->getQueryParam($v['urlparams']):array();

    	                $v['url'] = '';

    	                if(!isset($v['models'])||empty($v['models']))
    	                    continue;

    	                $fromid = $this->get('core.common')->encode($v['models'].','.(int)$v['id']);

    	                $v['url'] = "detail/{$fromid}";

    	                $v['url'] = $this->get('core.common')->U("detail/{$fromid}", $v['urlparams']);
    	            }
    	        }

    	        return $info;
    	    } catch (\Exception $e) {
    	        throw new \LogicException("HouseExtension:[".$e->getMessage()."]", false);
    	    }
        }
	}

	/**
	 * 获取某个子节点及其所有父节点
	 * @param string $service
	 * @param int $id
	 */
	public function getParents($service, $id)
	{
	    //?? $param['findType'] = true;//强制模板标识的数据全为数组返回，不使用对象返回
	    return $this->get($service)->getParent($id, array(), array('left_node'=>'ASC'));
	}

	public function newPath($name)
	{
	    $match = $this->get('router')->getRouteCollection()->get($name);

	    if(empty($match))
	        return "";

	    return $match->getPath();
	}
	
	/**
	 * 获取用户附属等级
	 * @param int $uid
	 * @param int $usertype
	 */
	public function getMemAttach($uid, $usertype)
	{
	    //查询组分类表
	    $memTypes = $this->get('db.mem_types')->findOneBy(array('id'=>$usertype));
	    
	    //计算
	    $this->get('core.rbac')->getAttachGroup($uid,is_object($memTypes)?$memTypes->getAttach():'');
	    
	    return $this->get('core.rbac')->getAttachRoles();
	}
	
	/**
	 * 获取mconfig的值
	 * @param string $ename
	 */
	public function getMconfig($ename, $name=null)
	{
	    $map = array();
	    $map['ename'] = $ename;
	    $info = $this->get('db.mconfig')->getData($map);
	    
	    return $name?(isset($info[$name])?$info[$name]:''):$info;
	}
	
	/**
	 * 从文件库中读取分类表数据
	 * @param string $ename
	 */
	public function getCategory($ename)
	{
	    $map = array();
	    $map['ename'] = $ename;

	    return $this->get('db.category')->getData($map);
	}
	
	/**
	 * 
	 * @param int $id
	 */
	public function getCategoryById($id)
	{
	    $map = array();
	    $map['id'] = $id;
	
	    return $this->get('db.category')->getDataById($map);
	}
	
	public function getPush2($name, $pageSize=5)
	{
	    $map = array();
	    $map['area'] = (int)$this->get('core.area')->getArea();
	    $map['name'] = $name;
	    $map['pageSize'] = $pageSize;
	    
	    return $this->get('db.pushs')->getData($map);
	}
	
	public function getAreaName($areaId)
	{
	    $map = array();
	    $map['id'] = (int)$areaId;
	    return $this->get('db.area')->getData($map);
	}

	public function isMobile($tel)
	{
	    return $this->get('core.common')->isMobileClient($tel);
	}
	
	public function getWxConfig()
	{
	    return $this->get('core.wxshare')->getSignPackage();
	}
	
	/**
	 * 获取当前用户剩余免费刷新次数
	 * @throws \LogicException
	 */
	public function getRefreshs()
	{
	    $user = $this->get('core.common')->getUser();
	    
	    if(!is_object($user)||!is_object($user->getUserinfo()))
	        throw new \LogicException("请重新登陆!");
	    
	    $userinfo = $user->getUserinfo();
	    
	    //每日可刷新总数量
	    $totalCount = 0;
	    if (strtotime(date('Y-m-d',$userinfo->getDuedate())) > time())
	    {
	        $totalCount = $userinfo->getRefreshs();
	    } else {
	        $map = array();
	        $map['area'] = (int)$this->get('core.area')->getArea();
	        $map['name'] = 'frefresh';
	        $result = $this->get('db.mconfig')->findOneBy($map, array(), false);
	        $totalCount = is_object($result)?(int)$result->getValue():0;
	    }
	    
	    //查询日志表
	    $map = array();
	    $map['uid'] = $user->getId();
	    $map['type'] = 'refresh';
	    $map['create_time']['gt'] = strtotime(date('Y-m-d'));
	    $count = $this->get('db.system_log')->count($map);
	    
	    return $totalCount-$count>0?$totalCount-$count:0;
	}
	
	/**
	 * 输出array字符串格式
	 * @param string $option
	 */
	public function showarray($option)
	{
	    return 'aa';
	    if(!is_array($option))
	        return $option;
	    
	    $str = "";
	    foreach($option as $k=>$v)
	    {
	        $str .= $k."=>".$v.",";
	    }
	    
	    return $str;
	}
	
	/**
	 * 对象转成数组
	 * @param unknown $obj   转换对象
	 * @param array $arr     指定的字段
	 * @param boolean $flag  true为获取指定字段，false为排除指定字段
	 */
	public function toArray($obj,array $arr=array(), $flag=true)
	{
	    $result = $this->get('serializer')->normalize($obj);
	    
	    if (empty($arr))
	        return $result;

	    if ($flag)
	    {
	       $temp = array();
	        // 遍历获取指定字段并存入 $temp
	        foreach ($result as $k=>$item)
	        {
    	        foreach ($arr as $v)
    	        {
    	            if (isset($item[$v]))
    	                $temp[$k][$v] = $item[$v];
    	        }
	        }
	        return $temp;
	    } else {
	        // 遍历去除指定字段
	        foreach ($result as $k=>$item)
	        {
    	        foreach ($arr as $v)
    	        {
    	            if (isset($item[$v]))
    	                unset($result[$k][$v]);
    	        }
	        }
	        return $result;
	    }
	}
	
	/**
	 * 校验权限
	 * @param string $path 校验路径，如:'interaction/commentsave'
	 * @return bool true为有权限，false为无权限
	 */
	public function is_fireallow($path=null)
	{
	    return $this->get('core.rbac')->checkPath($path);
	}
	
	/**
	 * 
	 * @param string $service 服务名,如:'house.news'
	 * @param array $fields   array('keywords'=>'合同,新房')
	 * @param array $params
	 * @return array 
	 */
	public function getReleated($service, $fields, $params=array())
	{
	    $params = parent::getQueryParam($params);
	    
	    $f = '';
	    foreach ($fields as $k=>$v)
	    {
	        $f = $k;
	        //替换中文逗号，并按逗号进行分割
	        $field = explode(',', str_replace('，', ',', $v));
	    }
	    
	    $map = array();
	    foreach ($field as $v)
	    {
	        // 如：'新房','%,新房,%','新房,%','%,新房'
	        $where = array("$v","%,$v,%","$v,%","%,$v");
	        foreach ($where as $vv)
	        {
	           $map[$f]['orX'][][$f]['like'] = $vv;
	        }
	    }
	    
	    return $this->get($service)->findBy(array_merge($map, $params['criteria']));
	}
}
