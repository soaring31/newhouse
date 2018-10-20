<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package Tripod
* create date 2015-6-9
*/
namespace CoreBundle\Functions;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;


/**
 * 表前缀处理服务
 */
class TablePrefixSubscriber implements EventSubscriber
{
	protected $prefix = '';

	/**
	 * Constructor
	 *
	 * @param string $prefix
	 */
	public function __construct($prefix)
	{
		$this->prefix = (string) $prefix;
	}

	/**
	 * Get subscribed events
	 *
	 * @return array
	 */
	public function getSubscribedEvents()
	{
		return array('loadClassMetadata');
	}

	/**
	 * Load class meta data event
	 *
	 * @param LoadClassMetadataEventArgs $args
	 *
	 * @return void
	 */
	public function loadClassMetadata(LoadClassMetadataEventArgs $args)
	{
		$classMetadata = $args->getClassMetadata();
		//$classMetadata->setIdGeneratorType(1);

		//描述错位修正
		foreach($classMetadata->fieldMappings as &$v)
		{

			if (isset($v['comment'])) {
				if(!isset($v['options'])) $v['options'] = array();
				$v['options']['comment'] = $v['comment'];
				unset($v['comment']);
			}
			if (isset($v['unsigned'])) {
				if(!isset($v['options'])) $v['options'] = array();
				$v['options']['unsigned'] = $v['unsigned'];
				unset($v['unsigned']);
			}
		}
		
		// 不要再使用前缀继承层次结构中的
		if ($classMetadata->isInheritanceTypeSingleTable() && !$classMetadata->isRootEntity()) {
			return;
		}
		
		//从YML访问数据库,需要加上前缀
		if($classMetadata->namespace)
		{
			if (false === strpos($classMetadata->getTableName(), $this->prefix)) {
				$tableName = $this->prefix . $classMetadata->getTableName();
				$classMetadata->setPrimaryTable(array('name' => $tableName));
			}
			
			foreach ($classMetadata->getAssociationMappings() as $fieldName => $mapping) {
				if ($mapping['type'] == ClassMetadataInfo::MANY_TO_MANY && $mapping['isOwningSide'] == true) {
					$mappedTableName = $classMetadata->associationMappings[$fieldName]['joinTable']['name'];
			
					// Do not re-apply the prefix when the association is already prefixed
					if (false !== strpos($mappedTableName, $this->prefix)) {
						continue;
					}
			
					$classMetadata->associationMappings[$fieldName]['joinTable']['name'] = $this->prefix . $mappedTableName;
				}
			}
			
		//从数据库生成YML文件，需要去掉表前缀
		}else{
			if (false !== strpos($classMetadata->getTableName(), $this->prefix)) {
				$tableName = str_replace(array($this->prefix),"",$classMetadata->getTableName());
				$classMetadata->setTableName($tableName);
				$classMetadata->name = $this->ucWords($tableName);
				if($classMetadata->name=='MembersSub')
				{
				    $ClassMetadataBuilder = new ClassMetadataBuilder($classMetadata);
				    $association = $ClassMetadataBuilder->createOneToOne('Members', 'Members');
				    $association->addJoinColumn('mid', 'mid');
				    $association->build();
				}
                if($classMetadata->name!='Members'&&$classMetadata->name!='MembersSub'&&strpos($classMetadata->name,'Members')===0){
                     $ClassMetadataBuilder = new ClassMetadataBuilder($classMetadata);
                     $association = $ClassMetadataBuilder->createOneToOne('MembersSub', 'MembersSub');
                     $association->addJoinColumn('mid', 'mid');
                     $association->build();
                }
			}			
		}
	}
	
	/**
	 * psr-0标准
	 * @param string $str
	 * @return string
	 */
	private function ucWords($str)
	{
		$str = ucwords(str_replace('_', ' ', $str));
		$str = str_replace(' ', '', $str);
		return $str;
	}
}