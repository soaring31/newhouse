<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package Tripod
* create date 2015-3-20
*/
namespace CoreBundle\Model;

use Symfony\Component\DependencyInjection\ContainerInterface;

class Database
{
	/**
	 * @var ContainerInterface
	 *
	 * @api
	 */
	protected static $prefix;
	protected static $container;
	
	public function __construct(ContainerInterface $container)
	{
	    static::$container = $container;
	    static::$prefix = $this->get('core.common')->getTblprefix();
	    //取出sql和dql调试语句
	    //return $query->getDQL();
	    //return $query->getSQL();
	}

	/**
	 * 附加表前缀方法
	 * @param string $tableName
	 * return string 返回再表前缀的表名
	 */
	public static function tableName($tableName=null)
	{
		if(empty($tableName))
		    throw new \InvalidArgumentException("无效的表名【".$tableName."】！！！");
	
		return isset(static::$prefix)?static::$prefix.$tableName:$tableName;
	}
	
	/**
	 * 从容器中取得DBAL服务
	 * @return object
	 */
	public static function connection()
	{
		return static::$container->get('database_connection');
	}
	
	/**
	 * 取得查询字段集
	 * 数组转换为字符串
	 * @param array $data
	 * @return string
	 */
	private static function extractDataValues(array $data)
	{
		$extractData = count($data)>0?implode(',', array_values($data)):'*';
		if(count($data)>0&&!isset($data['id']))
		    $extractData = "id,".$extractData;
		
		return $extractData;
	}
	
	/**
	 * 取得假删除字段
	 * @param string $tableName
	 * @return array()
	 */
	private static function extractDeleteValues($tableName)
	{
		//假删除数据 0为正常，1为删除
		$data = array();
		$data['is_delete']		= 1;
        $data['delete_time']	= time();       

        //创建假删除字段
        static::createIsDeleteValues($tableName, $data);
		return $data;
	}
	
	/**
	 * 自动创建假删除字段
	 * @param string $tableName
	 * @param array $data
	 */
	private static function createIsDeleteValues($tableName, array $data)
	{
		$dataAssoc	= array();

		//判断假删除字段是否存在，不存在则自动创建
		$SQL = "SHOW COLUMNS FROM ".static::tableName($tableName);
		$_dataAssoc = static::connection()->fetchAll($SQL);
		foreach($_dataAssoc as $k=>$v)
		{
			$dataAssoc[$v['Field']] = true;
		}
		$data = array();
		$data['is_delete']		= 0;
		$data['delete_time']	= time();
		//判断is_delete,delete_time是否存在，如果不存在，则创建
		foreach($data as $k=>$v){
			if(!array_key_exists($k,$dataAssoc)){
				switch($k){
					case 'is_delete':
						$SQL = "ALTER TABLE ".static::tableName($tableName)." ADD is_delete tinyint(1) not null COMMENT '0正常，1假删除'";
						break;
					case 'delete_time':
						$SQL = "ALTER TABLE ".static::tableName($tableName)." ADD delete_time int(10) not null COMMENT '删除时间'";
						break;
					default:
						$SQL = "";
						break;
				}
				static::connection()->query($SQL);
			}
		}
		
		return true;
	}
	
	/**
	 * 取得非删除数据的过滤条件
	 * @return array()
	 */
	private static function extractFilteValues($tableName)
	{
		//假删除数据 0为正常，1为删除
		$data = array();
		$data['is_delete'] = 0;

		//创建假删除字段
		static::createIsDeleteValues($tableName, $data);
		return $data;
	}
	
	/**
	 * 取得非删除数据的过淲条件
	 * @return string
	 */
	private static function extractActiveValues($tableName)
	{
		$criteria    = array();
		$filteValues = static::extractFilteValues($tableName);
    	foreach ($filteValues as $columnName=>$columnVal) {
    		$criteria[] = $columnName ." = '".$columnVal."'";
    	}
    	
    	return $criteria;
	}
	
	/**
     * 执行一个SQL查询和关联数组返回多条结果
     *字段索引数组
     * @param string $sql			SQL 查询语句.
     * @param array  $data			查询字段。数组格式
     * @param array  $identifier	查询参数。
     * @return array
     */
	public static function fetchAll($tableName
	    ,array $data = array()
	    ,array $identifier = array()
	    ,array $orderBy = array()
	    ,$pageSize = 8
	    ,$pageIndex = 0
	    ,$groupBy=null)
	{
	    $criteria = array();
		$data = static::extractDataValues($data);
		
		
		//判断是否为数组
		if(is_array($identifier)){
		    foreach ($identifier as $columnName=>$columnVal) {
		        $criteria[] = $columnName ." = '".$columnVal."'";
		    }
		    $identifier = count($criteria)>0 ?"AND ".implode(' AND ', $criteria):'';
		    //释放
		    $criteria = array();
		}else{
		    $identifier = $identifier?"AND ".$identifier:"";
		}

		$SQL = "SELECT {$data} FROM {$tableName} WHERE 1=1 {$identifier} ORDER BY id LIMIT {$pageIndex},{$pageSize}";
		return static::connection()->fetchAll($SQL);
	}
    
    /**
     * 执行一个SQL查询和关联数组返回一条结果
     * @param string $tableName		操作表名
     * @param array $data			操作字段，数组格式
     * @param string $identifier	操作条件
     * @return Array()
     */
    public static function fetchAssoc($tableName, array $data=array(), $identifier=null)
    {
    	//初始化
    	$criteria	= array();
    	$data 		= static::extractDataValues($data);

    	//判断是否为数组
    	if(is_array($identifier)){    		
    		foreach ($identifier as $columnName=>$columnVal) {
    			$criteria[] = $columnName ." = '".$columnVal."'";
    		}
    		$identifier = count($criteria)>0 ?"AND ".implode(' AND ', $criteria):'';
    		//释放
    		$criteria = array();
    	}else{
    		$identifier = $identifier?"AND ".$identifier:"";
    	}

    	//附加查询过淲
     	$identifier .= " AND ".implode(' AND ', static::extractActiveValues($tableName));
    	$SQL = "SELECT {$data} FROM {$tableName} WHERE 1=1 {$identifier}";
    	return static::connection()->fetchAssoc($SQL);
    }
    
    /**
     * 执行一个SQL查询和关联数组返回多条结果
     * 数字索引数组.
     *
	 * @param string $tableName		操作表名
     * @param array $data			操作字段，数组格式
     * @param string $identifier	操作条件
     * @return Array()
     *
     * @return array
     */
    public static function fetchArray($tableName, array $data=array(), $identifier=null)
    {
    	$data 		= static::extractDataValues($data);
    	$identifier = $identifier?"AND ".$identifier:"";
    	//附加查询过淲
    	$identifier .= " AND ".implode(' AND ', static::extractActiveValues($tableName));
    	$SQL		= "SELECT {$data} FROM ".static::tableName($tableName)." WHERE 1=1 {$identifier}";
    	return static::connection()->fetchArray($SQL);
    }
    
    /**
     * 执行一个SQL查询并返回一个单一的列的值
     * 结果的第一行.
     *
     * @param string $tableName		操作表名
     * @param array $data			操作字段，数组格式
     * @param string $identifier	操作条件，数组格式
     * @param integer $colnum    从0开始索引检索的列数.
     * @return mixed
     */
    public static function fetchColumn($tableName, array $data=array(), $identifier=null, $colnum = 0)
    {
    	$data 		= static::extractDataValues($data);
    	$identifier = $identifier?"AND ".$identifier:"";
    	//附加查询过淲
    	$identifier .= " AND ".implode(' AND ', static::extractActiveValues($tableName));
    	$SQL		= "SELECT {$data} FROM ".static::tableName($tableName)." WHERE 1=1 {$identifier}";
    	return static::connection()->fetchColumn($SQL, array(), $colnum);
    }
    
    /**
     * 执行一个SQL假删除语句（实现回收站功能）
     *
     * @param string $tableName		操作表名
     * @param string $identifier	操作条件，数组格式
     *
     * @return integer 受影响的行数.
     */
    public static function delete($tableName, array $identifier)
    {
    	return static::connection()->update(static::tableName($tableName), static::extractDeleteValues($tableName), $identifier);
    }
    
    /**
     * 执行一个SQL真删除语句（彻底从数据表中删掉）
     *
     * @param string $tableName		操作表名
     * @param string $identifier	操作条件。数组格式
     *
     * @return integer 受影响的行数.
     */
    public static function realdelete($tableName, array $identifier)
    {
    	//只允许删除有假删除标识的数据
    	$identifier['is_delete'] = 1;
    	return static::connection()->delete(static::tableName($tableName),$identifier);
    }
    
	/**
     * 执行一个SQL更新语句
     *
     * @param string $tableName  操作表名。
     * @param array  $data       操作字段。数组格式.
     * @param array  $identifier 操作的条件。数组格式.
     *
     * @return integer The number of affected rows.
     */
    public static function update($tableName, array $data, array $identifier)
    {
    	return static::connection()->update(static::tableName($tableName), $data, $identifier);
    }
    
    /**
     * 执行一个SQL插入语句.
     *
     * @param string $tableName 操作表名。
     * @param array  $data      操作字段。数组格式.
     *
     * @return integer The number of affected rows.
     */
    public static function insert($tableName, array $data)
    {    	
    	return static::connection()->insert(static::tableName($tableName), $data);
    }
    
    /**
     * 获得服务
     * @param int $id
     * @throws \InvalidArgumentException
     */
    protected static function get($id)
    {
        /**
         * 兼容3.0之前的版本request服务
         */
        if($id=='request')
            return static::$container->get('request_stack')->getCurrentRequest();

        if (!static::$container->has($id))
            throw new \InvalidArgumentException("[".$id."]服务未注册。");
    
        return static::$container->get($id);
    }
}