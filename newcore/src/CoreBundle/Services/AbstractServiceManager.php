<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2015-6-26
*/
namespace CoreBundle\Services;

use CoreBundle\Model\ModelBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Cache\ApcCache;
/**
 * 服务管理Abstract
 * 模型服务的基类，所有的模型服务都继承此类
 */
abstract class AbstractServiceManager extends ServiceBase
{
    protected $container;
	protected $sqlManager;
	protected $bundlename;
	//是否可以修改他人数据
	protected $_no_check=false;
	
	public function __construct(ContainerInterface $container)
	{
	    $this->container = $container;
	    //通过这里，指定表的mapping路径
	    $this->bundlename  = $this->get('cc')->getDefaultBundle();
	    $this->table = $this->bundlename.":".ucfirst($this->table);
	    // 指定从默认bundle 中查找 metadata，而非当前服务类所在的 bundle
	    $this->sqlManager  = new ModelBase($this->container, $this->table, $this->get('cc')->getDefaultBundle());

	}
	
	public function setNoCheck($flag)
	{
	    $this->_no_check = $flag;
	}
	
	public function getNoCheck()
	{
	    return $this->_no_check;
	}

	/**
	 * 读取表名
	 */
	public function getTables()
	{
		//从model读取数据
		return $this->sqlManager->getTables();
	}

	/**
	 * 设置Entity表名
	 * 如果是全名，HouseBundel:Area，则强制指定了mapping的命名空间
	 * 注：比较担心在一些存数据库的配置中，按bundle+表名的方式来指定服务，而不是直接定义容器服务。这样的话，最需要在不同的bundle中都保留mapping信息。
	 * @param string $tableName
	 */
	public function setTables($tableName)
	{
	    // 注：如果传入的不是全名，表名从 Area 转成全名  HouseBundel:Area 但这个 House 却是来自于 'doctrine.default_entity_manager'
	    $tableName = $this->get('cc')->prefixEntityName($tableName);

		//向model设置数据
		$this->sqlManager->setTables(ucfirst($tableName));
		$this->table = $this->getTables();
	}
	
	/**
	 * 切换 bundle 于不同的connection,不同的bundle中的 db服务，entity类，metadata有何关系
	 * @param string $bundle
	 */
	public function setBundle($bundle)
	{
		$tables = explode(":",$this->table);
		$table = end($tables);
	    $this->sqlManager  = new ModelBase($this->container, $bundle.":".$table, $bundle);

	    self::setTables($table);
	}

	/**
	 * 多条数据查询 (带分页的条件查询)
	 * @param array $criteria          查询条件
	 * @param array $orderBy           排序规则
	 * @param int $limit               查询记录条数限制(每页显示数//???)
	 * @param int $offset              从第几条记录开始查询(当前页码//???)
	 * @param string $groupBy          分组
	 */
	public function findBy(array $criteria, array $orderBy = null, $limit = 2000, $offset = 1, $groupBy='')
	{
	    $result = array();
	    
	    //[BC]
	    unset($criteria['_userFile']);

	    //区域权限
	    $criteria = $this->get('core.area')->dealQueryCriteria($criteria);

        $result = $this->sqlManager->findBy($criteria, $orderBy, $limit, $offset, $groupBy);

	    //属性表数据
	    return empty($criteria['_attribute']) ? $result : self::getAttributeTable($result);
	}
	/**
	 * 所有数据查询(不带参数不带分页），有区域限制
	 * 注:尽量不用
	 */
	public function findAll()
	{
	    
	    return self::findBy(array('_multi' => false));
	}

	/**
	 * 单条数据查询
	 * @param array $criteria 查询条件
	 * @param array $orderBy 排序规则
     * @param boolean $flag 是否查询属性表
	 */
	public function findOneBy(array $criteria, array $orderBy = array(), $flag=false)
	{
	    $result = array();

	    if(isset($criteria['id']) && (int)$criteria['id'] == 0) {
	        return $result;
	    }
	    
        //if(!empty($criteria['_attribute'])) {
            //$flag = true; //因为系统中存在太多用 is_object 来认定记录是否存在的判断，暂时注释这个。
        //}
        //[BC]
	    unset($criteria['_userFile']);

	    //目前用于资讯详情页跨分站不显示的问题
	    if (!isset($criteria['id']))
	       $criteria = $this->get('core.area')->dealQueryCriteria($criteria);//区域权限
	    
        try {
            $result = $this->sqlManager->findOneBy($criteria, $orderBy);
        } catch (\Exception $e) {
            self::logicException($e);
        }

        //属性表
	    if($flag)  $result = self::_getOneAttributeTable($result);

	    return $result;
	}

	/**
	 * 单条数据查询(纵表多合一)
	 * 通过关联(纵表.mid=横表.id),查询属性纵表的多条记录，用于附加到所关联的主(横)表的单条记录上(横表的 attributes字段)
	 * @param array $criteria
	 * @param array $orderBy
	 * @param array 只返回数组结果集
	 */
	public function findOneByVertical(array $criteria, array $orderBy = null)
	{
	    $info = array();

	    //禁用数量统计
	    $criteria['_multi'] = isset($criteria['_multi'])?(bool)$criteria['_multi']:false;
	    $_info = self::findBy($criteria);

	    if(!isset($_info['data']))
	        return array();

	    foreach($_info['data'] as $v)
	    {
	        $info[$v->getName()]['title'] = $v->getTitle();
	        $info[$v->getName()]['value'] = $v->getValue();
	    }

	    return $info;
	}

	/**
	 * 读取回收站单行数据(基于id)
	 * @param int $id
	 */
	public function getTrashById($id)
	{
	    return $this->sqlManager->getTrashById($id);
	}

	/**
	 * 读取回收站多行数据(基本条件)
	 * @param array $param
	 */
	public function getTrash(array $criteria = array(), array $orderBy = null)
	{
	    $criteria['useCache'] = false;
	    return $this->sqlManager->getTrash($criteria, $orderBy);
	}
	
	/**
	 * 批量附加属性表数据 [注] 只处理数组结果集，且合并到主结构集
	 * @param array|object $result
	 */
	public function getAttributeTable($result)
	{
	    if(!isset($result['data'])||empty($result['data']))
	        return $result;

        $table = $this->sqlManager->getTableName();
        $model = $this->get('db.models')->getData($table, 'name');

        if(!isset($model['attributeTable'])||!$model['attributeTable'])
            return $result;

        $model = $this->get('db.models')->getData($model['attributeTable']);

        //判断是否纵表，非纵表则返回
        if(!isset($model['structure'])||!$model['structure'])
            return $result;
        foreach($result['data'] as &$v)
        {
            if(is_array($v)) {//只处理数组结果集，并将结果合并到主结果集中
                $_attributes = $this->get($model['service'])->findOneByVertical(array('mid'=>$v['id']));
                foreach($_attributes as $kk => $vv) {
                    $v[$kk] = $vv['value'];
                }                
            } 
        }
        return $result;
	}

	/**
	 * 单条附加属性表数据 [注] 只处理数组结果集，且合并到主结构集
	 * @param array|object $result
	 */
	private function _getOneAttributeTable($result)
	{
	    if(empty($result) || is_object($result)) {//不处理对象结果集
	        return $result;
	    } 

	    $table = $this->sqlManager->getTableName();

        $model = $this->get('db.models')->getData($table, 'name');
        
        if(!isset($model['attributeTable'])||!$model['attributeTable'])
            return $result;

        $model = $this->get('db.models')->getData($model['attributeTable']);

        if(!isset($model['structure'])||!$model['structure'])
            return $result;
        
        $_attributes = $this->get($model['service'])->findOneByVertical(array('mid'=>$result['id']));
        foreach($_attributes as $kk => $vv) {
            $result[$kk] = $vv['value'];
        }

        return $result;
	}

	/**
	 * 数据统计
	 * @param array $criteria
	 * @param string $groupBy
	 */
	public function count(array $criteria, $groupBy=null)
	{
	    //区域权限
	    $criteria = $this->get('core.area')->dealQueryCriteria($criteria);
		try {
		    $criteria['useCache'] = 0;
            return $this->sqlManager->count($criteria, $groupBy);
        } catch (\Exception $e) {
            self::logicException($e);
        }
	}
	
	/**
	 * 未用
	 */
	public function ucwords1($string, $delimiters = " \n\t\r\0\x0B-")
	{
	    return preg_replace_callback(
	        '/[^' . preg_quote($delimiters, '/') . ']+/',
	        function($matches) {
	            return ucfirst($matches[0]);
	        },
	        $string
	    );
	}

	/**
	 * 内嵌查询
	 * @param number $pageIndex
	 * @param number $pageSize
	 * @param array $param
	 */
	public function getInternalSearch(array $param=array())
	{     	
     	//区域权限
     	$param['query'] = $this->get('core.area')->dealQueryCriteria($param['query']);
     	if(isset($param['query1'])) {
     	    $param['query1'] = $this->get('core.area')->dealQueryCriteria($param['query1']);
     	}
	    return $this->sqlManager->getInternalSearch($param);
	}

	/**
	 * UNION ALL 查询
	 * @param array $tables
	 * @param array $params
	 * @return array 数组中的值是数组，不是对象
	 */
	public function getUNIONSearch(array $tables, array $params, array $params1 = array())
	{
	    //区域权限
	    $params['criteria'] = $this->get('core.area')->dealQueryCriteria(isset($params['criteria'])?$params['criteria']:array());
	    return $this->sqlManager->getUNIONSearch($tables, $params, $params1);
	}

	/**
	 * 二叉树结构
	 * @param number $pageIndex
	 * @param number $pageSize
	 * @param array $param
	 */
	public function getBinaryTreeSearch(array $param=array())
	{
	    $param['pageIndex'] = isset($param['pageIndex'])&&$param['pageIndex']>0?$param['pageIndex']:1;
	    $param['pageSize'] = isset($param['pageSize'])&&$param['pageSize']>0?$param['pageSize']:8;

	    //区域权限
	    $param['query'] = $this->get('core.area')->dealQueryCriteria($param['query']);	    
	    if(isset($param['query1'])) {
	        $param['query1'] = $this->get('core.area')->dealQueryCriteria($param['query1']);
	    }
	    
	    return $this->sqlManager->getBinaryTreeSearch($param);
	}

	/**
	 * 获取所有的有效数据
	 * @param int $pageIndex	当前页码
	 * @param int $pageSize		每页显示的数量
	 * @param array $param		查询条件
	 * 取出sql和dql调试语句
	 * return $query->getDQL();
	 * return $query->getSQL();
	 */
	public function getTableByAll($pageIndex=1, $pageSize=8, array $param=array())
	{
	    $result = array();

	    $data = array();
	    $data['pageIndex'] = isset($data['pageIndex'])?$data['pageIndex']:$pageIndex;
	    $data['pageSize']  = isset($data['pageSize'])?$data['pageSize']:$pageSize;
	    $data['findType']  = 1; //获得查询结果集,true为数组结果集，false为对象结果集
	    $data['pageIndex'] = $data['pageIndex']<=0?1:$data['pageIndex'];
	    $data['pageSize'] = $data['pageSize']<=2000?$data['pageSize']:2000;
	    $data = array_merge($data, $param);
	    $data['query'] = $this->get('core.area')->dealQueryCriteria(isset($data['query'])?$data['query']:array());//区域权限

    	try {
            $result = $this->sqlManager->getTableByAll($data);
        } catch (\Exception $e) {
            self::logicException($e);
        }

        //属性表数据        
        return isset($data['_attribute'])&&$data['_attribute']?self::getAttributeTable($result):$result;
	}

	/**
	 * 获取数据，基于ID
	 * @param string $id
	 */
	public function getTableById($id)
	{
	    $metadata = $this->getClassMetadata($this->table);

	    if(!isset($metadata->identifier[0]))
	        return array();
	    
	    $map = array();
	    $map[$metadata->identifier[0]] = $id;
	    $map = $this->get('core.area')->dealQueryCriteria($map);//区域权限
	    return self::findOneBy($map, array(), false);
	}
	
	/**
	 * 主添加入口(表单或不验证),尽量不要以传入 $entity的方式。纵表单不需要添加(不存在entity与自增id)
	 * @param array $data          该数据优先于 $entity,所以尽量不要在方法之前处理 $entity，其数据可能被覆盖掉。
	 * @param $entity              尽量不要传 $entity,以$data 为准
	 * @param boolean $isValid     是否使用表单提交
	 * @return entity/boolean      当主表单为纵表单时，返回的是 boolean。
	 */
	public function add(array $data, $entity=null, $isValid=true)
	{
	    if (($result = self::_checkBatch($data)) !== false)
	        return $result;
	    
	    if($isValid) {
			
	        $entity = $this->get('core.form_submit')->defaultSubmit(0, $data, $entity, $this);
	
	    } else {
	
	        $entity = self::dataToEntity($data, $entity);
	        $entity = self::createEntity($entity);
	         
	    }

	    return $entity;
	}

	/**
	 * 主更新入口,尽量不要以传入 $entity的方式。注意使用纵表表单时，不能使用当前表的 metadata 及 entity
	 * @param int $id              基于ID的更新条件
	 * @param array $data          需要更新的数据(数组)
	 * @param object $entity       尽量不要传 $entity,以$data 为准
	 * @param boolean $isValid     是否使用表单提交
	 * @return entity/boolean      当主表单为纵表单时，返回的是 boolean。
	 * @throws LogicException
	 */
	public function update($id, array $data, $entity=null, $isValid=true)
	{
        //复制
        if(true == $isValid && true == (int)$this->get('request')->get('_copy',0)) {
            return $this->add($data,null,true);
        }
         
        // 检查会员中心所有者权限//如果需要跳过该检查，可先用 setNoCheck(true)方法
        self::checkOperatePermission($id, $data, $entity, 'update');
         
        if($isValid) {
            
            $entity = $this->get('core.form_submit')->defaultSubmit($id, $data, $entity, $this);//写库已完成。
        
        } else {
             
            if(!$entity = self::_isEntity($entity,$id)) {
                throw new \LogicException('数据不存在或已被删除');
            }
             
            $entity = self::dataToEntity($data, $entity);
             
            $entity = self::modifyEntity($entity);// 写库
        }
         
        return $entity;
	}
	
	/**
	 * 假删除。需要加入属性表的关联删除//??//!!
	 * @param int $ids         基于ID的删除条件
	 * @param object $info     已实例化的对像
	 * @throws \LogicException
	 */
	public function delete($ids)
	{
	    if(!is_array($ids)) {
	        $ids = explode(',',$ids);
	    }

	    $i = 0;
	    foreach($ids as $id) {
	        //获得原数据
	        $info = $this->findOneBy(array('id'=>$id),array(),false);
	        if(empty($info)) continue;
	        	
	        self::checkOperatePermission($id, array(), $info, 'delete');

	        //嵌入修改时间
	        if(method_exists($info, 'getUpdateTime')) {
	            $info->setUpdateTime(time());
	        }

	        $this->remove($info);
	        $i++;
	    }
	    //更新Yml缓存文件
	    if(method_exists($this,'handleYmlData') && $i) {
	        $this->handleYmlData($info);
	    }

	    return $i;
	}	

	/**
	 * 系统内部添加，不触发数据库LifecycleEvent(模型监听)
	 * @param array $data 需要更新记录的查询条件，如果不存在该条件的记录，则新增。
	 * @return integer 受影响的记录数
	 * @throws LogicException
	 */
	public function dbalAdd(array $data)
	{
	    if(empty($data)) throw new \LogicException("新增数据不能为空!", false);

	    $data['create_time'] = time();
	    $data['update_time'] = time();
	    $data['identifier'] = $this->get('cc')->createIdentifier();
	    if(!isset($data['area'])) {
	        $data['area'] = $this->get('core.area')->getArea();
	    }

	    try {
	        $result = $this->sqlManager->dbalAdd($data);
	    } catch (\Exception $e) {
	        self::logicException($e);
	    }
	
	    //清缓存
	    $this->_clearResultCache();
	    return $result;
	}
	
	/**
	 * 系统内部修改，不触发数据库LifecycleEvent(模型监听)
	 * @param array $data     更新的数据
	 * @param array $params   查询条件(更新哪些记录)
	 */
	public function dbalUpdate(array $data, array $params = array())
	{
	    if(empty($data)) throw new \LogicException("更新数据不能为空!", false);
	    $data['update_time'] = time();
	
	    try {
	        $this->sqlManager->dbalUpdate($data, $params);
	    } catch (\Exception $e) {
	        self::logicException($e);
	    }
	
	    //清缓存
	    $this->_clearResultCache();
	    return true;
	}
	
	/**
	 * 系统内部使用。按条件删除，不触发数据库LifecycleEvent(模型监听)
	 * 需要加入属性表的关联删除//??//!!
	 * @param array $params   查询条件(删除哪些记录)
	 */
	public function dbalDelete(array $params)
	{
	    if(empty($params)) {
	        throw new \LogicException("查询参数不能为空!", false);
	    }
	
	    try {
	        $this->sqlManager->dbalDelete($params);
	    } catch (\Exception $e) {
	        self::logicException($e);
	    }
	     
	    //清缓存
	    $this->_clearResultCache();
	    return true;
	}
	
	/**
	 * 多条记录批量添加,每条记录可以是一个 entity 对象，也可以数组
	 * @param array $infos 含多个entity(或数据)的数组
	 * @return array 包含成功入库的  entity 的数组
	 */
	public function batchadd(array $infos)
	{
	    $entities = array();
	    foreach($infos as $v) {
	        if(is_object($v)) {
	            $entity = self::systemAssign($v, 'add');
	        } else {
	            $entity = self::dataToEntity($v, null);
	        }
	        $entity = self::systemAssign($entity, 'add');
	        $entities[] = $entity;
	    }
	    self::batchCreate($entities);
	    return $entities;
	}

	/**
	 * 多个 entity的批量更新，不可是数据数组，只接收对象数组
	 * @param array $entities
	 * @return array 包含成功更新入库的entity数组
	 */
	public function batchupdate(array $entities)
	{
	    foreach($entities as $v) {
	        self::systemAssign($v, 'update');
	    }
    
	    try {
	        $this->sqlManager->batchUpdate($entities);
	    } catch (\Exception $e) {
	        self::logicException($e);
	    }
	
	    //清缓存
	    $this->_clearResultCache();
	    return $entities;
	
	}

	/**
	 * 按非id条件，检查一条记录是否存在,不存在则补充一条新的。注意需要更新的数据不要在这里处理。
	 * 主要用于属性表或关联横表，1、补全userinfo表记录，2、属性表或流水表的记录
	 * //??这个方法的使用情况需要复查,极易出现逻辑混乱
	 * @param array $criteria 识别唯一记录的特征，不能含更新数据(否则会加出很多意想不到的记录)
	 * @return Ambigous <multitype:, multitype:array object >|Ambigous <boolean, unknown>
	 */
	public function createOneIfNone(array $criteria)
	{
	    if(isset($criteria['id']) || empty($criteria)) {//避免开发中误用本方法
	        throw new \LogicException(__METHOD__.'条件为空或含id');
	    }
	
	    $map = array();
	    $map['useCache'] = false;
	
	    $entity = self::findOneBy($map + $criteria);
	
	    if(is_object($entity))
	        return $entity;
	
	    // 补弃的数据应是唯一识别一条数据的条件
	    $data = array();
	    foreach($criteria as $k => $v) {
	        $data[$k] = is_array($v) ? end($v) : $v;
	    }
	
	    return self::add($data, null, false);
	}	
	/**
	 * 恢复删除数据（恢复假删除数据）,只针对数据库操作
	 * @param int $id    要恢复的数据ID
	 * @return bool
	 */
	public function undelete($id)
	{
	    $entity = $this->getTrashById($id);
	    //从model恢复删除数据
	    $this->sqlManager->undelete($entity);

	    return true;
	}
	
	/**
	 * 真删除,将把数据从数据表中永久删除, 不可恢复,只针对数据库操作
	 * @param int $id          基于ID的永久删除条件
	 * @param $entity          Entity对象
	 * @throws \LogicException
	 */
	public function realDelete($id, $entity = null)
	{
	    //获得原数据
	    if(!is_object($entity))
	        $entity = $this->getTrashById($id);
	
	    if(empty($entity))
	        throw new \LogicException('数据不存在或已被删除');
	
	    if(method_exists($entity, 'getIssystem') && $entity->getIssystem()==1)
	        throw new \LogicException("系统类数据不允许删除!!!", false);
	
	    //从model删除数据
	    return $this->sqlManager->realDelete($entity);
	}


	/**
	 * 数据转为 entity。如传入已有 entity,则将数据合并到该entity。如果$entity为空，则新创建一个。
	 * @param array $data          需要写入到 $entity 的数据
	 * @param object|null $entity  当其等于null时为添加操作，会得到一个新创建的。
	 * @return object $entity
	 * @throws \LogicException
	 */
	public function dataToEntity(array $data, $entity = null)
	{
        $_isOld = is_object($entity) ? true : false;
        $metadata = self::getClassMetadata();

        // 确认是非 Doctrine 原生 metadata
        if(!isset($metadata->entity) || !is_object($metadata->entity)) {
            throw new \LogicException('$metadata 对象错误');
        }
        
        if(!$_isOld) $entity = clone $metadata->entity;
        
        foreach($metadata->column as $v) {
            $type = isset($metadata->fieldMapps[$v]['type']) ? $metadata->fieldMapps[$v]['type'] : 'string';
            $options = $metadata->fieldMapps[$v]['options'];
            $default = isset($options['default']) ? $options['default'] : '';

            if(!isset($data[$v])) {
                if($_isOld) continue; //如果是更新动作，不再为其设置默认值。
                $value = $default;
            } else $value = $data[$v];
        
            $value = $this->get('cc')->normalizeFieldValue($value,$type);
            $entity->{"set" . $this->get('cc')->ucWords($v)}($value);
        }
        
        if(method_exists($entity, "setAttributes")) {
            $entity->setAttributes('');
        }
        
        return $entity;
	}	
	
	/**
	 * 将一个更新好的entity，执行最后的系统内置规则之后入库
	 */
	public function modifyEntity($entity)
	{
	    self::systemAssign($entity, 'update');
	    self::modify($entity);
	    return $entity;  
	}	
	/**
	 * 将一个更新好的entity，执行最后的系统内置规则之后入库
	 */
	public function createEntity($entity)
	{
	    self::systemAssign($entity, 'add');
	    self::create($entity);
	    return $entity;
	}	
	


	/**
	 * 重建整个表的二叉树结构
	 */
	public function createBinaryNode()
	{
	    set_time_limit(3600) ;

	    $data = array();
	    $data['left_node'] = 0;
	    $data['right_node'] = 0;
	    $data['binary_tree'] = 0;

	    //将表中所有的二叉树数据清0
	    self::dbalUpdate($data);

// 	    $infos = self::findBy(array(), null, 10000);

// 	    if(!isset($infos['data']))
// 	        return array();

// 	    $infos = $this->get('cc')->getTree($infos['data'],0);
	    
// 	    foreach($infos as $info)
// 	    {
// 	        self::_createBinaryNode($info);
// 	        unset($info);
// 	    }
	    
	    
	    $infos = self::findBy(array('findType'=>1), null, 10000);
	    
	    //所有数据
	    $infosArr = array();
	    //组装数组
	    foreach ($infos['data'] as $item)
	    {
	        $infosArr[$item['id']] = array('id'=>$item['id'],'pid'=>$item['pid'],'left_node'=>$item['left_node'],'right_node'=>$item['right_node']);
	    }
	    
        $infos = $this->get('cc')->getTree($infosArr,0);
        foreach ($infos as $info)
        {
            self::_operationNode($info, $infosArr);
        }
        unset($infos);
        
        //更新数据
        $data = array();
        $data['binary_tree'] = 1;//??
        $param = array();
        foreach ($infosArr as $info)
        {
            $data['left_node'] = $info['left_node'];
            $data['right_node'] = $info['right_node'];
            $param['id'] = $info['id'];
            self::dbalUpdate($data, $param);
        }
        unset($infosArr);

	    //控制器初始化
	    if(method_exists($this,'handleYmlData'))
	        $this->handleYmlData(self::retCache());
	    else
	        self::retCache();
	}
	
	/**
	 * 计算二叉树的节点值
	 * @param unknown $info
	 * @param unknown $tree
	 * @return boolean
	 */
	private function _operationNode($info, &$tree)
	{
	    if(!is_array($info->menu))
	        return false;

	    $right_node = isset($tree[$info->menu['pid']])?$tree[$info->menu['pid']]['left_node']:0;
	    foreach ($tree as &$item)
	    {
	        if ($item['right_node']>$right_node)
	            $item['right_node'] += 2;
	        if ($item['left_node']>$right_node)
	            $item['left_node'] += 2;
	        if ($item['id'] == $info->menu['id'])
	        {
	            $item['left_node'] = $right_node+1;
	            $item['right_node'] = $right_node+2;
	        }
	    }

        if(!isset($info->nodes))
            return true;
        
        foreach($info->nodes as $item1)
        {
            self::_operationNode($item1, $tree);
        }

        return true;
	}

	/**
	 * 创建某个节点的二叉树数据
	 * 过程中需要更新其它节点的二叉树数据,插入当前节点数据，将大于当前节点数据的节点数据+2
	 */	
	public function _createBinaryNode($info)
	{
	    //只对建立了树形结构的对象进行处理
	    if(!is_object($info->menu))
	        return false;

	    $map = array();
	    $map['id'] = $info->menu->getPid();
	    $map['findType'] = 1;//获得查询结果集,true为数组结果集，false为对象结果集
	    $map['useCache'] = 0;
	    $map['binary_tree'] = 1;
	    $rs = self::findOneBy($map, array(), false);

	    //???
	    if(empty($rs))
	    {
	        $map = array();
	        $map['id'] = $info->menu->getPid();
	        $map['findType'] = 1;//获得查询结果集,true为数组结果集，false为对象结果集
	        $map['binary_tree'] = 1;

	        $rs = self::findOneBy($map, array(), false);

	        $rs['left_node'] = isset($rs['left_node'])?$rs['left_node']-1:0;
	    }

	    $right_node = isset($rs['left_node'])?(int)$rs['left_node']:0;

	    $data = array();
	    $params = array();
	    //$data['right_node'] = "(p.right_node + 2)";
	    $data['right_node']['sum'] = 2;
	    $data['binary_tree'] = 1;
	    $params['right_node']['gt'] = $right_node;
	    self::dbalUpdate($data, $params);

	    $data = array();
	    $params = array();
	    //$data['left_node'] = "(p.left_node + 2)";
	    $data['left_node']['sum'] = 2;
	    $data['binary_tree'] = 1;
	    $params['left_node']['gt'] = $right_node;
	    self::dbalUpdate($data, $params);

	    $data = array();
	    $params = array();
	    $data['left_node'] = $right_node+1;
	    $data['right_node'] = $right_node+2;
	    $data['binary_tree'] = 1;
	    $params['id'] = $info->menu->getId();
	    self::dbalUpdate($data, $params);
	    
	    unset($rs);
	    unset($map);
	    unset($data);
	    unset($params);
	    unset($right_node);
	    

	    if(!isset($info->nodes))
	        return true;

	    foreach($info->nodes as $item)
	    {
	        self::_createBinaryNode($item);
	    }

	    unset($info);

	    return true;
	}
	
	/**
	 * 创建某个节点的二叉树数据
	 * 过程中需要更新其它节点的二叉树数据,插入当前节点数据，将大于当前节点数据的节点数据+2
	 */
	public function createBinaryNodeByOne($info)
	{
	    if(!is_object($info))
	        return false;

	    $map = array();
	    $map['id'] = $info->getPid();
	    $map['findType'] = 1;//获得查询结果集,true为数组结果集，false为对象结果集
	    $map['useCache'] = 0;
	    $map['binary_tree'] = 1;

	    //$rs = self::findBy($map, array('right_node'=>'desc'), 1);

	    //$rs = isset($rs['data'][0])?$rs['data'][0]:'';
	    
	    $rs = self::findOneBy($map, array(), false);

	    if(empty($rs))
	    {
	        $map = array();
	        $map['id'] = $info->getPid();
	        $map['findType'] = 1;//获得查询结果集,true为数组结果集，false为对象结果集
	        $map['binary_tree'] = 1;

	        $rs = self::findOneBy($map, array(), false);

	        $rs['left_node'] = isset($rs['left_node'])?$rs['left_node']-1:0;
	    }

	    $right_node = isset($rs['left_node'])?(int)$rs['left_node']:0;

	    // 所有 right_node>p_right_node 的节点，right_node+2
	    $data = array();
	    $params = array();
	    $data['right_node'] = "(p.right_node + 2)";
	    $data['binary_tree'] = 1;
	    $params['right_node'] = array('gt'=>$right_node);
	    self::dbalUpdate($data, $params);
	    
	    // 所有 left_node>p_right_node 的节点，left_node+2
	    $data = array();
	    $params = array();
	    $data['left_node'] = "(p.left_node + 2)";
	    $data['binary_tree'] = 1;
	    $params['left_node'] = array('gt'=>$right_node);
	    self::dbalUpdate($data, $params);
	    
	    // 当前节点，left_node=p_right_node+1, right_node=p_right_node+2
	    $data = array();
	    $params = array();
	    $data['left_node'] = $right_node+1;
	    $data['right_node'] = $right_node+2;
	    $data['binary_tree'] = 1;
	    $params['id'] = $info->getId();
	    self::dbalUpdate($data, $params);

	    self::retCache();
	    return true;
	}

	/**
	 * 获取某个父节点以及其所有子节点
	 * @param int $id
	 */
	public function getNodeById($id=0, $params=array())
	{
	    $info = $this->findOneBy(array_merge(array('id'=>$id), $params), array(), false);

	    if(empty($info))
	        return array();

	    $map = array();

	    if(is_array($info))
	    {
	    	$map['findType'] = 1;//获得查询结果集,true为数组结果集，false为对象结果集
	    	$map['left_node']['between'] = array($info['left_node'],$info['right_node']);
	    }else{
	    	$map['left_node']['between'] = array($info->getLeftNode(),$info->getRightNode());
		}
        
		// 只取最末端一层的子节点(最末一层的左右节点是连续的)
	    if(!isset($params['_multinode']))
	        $map['right_node']['diffX']['left_node'] = 1;
	    unset($params['_multinode']);
	    
	    $params['order'] = 'sort|asc,id|asc';
	    $infos = $this->findBy(array_merge($map, $params));

	    $infos['data'][] = $info;

	    $infos['pageCount'] = count($infos['data']);

	    return $infos;
	}

	/**
	 * 获取所有子节点，注：暂未在系统中使用
	 * @param array $data
	 */
	public function getNode(array $params = array())
	{
	    $aliasName = isset($params['aliasName'])?$params['aliasName']:"p";

        $params['pageIndex'] = isset($params['pageIndex'])?$params['pageIndex']:1;
	    $params['pageSize'] = isset($params['pageSize'])?$params['pageSize']:8;
	    $params['query']['aliasName'] = isset($params['query']['aliasName'])?$params['query']['aliasName']:'u';
	    $params['query']["{$aliasName}.left_node"]['between'] = array('u.left_node', 'u.right_node');

	    $select = $from = array();

	    //关联库
	    $from[$this->table] = $params['query']['aliasName'];

	    return $this->sqlManager->findByBuilder($select, $from, $params);
	}

	/**
	 * 获取所有的叶子节点，注：暂未在系统中使用
	 */
	public function getSubNode(array $data = array())
	{
	    return $this->sqlManager->getSubNode($data);
	}

	/**
	 * 获取指定id的子节点及其所有(多级)父节点
	 * @param unknown $id
	 * @param array $notid    过滤掉的id
	 * @param unknown $order  排序
	 */
	public function getParent($id, array $notid=array(), $order=array())
	{
	    $data = array();

	    if(in_array($id, $notid, true))
	        return $data;
        
	    //当前节点
	    $info = self::findOneBy(array('id'=>$id), array(), false);

	    if(empty($info))
	        return $data;

	    $map = array();
	    $map['_multi'] = false;
	    $map['left_node']['lte'] = $info->getLeftNode();
	    $map['right_node']['gte'] = $info->getLeftNode();

	    if($notid)
	        $map['id']['notIn'] = $notid;

	    return self::findBy($map, $order);
	}

	/**
	 * 删除某个节点及其所有子节点
	 * @param int $id
	 */
	public function deleteNode($id)
	{
	    return $this->sqlManager->deleteNode($id);
	}




	
	public function dropTable($bundle, $serviceName)
	{
	    try {
	        $em = self::getEntityManager();
	         
	        $metadata = self::getClassMetadata(null, true);
	         
	        //表名
	        $tableName = isset($em->getMetadataFactory()->getMetadataFor($this->table)->table['name'])?$em->getMetadataFactory()->getMetadataFor($this->table)->table['name']:'';
	         
	        if(empty($tableName))
	            return false;
            
	        if($serviceName) {
    	        //删除orm文件
    	        $this->get('core.importdoctrine.command')->deleteOrmFile($metadata, $bundle, $serviceName);	            
	        }

	        //删除数据表
    	    $sql = "DROP TABLE IF EXISTS {$tableName};";
    	    $connection = $em->getConnection();
    	    $stmt = $connection->prepare($sql);
    	    $stmt->execute();
    	    $stmt->closeCursor();
	    } catch (\Exception $e) {     
	        exit($tableName);
	    }

	    return true;
	}

	/**
	 * 获取表结构
	 * @param string $tableClass 格式：HouseBundle:Userinfo 或完全类名HouseBundle\Entity\Userinfo
	 * @param string $flag 为true时，返回 Doctrine 原生对象
	 */
	public function getClassMetadata($tableClass=null, $flag=false)
	{
	    //取表Metadata
	    $classMetadata = $this->sqlManager->getClassMetadata($tableClass?$tableClass:$this->table);
	    if($flag) {
	        return $classMetadata;
	    }
	    
	    //定义对像类型数据
	    $metadata = new \stdClass();
	    $identifier  = $classMetadata->getIdentifier();
	    $columnNames = $classMetadata->columnNames;

	    //去主键，自增长型主键不能直接赋值
	    foreach($identifier as $v)
	    {
	        unset($columnNames[$v]);
	    }

	    $metadata->identifier = $classMetadata->getIdentifier();
	    $metadata->column     = $columnNames;
	    $metadata->entity     = new $classMetadata->name;
	    $metadata->reflFields = $classMetadata->reflFields;
	    $metadata->fieldMapps = $classMetadata->fieldMappings;
	    $metadata->tablename  = $classMetadata->table['name'];

	    return $metadata;
	}

	public function getEntityManager()
	{
	    return $this->sqlManager->getEntityManager();
	}

	public function getConnection()
	{
	    return $this->sqlManager->getEntityManager()->getConnection();
	}

	/**
	 * 获得表名
	 */
	public function getTableName($prefix=false)
	{
	    return $prefix&&isset(self::getEntityManager()->getMetadataFactory()->getMetadataFor($this->table)->table['name'])?self::getEntityManager()->getMetadataFactory()->getMetadataFor($this->table)->table['name']:$this->sqlManager->getTableName();
	}

	/**
	 * 获取当前用户信息
	 */
	public function getUser()
	{
	    return $this->get('cc')->getUser();
	}



	//需要进一步整理//??//!!
	public function retCache()
	{
	    $this->get('cc')->S();
	    self::clearCache();
	    return true;
	}
	
	/**
	 * 没有表单的entity验证
	 * @param object $entity Entity对象
	 * @return boolean
	 * @throws InvalidArgumentException
	 */
	public function validateEntity($entity)
	{
	    if(!is_object($entity)) {
	        throw new \InvalidArgumentException('被验证的必须是一个Entity对象');
	    }
	    //数据校验
	    $errors = $this->get('validator')->validate($entity);
	
	    //如有错误信息刚返回错误提示
	    if(count($errors)>0) {
	        throw new \InvalidArgumentException($errors[0]->getMessage());
	    }
	
	    return true;
	
	}

	// 在entity入库前执行系统内定规则，针对横表entity(纵表单数据转为横表添加时，也要经过这个处理)
	// dbal的系统规则不在这里处理
	protected function systemAssign($entity, $operateType = 'add')
	{
	    if(is_object($entity)) {
	
	        $data = $this->get('request')->request->All();
	
	        switch ($operateType) {
	            case 'add':	                
	                //不受影响的特殊表
	                $passTables = array('msglog');
	                if (!in_array(self::getTableName(), $passTables))
	                {
    	                //嵌入checked
    	            	$user = $this->get('cc')->getUser();
    	            	if (!is_object($user) || (method_exists($user, 'getMid')&&$user->getMid()==0)) {
    		                if (method_exists($entity, 'getChecked')) {
    		                    if ($this->get('core.rbac')->isGranted('autocheck', $this->getTableName(), $this->get('cc')->getBundleName(true)))
    		                        $entity->setChecked(0);
    		                }
    		            }
	                }
	                //嵌入创建时间
	                if(method_exists($entity, 'getCreateTime') && $entity->getCreateTime()==0) {
	                    $entity->setCreateTime(time());
	                }
	                //嵌入状态
	                if(method_exists($entity, 'getStatus')) {
	                    $entity->setStatus(1);
	                }
	                //嵌入唯一标识
	                if(method_exists($entity, 'getIdentifier')) {
	                    $entity->setIdentifier($this->get('cc')->createIdentifier());
	                }
	
	                //注入区域字段
                    if(method_exists($entity, 'getArea') && $entity->getArea()==0 && !isset($data['area'])){
	                    $entity->setArea($this->get('core.area')->getArea());
	                }
	
	                //判断二叉树结构，注：如传入了_binaryFlag标记，则避免创建二叉树结构(仅用于数据导入导出)
	
	                if(!isset($data['_binaryFlag']) || $data['_binaryFlag']==0)//避免导出
	                {
	                    if(method_exists($entity, 'getPid') && method_exists($entity, 'getLeftNode') && method_exists($entity, 'getRightNode')) {
	                        $nEntity = clone $entity;
	                        self::createBinaryNodeByOne($nEntity);
	                    }
	
	                }
	
	                break;
	            case 'update':
	
	                if(method_exists($entity, 'setAttributes')) {
	                    $entity->setAttributes('');
	                }
	                //系统类数据必须为启用
	                if(method_exists($entity, 'getIssystem') && $entity->getIssystem() == 1) {
	                    if(method_exists($entity, 'setAvailable')) {
	                        $entity->setAvailable(1);
	                    }
	                }
	
	                break;
	        }
	        //嵌入修改时间
	        if(method_exists($entity, 'getUpdateTime')) {
	            $entity->setUpdateTime(time());
	        }
	        //分析分站权限
	        $this->get('core.area')->dealModifyPermission($entity);
	    }
	    return $entity;
	}
	
	/**
	 * 检查在会员中心时，是否允许操作(删除或修改)别人发布的内容,待重新考虑此方案//??//??
	 * 如果需要跳过该检查，可先用 setNoCheck(true)方法
	 */
	protected function checkOperatePermission($id, array $data, $entity = null, $operateType = 'update')
	{
	    if($operateType == 'delete') {
	        if(method_exists($entity, 'getIssystem') && $entity->getIssystem()==1)
	        {
	            if(method_exists($entity, 'getName'))
	                throw new \LogicException("[".$entity->getName()."]为系统类数据不允许删除!", false);
	            else
	                throw new \LogicException("[".$entity->getId()."]为系统类数据不允许删除!", false);
	        }
	         
	        //判断是否有下级
	        if(method_exists($entity, 'getPid'))
	        {
	            $count = $this->count(array('pid'=>$entity->getId()));
	            if($count>0)
	            {
	                if(method_exists($entity, 'getName'))
	                    throw new \LogicException("[".$entity->getName()."]有下级数据，禁止删除!", false);
	                else
	                    throw new \LogicException("[".$entity->getId()."]有下级数据，禁止删除!", false);
	            }
	        }
	    }
	
	    if(!$this->_no_check) {
	
	        //判断数据是否属于当前用户
	        $user = $this->get('cc')->getUser();
	        //屏蔽游客删除数据
	        if (!is_object($user) && $operateType == 'delete') {
	            throw new \LogicException('游客不能修改数据，请登录后再操作');
	        }
	
	
	        //如果考虑到纵表表单的话，这部分处理需要调整
	        if (is_object($user) && method_exists($user, 'getUserinfo') && method_exists($user->getUserinfo(), 'getUsertype')) {
	             
	            $result = $this->get('db.mem_types')->findOneBy(array('id'=>$user->getUserinfo()->getUsertype()), array(), false);
	
	            if(is_object($result) && $result->getUsertplid()) {
	                //获得原数据
	                if(!is_object($entity)) {
	                    $entity = $this->findOneBy(array('id'=>$id), array(), false);
	                }
	                 
	                if (is_object($entity) && method_exists($entity, 'getUid') && $entity->getUid()!=$user->getId())
	                {
	                    throw new \LogicException('不得删改他人数据');
	                }
	            }
	        }
	    }
	}
	

	/**
	 * 新增实体对象
	 * [注] 暂无外部调用
	 * @param $entity Entity对象
	 */
	protected function create($entity)
	{
        try {
            
           $this->sqlManager->add($entity);
           //dump($entity);
           //exit;
           
            
        } catch (\Exception $e) {
            self::logicException($e);
        }   
	    //清缓存
	    $this->_clearResultCache();
	}
		
	
	/**
	 * 批量新增实体对象
	 * [注] 暂无外部调用
	 * @param array $entities 实体对象数组
	 */
	protected function batchCreate(array $entities)
	{
        try {
            $this->sqlManager->batchadd($entities);
        } catch (\Exception $e) {
            self::logicException($e);
        }

	    //清缓存
	    $this->_clearResultCache();
	}
	

	/**
	 * 删除一个实体对象
	 * [注] 暂无外部调用
	 * @param $entity Entity对象
	 * @return boolean
	 */
	protected function remove($entity)
	{
	    if(!is_object($entity)) return false;
        try {
            $this->sqlManager->delete($entity);
        } catch (\Exception $e) {
            self::logicException($e);
        }
	
	    //清缓存
	    $this->_clearResultCache();
	    return true;
	}
	
	/**
	 * 更新一个实体对象  
	 * [注] 暂未外部调用
	 * @param $entity Entity对象
	 * @return boolean
	 */
	protected function modify($entity)
	{
	    if(!is_object($entity))
	        return false;

        try {
            $this->sqlManager->update($entity);
        } catch (\Exception $e) {
            self::logicException($e);
        }
	
	    //清缓存
	    $this->_clearResultCache();
	    return true;
	}

	protected function logicException(\Exception $e)
	{
	    $this->get('cc')->logicException($e);
	}

	
	/**
	 * 清除缓存 [注]未外用
	 */
	protected function clearCache()
	{
	    //清除元数据缓存
	    self::_clearMetadataCache();
	
	    //清除结果数据缓存
	    self::_clearResultCache();
	
	    //清除查询数据缓存
	    self::_clearQueryCache();
	}
	
	/**
	 * 清除元数据缓存
	 * [注] 开发后台修改模型字段或手动修改metadata文件，metadata缓存需要更新
	 */
	protected function _clearMetadataCache()
	{
	    $cacheDriver = $this->getEntityManager()->getConfiguration()->getMetadataCacheImpl();
	     
	    if ( ! $cacheDriver )
	        return false;
	
	    if ($cacheDriver instanceof ApcCache)
	        return false;
	
	    $cacheDriver->deleteAll();
	
	    return true;
	}
	
	/**
	 * 清除结果数据缓存
	 */
	protected function _clearResultCache()
	{
	    $cacheDriver = $this->getEntityManager()->getConfiguration()->getResultCacheImpl();
	    //不配置，则默认使用 new \Doctrine\Common\Cache\ArrayCache()
     
	    if ( ! $cacheDriver )
	        return false;
	     
	    if ($cacheDriver instanceof ApcCache)
	        return false;
	     
	    $cacheDriver->deleteAll();
     
	    return true;
	}
	
	/**
	 * 清除查询数据缓存
	 */
	protected function _clearQueryCache()
	{
	    $cacheDriver = $this->getEntityManager()->getConfiguration()->getQueryCacheImpl();
	     
	    if ( ! $cacheDriver )
	        return false;
	
	    if ($cacheDriver instanceof ApcCache)
	        return false;
	
	    $cacheDriver->deleteAll();
	
	    return true;
	}
	
	/**
	 * 是否一个  entity
	 */	
	protected function _isEntity($entity,$id = 0)
	{
	    if(is_object($entity)) return $entity;

	    if(($id = (int)$id) <= 0) return false;
	
	    $entity = self::findOneBy(array('id' => $id), array(), false);
    
	    if(empty($entity)) {
	        return false;
	    }
	
	    return $entity;
	}
	/**
	 * [不建议用] 系统内部用的添加，目前只用于打包或更新。进一步会考虑清理//??//!!
	 * @param object/array $data 为object时，只是新增。为数组时，新增/更新 $params 所查询到的对象
	 * @param $params 需要更新记录的查询条件，如果不存在该条件的记录，则新增。
	 * @return object Entity
	 */
	public function systemAdd($entity, array $params=array())
	{
	    if(is_object($entity)) {
	        self::create($entity);
	        return $entity;
	    }
	
	    if(empty($params) || !is_array($entity))  return array();
	    $data = $entity;//实际上传入的是更新数据，不是对象了。
	
	    $params['useCache'] = false;
	    $entity = self::findOneBy($params);
	    $_isOld = is_object($entity) ? true : false;
	
	    $entity = self::dataToEntity($data, $_isOld ? $entity : null);
	
	    if($_isOld) {
	        self::modify($entity);
	    } else {
	        self::create($entity);
	    }
	
	    return $entity;
	}
	
	/**
	 * 数据提交的时候传递的参数中有_batch的时候，即表明所传的参数中在_batch的值中的就要进行拆分
	 * @param array $data
	 */
	public function _checkBatch(array $data)
	{
	    if (isset($data['_batch'])&&$data['_batch'])
	    {
	        $datas = array();
	        
	        //需要拆分的字段
	        $fields = explode(',', $data['_batch']);
	        
	        $times = 0;
	        if (isset($data[$fields[0]]))
	            $times = count(explode(',', $data[$fields[0]]));
	        
	        //复制数据
	        while ($times>0)
	        {
	            $datas[] = $data;
	            $times--;
	        }

	        foreach ($fields as $k=>$v)
	        {
	            $values[$v] = explode(',', $data[$v]);
	        }
	        
	        //修改数据
	        foreach ($datas as $k=>$v)
	        {
	            foreach ($fields as $kk=>$vv)
	            {
	                $datas[$k][$vv] = $values[$vv][$k];
	            }
	        }

	        return $this->batchadd($datas);
	    }
	    return false;
	}
/////////////////////////////////////////////////////////////////////

	//[BC] 清理。请使用findby()
	public function findBy1(array $criteria, array $orderBy = null, $limit = 2000, $offset = 1, $groupBy='')
	{
	    unset($criteria['_attribute']);
	    return self::findBy($criteria, $orderBy, $limit, $offset, $groupBy);
	}
	
	//[BC] 清理。请使用 delete($ids)
	public function batchdelete(array $entities) {}
	
	//[BC] 清理。请使用getClassMetadata()
	public function getMetadata()
	{
	    return $this->getClassMetadata($this->table);
	}
	//[BC] 清理。请使用getEntityManager()
	public function getObjectManager()
	{
	    return $this->sqlManager->getEntityManager();
	}
	
	//[BC] 清理
	protected function _handleMap(array &$map)
	{
	    $user = self::getUser();
	    $mid = method_exists($user, 'getMid')?(int)$user->getMid():0;
	
	    if($mid==1)
	        return false;
	
	    $attributes = $this->get('cc')->getAttributes();
	
	    $menus = isset($attributes['menus'])&&$attributes['menus']?$attributes['menus']:array(0);
	
	    $map['id']['in'] = $menus?array_keys($menus):array(0);
	    $map['_multi'] = false;
	
	    return true;
	}
	
	//[BC] 清理,请使用 batchupdate 方法
	public function batchModify(array $data)
	{
	    return self::batchupdate($data);
	
	}	
	//[BC] 即将清除
	public function readdelete($id, $info='') {
	    return self::realDelete($id, $info);
	}	
	//[BC] 即将清除
	public function add1(array $data, $entity = null)
	{
	    return self::systemAdd($data, $entity);
	}
	// [BC]  即将清除,请使用 update 方法
	public function updateEntity($entity, array $data)
	{
	    if(!method_exists($entity, 'getId')) {
	        throw new \LogicException('数据不存在或已被删除');
	    }
	    return self::update($entity->getId(),$data,$entity,false);
	}	
	//[BC]
	public function checkInfo(array $criteria)//createIfNone
	{
	    return self::createOneIfNone($criteria);
	}
    //[BC] 清理
	public function addImport(array $data, $entity=null, $isValid=true)
	{
	    return self::add($data, $entity, false);
	}				
	//[BC] 清理
	public function init() {}
    
	//[BC] 清理
	public function setStoragetype($type) {}
	
	//[BC] 清理
	public function setWriteAttach() {}
	
	//[BC] 清理
	public function setReadAttach($storagetype=null) {}
	//[BC] 清理
	public function updateByParam(array $param, array $data){}	

}