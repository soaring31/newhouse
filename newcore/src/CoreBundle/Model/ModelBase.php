<?php
/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package Tripod
 * create date 2015-6-8
 */

namespace CoreBundle\Model;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
use CoreBundle\Services\ServiceBase;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * model基类
 * 基于 table 的操作，但可以切换表，也会需要关联(JOIN)表
 * 与 bundle 的关系,需要指定 bundle 来查找 metadata
 *
 * 如果关联表，或join 表，都需要在同一个connection上
 * 暂支持单个 EntityManager，稍做整理可支侍多 EntityManager 之间的互动
 *
 */
//在 AbstractServiceManager 中除非 setTables 才新建本类实例，而setTables基本是在调用db服务时执行，
//这样的话，同一个db服务，本类实例是同一个，里面大多属性在本实例的调用中都是延续的，包括 queryBuilder
//要用核查新建一个查询，需要createQueryBuilder创建新实例，同时配以 mapRequest 以重置本实例的各种属性
// 如果需要针对一张复制表表进行操作//????

class ModelBase extends ServiceBase
{
    protected $container;
    protected $entityManager;       //Doctrine\ORM\EntityManager
    protected $queryBuilder;        //Doctrine\ORM\QueryBuilder
    protected $repository;          //Doctrine\ORM\EntityRepository

    protected $table;
    protected $bundle;
    protected $prefix;

    protected $pageIndex = 1;
    protected $pageSize = 1;
    protected $pageSkip = 1;
    protected $order = array('sort' => 'asc', 'id' => 'asc');
    protected $orderBy = 'DESC';
    protected $hydrationMode = 1;
    protected $useCache = true;
    protected $groups = array();

    /**
     * 在这里，$table 可以定位 metadata，从而指定 repository
     * $bundle 也只是帮助定位 metadata,只是 $table 中的一部分
     * EM的 id 并没有通过参数传入，而是强行指定。从而 conn 也是强行指定的。
     */
    public function __construct(ContainerInterface $container, $table, $bundle)
    {
        $this->table = $table;
        $this->bundle = strtolower(str_replace('Bundle', '', $bundle));
        $this->container = $container;
        $this->prefix = $this->get('core.common')->getTblprefix();
        // ORM主入口，Doctrine\ORM\EntityManager
        // 只使用 doctrine.default_entity_manager
        $this->entityManager = $this->get('doctrine')->getManager($this->get('core.common')->getUserDb());

        //取出sql和dql调试语句
        //return $query->getDQL();
        //return $query->getSQL();
    }

    /**
     * The EntityManager is the central access point to ORM functionality.
     *
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * [BC] 即将删除，请使用 getEntityManager()
     */
    public function getObjectManager()
    {
        return $this->getEntityManager();
    }

    /**
     * A repository for entities with generic as well as business specific methods for retrieving entities.
     *
     * @return Doctrine\ORM\EntityRepository
     */
    protected function getRepository()
    {
        return $this->repository;
    }

    public function getClass()
    {
        return $this->table;
    }

    /**
     * 读取表名
     */
    public function getTables()
    {
        return $this->getClass();
    }

    /**
     * 设置表名
     * @param string $tableName
     */
    public function setTables($tableName = null)
    {
        $this->table = $tableName ? $tableName : $this->getClass();
        $this->repository = $this->get('doctrine')->getRepository($this->table, $this->get('core.common')->getUserDb());
        $this->queryBuilder = $this->getRepository()->createQueryBuilder('p');
    }

    /**
     * psr-0命名规则
     * @param string $str
     * @return string
     */
    final public function ucWords($str)
    {
        return $this->get('core.common')->ucWords($str);
    }

    /**
     * 数据过滤整合
     */
    final public function filterData($class)
    {
        $identifier = $this->getClassMetadata($class)->getIdentifier();
        $columnNames = $this->getClassMetadata($class)->columnNames;
        unset($columnNames[$identifier[0]]);
        return $columnNames;
    }

    /**
     * 取得字段类型资料，指定$type，返回该类型标题，否则返回所有字段类型数组
     * @param string $type
     * @return Ambigous <multitype:string , string>
     */
    public function datatype($type = '')
    {
        return $this->get('core.common')->datatype($type);
    }

    /**
     * 嵌入表前缀
     * @param string $name
     */
    public function prefixName($name)
    {
        return $this->get('core.common')->prefixName($name);
    }

    /**
     * 过滤表前缀
     * @param string $name
     */
    public function unprefixName($name)
    {
        return $this->get('core.common')->unprefixName($name);
    }

    /**
     * 获得指定数据
     * @param int $id
     */
    public function findOneById($id)
    {
        return $this->getRepository()->find($id);
    }

    public function getNodeById($id)
    {
        //获得查询结果集
        return self::getResult();
    }

    /**
     * 获得所有的文档模型数据
     * @param array $criteria 查询条件
     */
    public function getTableByAll(array $criteria)
    {
        //初始化
        $result = array();
        $result['data'] = array();
        $result['pageCount'] = 0;
        $result['pageIndex'] = 1;

        //SQL参数
        self::mapRequest($criteria);

        //主表别名参数
        $aliasName = isset($criteria['aliasName']) ? $criteria['aliasName'] : 'p';

        $this->queryBuilder = $this->getRepository()->createQueryBuilder($aliasName);

        //获取表结构
        $metadata = self::getClassMetadata($this->table);

        //查询条件
        if (isset($criteria['query']) && is_array($criteria['query']))
            self::formulaFun($criteria['query'], $aliasName);
        else {
            //过淲假删除数据
            if (isset($metadata->fieldNames['is_delete']))
                $this->queryBuilder->andWhere($aliasName . '.is_delete = 0');
        }

        //是否有表关联
        self::_setJoinParam($criteria, $aliasName, $metadata);

        return self::getResult($aliasName);


        return $result;
    }

    /**
     * 表关联参数
     * @param array $data
     * @param QueryBuilder $query
     * @param string $aliasName
     * @param ClassMetadata $metadata
     */
    private function _setJoinParam(array $data, $aliasName, ClassMetadata $metadata)
    {
        //判断是否使用字段别名
        if (isset($data['alias']) && is_array($data['alias'])) {
            foreach ($data['alias'] as $k => $v) {
                $this->queryBuilder->addSelect($aliasName . '.' . $v . ' as ' . $k);
            }
        }

        if (isset($data['join']) && $data['join']) {
            //计数器
            $joinnum = 0;
            foreach ($data['join'] as $db => $val) {
                //最多五个关联
                if ($joinnum > 5) continue;

                if (!isset($val['aliasName']) || empty($val['aliasName'])) continue;

                //表关联类型(join, left join)
                $joinType = isset($val['joinType']) ? $val['joinType'] : 'join';
                if (!in_array($joinType, array('join', 'leftjoin'))) continue;

                //查询参数
                $param = "";

                //判断是否使用字段别名
                if (isset($val['alias']) && is_array($val['alias']) && count($val['alias']) > 0) {
                    foreach ($val['alias'] as $k => $v) {
                        $this->queryBuilder->addSelect($val['aliasName'] . '.' . $v . ' as ' . $k);
                    }
                } else {
                    $fieldList = $this->getClassMetadata($this->get('core.common')->prefixEntityName($db))->getFieldNames();
                    foreach ($fieldList as $field) {
                        $this->queryBuilder->addSelect($val['aliasName'] . '.' . $field . ' as ' . $field);
                    }
                }

                //组合查询条件
                if (isset($val['query']) && is_array($val['query']))
                    self::formulaFun($val['query'], $val['aliasName'], $param, 2);

                //查询条件
                if ($param) {
                    $this->queryBuilder->join($this->get('core.common')->prefixEntityName($db)
                        , $val['aliasName']
                        , 'WITH'
                        , $param);
                    $joinnum++;
                }
            }
        }
    }

    /**
     * 多条数据查询 (带分页的条件查询)
     * @param array $criteria 查询条件
     * @param array $orderBy 排序规则
     * @param int $limit 查询记录条数限制(每页显示数//???)
     * @param int $offset 从第几条记录开始查询(当前页码//???)
     * @param string $groupBy 分组
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = 2000, $offset = 1, $groupBy = '')
    {
        $criteria['pageSize'] = isset($criteria['pageSize']) ? $criteria['pageSize'] : $limit;//???
        $criteria['pageIndex'] = isset($criteria['pageIndex']) ? $criteria['pageIndex'] : $offset;//???
        $criteria['groupBy'] = isset($criteria['groupBy']) ? $criteria['groupBy'] : $groupBy;

        $_multi = isset($criteria['_multi']) ? $criteria['_multi'] : true;

        unset($criteria['_multi']);

        //排序
        if (is_array($orderBy)) {
            $order = "";
            foreach ($orderBy as $fieldName => $orientation) {
                $order .= ($order ? "," : "") . $fieldName . "|" . $orientation;
            }

            $criteria['order'] = isset($criteria['order']) && $criteria['order'] ? "," : "" . $order;
        }

        self::mapRequest($criteria);

        $this->queryBuilder = $this->getRepository()->createQueryBuilder('p');

        self::formulaFun($criteria, 'p');
        return self::getResult('p', $_multi);

    }

    /**
     * 单条数据查询
     * @param array $criteria
     * @param array $orderBy
     */
    public function findOneBy(array $criteria, array $orderBy = array())
    {
        $aliasName = isset($criteria['aliasName']) ? $criteria['aliasName'] : 'p';

        //排序
        if (is_array($orderBy) && $orderBy) {
            $order = "";
            foreach ($orderBy as $fieldName => $orientation) {
                $order .= ($order ? "," : "") . $fieldName . "|" . $orientation;
            }

            $criteria['order'] = isset($criteria['order']) && $criteria['order'] ? "," : "" . $order;
        }

        self::mapRequest($criteria);

        $this->queryBuilder = $this->getRepository()->createQueryBuilder($aliasName);

        self::formulaFun($criteria, $aliasName);

        $this->pageSkip = 0;
        $this->pageSize = 1;

        //获得查询结果集
        $info = self::getResult($aliasName, false);

        if (!isset($info['data']) || empty($info['data']))
            return array();

        return current($info['data']);

    }

    /**
     * 多条查询(无条件)
     */
    public function findAll()
    {
        return $this->getRepository()->findAll();
    }

    /**
     * 数量统计
     * @param array $criteria
     * @param string $groupBy
     */
    public function count(array $criteria, $groupBy = null)
    {
        //获取表结构
        $metadata = $this->getClassMetadata($this->table);
        self::mapRequest($criteria);
        $this->queryBuilder = $this->getRepository()->createQueryBuilder('p');

        self::formulaFun($criteria, 'p');

        if (is_array($groupBy)) {
            foreach ($groupBy as $groupName) {
                $this->queryBuilder->addGroupBy('p.' . $groupName);
            }
        }

        if (is_string($groupBy) && isset($metadata->fieldNames[$groupName]))
            $this->queryBuilder->addGroupBy('p.' . $groupName);

        $query = $this->queryBuilder->getQuery();
        self::_useCache($query);

        return self::_count($query);

    }


    /**
     * 新增一个实体对象
     * @param $entity Entity 对象
     */
    public function add($entity)
    {
        self::getEntityManager()->clear();
        self::checkObject($entity);

        $result = self::getEntityManager()->persist($entity);
        self::getEntityManager()->flush();
        self::getEntityManager()->clear();
        return $result;
    }

    /**
     * 批量新增实体对象
     * @param array $entities 实体对象数组
     */
    public function batchadd(array $entities)
    {
        self::getEntityManager()->clear();

        foreach ($entities as $entity) {
            self::checkObject($entity);
            self::getEntityManager()->persist($entity);
        }
        self::getEntityManager()->flush();
        self::getEntityManager()->clear();
    }

    /**
     * 更新一个实体对象
     * @param $entity Entity 对象
     * @return boolean
     */
    public function update($entity)
    {
        self::getEntityManager()->clear();
        self::checkObject($entity);
        self::getEntityManager()->merge($entity);
        self::getEntityManager()->flush();
        self::getEntityManager()->clear();

        return true;
    }

    /**
     * 批量修改多个实体对象
     * @param array $entities 包含多个Entity对象的数组
     */
    public function batchUpdate(array $entities)
    {
        self::getEntityManager()->clear();
        foreach ($entities as $entity) {
            self::checkObject($entity);
            self::getEntityManager()->merge($entity);
        }

        self::getEntityManager()->flush();
        self::getEntityManager()->clear();

        return true;
    }

    /**
     * 删除数据（假删除）
     * @param array $id 删除条件(id)
     * @return bool
     */

    /**
     * 删除一个实体对象（假删除）
     * @param $entity Entity对象
     * @return boolean
     */
    public function delete($entity)
    {
        self::checkObject($entity);

        self::getEntityManager()->clear();
        if (method_exists($entity, 'getIsDelete')) {
            $entity->setIsDelete(1);
            self::getEntityManager()->merge($entity);
        } else {
            self::getEntityManager()->remove($entity);
        }

        self::getEntityManager()->flush();
        self::getEntityManager()->clear();
        return true;
    }

    /**
     * 批量删除（假删除）多个 entity 对象
     * @param array $entities 实体对象数组
     */
    public function batchdelete(array $entities)
    {
        self::getEntityManager()->clear();
        foreach ($entities as $entity) {
            if (!is_object($entity)) continue;

            if (method_exists($entity, 'setIsDelete')) {
                $entity->setIsDelete(1);
                self::getEntityManager()->merge($entity);
            } else {
                self::getEntityManager()->remove($entity);
            }
        }

        self::getEntityManager()->flush();
        self::getEntityManager()->clear();
    }

    /**
     * 恢复删除数据（恢复假删除数据）
     * @param array $id 删除条件(id)
     * @return bool
     */
    public function undelete($entity)
    {
        self::checkObject($entity);
        self::getEntityManager()->clear();
        if (method_exists($entity, 'setIsDelete'))
            $entity->setIsDelete(0);

        self::getEntityManager()->merge($entity);
        self::getEntityManager()->flush();
        self::getEntityManager()->clear();
        //更新删除字段并返回bool
        return true;
    }

    /**
     * 批量恢复删除数据（恢复假删除数据）
     * @param array $id 删除条件(id)
     * @return bool
     */
    public function batchundelete($info)
    {
        self::getEntityManager()->clear();
        foreach ($info as $data) {
            if (method_exists($data, 'getIsDelete')) {
                $data->setIsDelete(0);
                self::getEntityManager()->merge($data);
            } else {
                self::getEntityManager()->remove($data);
            }
        }

        self::getEntityManager()->flush();
        self::getEntityManager()->clear();

        //更新删除字段并返回bool
        return true;
    }

    /**
     * 删除数据（真删除,将从数据表中把数据永久删除, 不可恢复）
     * @param array $data 查询条件
     * @return bool
     */
    public function realDelete($info)
    {
        if (!is_object($info) || $info->getIsDelete() != 1)
            return false;

// 	    self::getEntityManager()->clear();
        self::getEntityManager()->remove($info);
        self::getEntityManager()->flush();
        self::getEntityManager()->clear();
        return true;
    }

    /**
     * 批量真删除
     * @param array $entity
     */
    public function batchRealDelete(array $entities)
    {
        self::getEntityManager()->clear();
        foreach ($entities as $entity) {
            if (!is_object($entity) || $entity->getIsDelete() != 1) {
                continue;
            }

            self::getEntityManager()->remove($entity);
        }
        self::getEntityManager()->flush();
        self::getEntityManager()->clear();
    }

    /**
     * dbal的条件更新
     * @param array $data 更新的数据
     * @param array $params 查询的条件
     */
    public function dbalUpdate(array $data, array $params)
    {

        self::getEntityManager()->clear();

        //SQL参数
        self::mapRequest($params);

        //主表别名参数
        $aliasName = isset($data['aliasName']) ? $data['aliasName'] : 'p';

        $this->queryBuilder = self::getRepository()->createQueryBuilder($aliasName);

        //表结构
        $metadata = self::getClassMetadata($this->table);

        //更新内容
        //diff -
        //sum +
        //quot /
        //prod *
        foreach ($data as $k => $v) {
            if (!isset($metadata->fieldNames[$k]))
                continue;

            if (is_array($v))
                $this->queryBuilder->set("p." . $k, $this->queryBuilder->expr()->{key($v)}("p." . $k, end($v)));
            else
                $this->queryBuilder->set("p." . $k, "'$v'");
        }

        //查询条件
        foreach (array_keys($params) as $k) {
            if (!isset($metadata->fieldNames[$k]))
                unset($params[$k]);
        }

        self::formulaFun($params, $aliasName);

        $this->queryBuilder->update()->getQuery()->execute();

        return true;

    }

    /**
     * dbal的条件删除 [注] 可用于系统内部，还不是真正的 dbal 操作
     * @param array $params 查询的条件
     */
    public function dbalDelete(array $params)
    {
        //SQL参数
        self::mapRequest($params);

        //表结构
        $metadata = self::getClassMetadata($this->table);

        //查询条件
        foreach (array_keys($params) as $k) {
            if (isset($metadata->fieldNames[$k]))
                continue;

            unset($params[$k]);
        }

        $this->queryBuilder = self::getRepository()->createQueryBuilder('p');

        self::formulaFun($params, 'p');

        $this->queryBuilder->delete()->getQuery()->execute();

        return true;

    }

    /**
     * SQL参数
     * @param array $params
     */
    protected function mapRequest(array &$params)
    {
        //获得查询结果集,true为数组结果集，false为对象结果集
        if (isset($params['findType']) && (bool)$params['findType'] == true)
            $this->hydrationMode = 2;
        else
            $this->hydrationMode = 1;

        if (isset($params['useCache']) && (bool)$params['useCache'] == false)
            $this->useCache = false;
        else
            $this->useCache = true;

        //分页参数
        if (isset($params['pageIndex']))
            $this->pageIndex = (int)$params['pageIndex'];
        else
            $this->pageIndex = 1;

        if (isset($params['pageSize']))
            $this->pageSize = (int)$params['pageSize'];
        else
            $this->pageSize = 8;

        // Order By
        $this->orderBy = 'DESC';
        if (isset($params['orderBy'])) {
            switch (strtoupper($params['orderBy'])) {
                case 'ASC':
                    $this->orderBy = 'ASC';
                    break;
                case 'DESC':
                    $this->orderBy = 'DESC';
                    break;
            }
        }

        // group By
        $this->groups = array();
        if (isset($params['groupBy']) && $params['groupBy']) {
            //获取表结构
            $metadata = self::getClassMetadata($this->table);

            $groups = explode(',', $params['groupBy']);

            foreach ($groups as $group) {
                $groupField = explode('.', $group);
                if (!isset($metadata->fieldNames[end($groupField)]))
                    continue;

                $this->groups[] = $group;
            }
        }

        // Order
        $this->order = array('id' => 'asc');
        if (isset($params['order']) && $params['order']) {
            //获取表结构
            $metadata = self::getClassMetadata($this->table);

            $orders = explode(',', $params['order']);


            if (end($orders) == $params['order'])
                $orders = explode(' and ', $params['order']);

            $this->order = array();

            foreach ($orders as $_order) {
                $order = preg_split("/[\s|]+/", $_order);

                if (strtolower($order[0]) == 'case') {
                    $this->order[$_order] = $this->orderBy;
                    continue;
                }

                $orderField = explode('.', $order[0]);

                if (!isset($metadata->fieldNames[end($orderField)]))
                    continue;

                if (count($order) >= 2)
                    $this->order[$order[0]] = $order[1];
                else
                    $this->order[$order[0]] = $this->orderBy;
            }
        }

        $this->pageSkip = ($this->pageIndex - 1) * $this->pageSize;

        unset($params['order']);
        unset($params['orderBy']);
        unset($params['groupBy']);
        unset($params['findType']);
        unset($params['useCache']);
        unset($params['pageSize']);
        unset($params['pageIndex']);
    }

    /**
     * 运算符处理
     * @param array $info 查询条件
     * @param QueryBuilder $query
     * @param string $aliasName
     * @param string $param
     * @param number $flag 1-QueryBuilder方式，0-SQL
     * @param string $table ***Bundle:***
     */
    public function formulaFun(array $info, $aliasName = null, &$param = null, $flag = 1, $table = null)
    {
        $isDelete = false;
        $aliasName = $aliasName ? $aliasName . "." : "";
        $metadata = self::getClassMetadata($table ? $table : $this->table);

        //转换成数字的字段
        $_fieldType = array('integer', 'boolean', 'smallint', 'float');

        foreach ($info as $k => $item) {
            $k = explode('.', $k);
            $endk = end($k);

            $k = count($k) > 1 ? implode('.', $k) : $aliasName . end($k);

            if (!isset($metadata->fieldNames[$endk]))
                continue;


            $fieldType = isset($metadata->fieldMappings[$endk]['type']) ? $metadata->fieldMappings[$endk]['type'] : 'string';

            if ($endk == 'is_delete')
                $isDelete = true;

            if (is_array($item)) {
                foreach ($item as $kk => $vv) {
                    if ($vv == "no")
                        continue;
                    $isNumeric = true;
                    if ($vv === '_null')
                        $vv = 'null';

                    switch ($kk) {
                        //等于
                        case 'eq':
                            //不等于
                        case 'neq':
                            //小于
                        case 'lt':
                            //小于等于
                        case 'lte':
                            //大于
                        case 'gt':
                            //大于等于
                        case 'gte':
                        case 'like':
                        case 'notLike':
                            //判断字段结构是否为数字类型，非数字类型需加单引号
                            if (!is_array($vv)) {
                                //$_vv = explode('.', $vv);

                                if (!in_array($fieldType, $_fieldType))
                                    $isNumeric = false;
                            }

                            //if(is_numeric($vv))
                            //    $isNumeric = true;

                            if ($flag == 1)
                                $this->queryBuilder->andWhere($this->queryBuilder->expr()->$kk($k, $isNumeric ? $vv : "'" . $vv . "'"));
                            else
                                $param .= ($param ? " AND " : "") . $this->queryBuilder->expr()->$kk($k, $isNumeric ? $vv : "'" . $vv . "'");

                            break;
                        case 'in':
                        case 'notIn':
                            if ($flag == 1)
                                $this->queryBuilder->andWhere($this->queryBuilder->expr()->$kk($k, is_array($vv) ? $vv : explode(',', $vv)));
                            else
                                $param .= ($param ? " AND " : "") . $this->queryBuilder->expr()->$kk($k, is_array($vv) ? $vv : explode(',', $vv));

                            break;
                        case 'find':
                            $sql = " FIND_IN_SET(" . (is_numeric($vv) ? $vv : "'" . $vv . "'") . ", {$k})>0";

                            if ($flag == 1)
                                $this->queryBuilder->andWhere($sql);
                            else
                                $param .= ($param ? " AND " : "") . $sql;
                            break;
                        //统计
                        case 'count':
                            if (is_array($vv)) {
                                foreach ($vv as $vee) {
                                    $vee = explode('.', $vee);
                                    $endk = end($vee);

                                    $k = count($vee) > 1 ? implode('.', $vee) : $aliasName . end($vee);
                                    $this->queryBuilder->addSelect("count({$k}) as {$endk}");
                                }
                            } else {
                                $vee = explode('.', $vv);
                                $endk = end($vee);

                                $k = count($vee) > 1 ? implode('.', $vee) : $aliasName . end($vee);
                                $this->queryBuilder->addSelect("count({$k}) as {$endk}");
                            }
                            break;
                        //求和
                        case 'sum':
                            if (is_array($vv)) {
                                foreach ($vv as $vee) {
                                    $vee = explode('.', $vee);
                                    $endk = end($vee);

                                    $k = count($vee) > 1 ? implode('.', $vee) : $aliasName . end($vee);
                                    $this->queryBuilder->addSelect("sum({$k}) as {$endk}");
                                }
                            } else {
                                $vee = explode('.', $vv);
                                $endk = end($vee);

                                $k = count($vee) > 1 ? implode('.', $vee) : $aliasName . end($vee);
                                $this->queryBuilder->addSelect("sum({$k}) as {$endk}");
                            }

                            break;
                        //平均
                        case 'avg':
                            if (is_array($vv)) {
                                foreach ($vv as $vee) {
                                    $vee = explode('.', $vee);
                                    $endk = end($vee);

                                    $k = count($vee) > 1 ? implode('.', $vee) : $aliasName . end($vee);
                                    $this->queryBuilder->addSelect("avg({$k}) as {$endk}");
                                }
                            } else {
                                $vee = explode('.', $vv);
                                $endk = end($vee);

                                $k = count($vee) > 1 ? implode('.', $vee) : $aliasName . end($vee);
                                $this->queryBuilder->addSelect("avg({$k}) as {$endk}");
                            }

                            break;
                        //最大值
                        case 'max':
                            if (is_array($vv)) {
                                foreach ($vv as $vee) {
                                    $vee = explode('.', $vee);
                                    $endk = end($vee);

                                    $k = count($vee) > 1 ? implode('.', $vee) : $aliasName . end($vee);
                                    $this->queryBuilder->addSelect("max({$k}) as {$endk}");
                                }
                            } else {
                                $vee = explode('.', $vv);
                                $endk = end($vee);

                                $k = count($vee) > 1 ? implode('.', $vee) : $aliasName . end($vee);
                                $this->queryBuilder->addSelect("max({$k}) as {$endk}");
                            }

                            break;
                        //最小值
                        case 'min':
                            if (is_array($vv)) {
                                foreach ($vv as $vee) {
                                    $vee = explode('.', $vee);
                                    $endk = end($vee);

                                    $k = count($vee) > 1 ? implode('.', $vee) : $aliasName . end($vee);
                                    $this->queryBuilder->addSelect("min({$k}) as {$endk}");
                                }
                            } else {
                                $vee = explode('.', $vv);
                                $endk = end($vee);

                                $k = count($vee) > 1 ? implode('.', $vee) : $aliasName . end($vee);
                                $this->queryBuilder->addSelect("min({$k}) as {$endk}");
                            }
                            break;
                        //加
                        case 'sumX':
                            $values = end($vv);

                            $kks = explode('.', key($vv));
                            $kks = count($kks) > 1 ? implode('.', $kks) : $aliasName . end($kks);

                            if (is_array($values))
                                $values = $this->queryBuilder->expr()->{key($values)}($k . '+' . $kks, end($values));
                            else
                                $values = $k . ' + ' . $kks . ' = ' . $values;

                            if ($flag == 1)
                                $this->queryBuilder->andWhere($values);
                            else
                                $param .= ($param ? " AND " : "") . $values;
                            break;
                        //减
                        case 'diffX':
                            $values = end($vv);

                            $kks = explode('.', key($vv));
                            $kks = count($kks) > 1 ? implode('.', $kks) : $aliasName . end($kks);

                            if (is_array($values))
                                $values = $this->queryBuilder->expr()->{key($values)}($k . '-' . $kks, end($values));
                            else
                                $values = $k . ' - ' . $kks . ' = ' . $values;

                            if ($flag == 1)
                                $this->queryBuilder->andWhere($values);
                            else
                                $param .= ($param ? " AND " : "") . $values;
                            break;
                        //乘
                        case 'prodX':
                            $values = end($vv);

                            $kks = explode('.', key($vv));
                            $kks = count($kks) > 1 ? implode('.', $kks) : $aliasName . end($kks);

                            if (is_array($values))
                                $values = $this->queryBuilder->expr()->{key($values)}($k . '*' . $kks, end($values));
                            else
                                $values = $k . ' * ' . $kks . ' = ' . $values;

                            if ($flag == 1)
                                $this->queryBuilder->andWhere($values);
                            else
                                $param .= ($param ? " AND " : "") . $values;
                            break;
                        //除
                        case 'quotX':
                            $values = end($vv);

                            $kks = explode('.', key($vv));
                            $kks = count($kks) > 1 ? implode('.', $kks) : $aliasName . end($kks);

                            if (is_array($values))
                                $values = $this->queryBuilder->expr()->{key($values)}($k . '/' . $kks, end($values));
                            else
                                $values = $k . ' / ' . $kks . ' = ' . $values;

                            if ($flag == 1)
                                $this->queryBuilder->andWhere($values);
                            else
                                $param .= ($param ? " AND " : "") . $values;
                            break;
                        case 'concat':
                            if ($flag == 1)
                                $this->queryBuilder->andWhere($this->queryBuilder->expr()->$kk($k, $aliasName . $vv));
                            else
                                $param .= ($param ? " AND " : "") . $this->queryBuilder->expr()->$kk($k, $aliasName . $vv);
                            break;
                        case 'countDistinct':
                            if ($flag == 1)
                                $this->queryBuilder->andSelect($this->queryBuilder->expr()->$kk($k));
                            break;
                        case 'orX':
                            $sql = "";
                            foreach ($vv as $vitem) {
                                if (!is_array($vitem))
                                    continue;

                                foreach ($vitem as $ki => $vi) {
                                    $kk = 'eq';
                                    if (is_array($vi)) {
                                        $kk = key($vi);
                                        $vi = end($vi);
                                    }

                                    $ki = explode('.', $ki);
                                    $ki = count($ki) > 1 ? implode('.', $ki) : $aliasName . end($ki);

                                    //判断字段结构是否为数字类型，非数字类型需加单引号
                                    if (!is_array($vi) && !in_array($fieldType, $_fieldType))
                                        $isNumeric = false;

                                    //if(is_numeric($vi))
                                    //    $isNumeric = true;

                                    if ($kk == 'find') {
                                        $sql = " FIND_IN_SET(" . ($isNumeric ? $vi : "'" . $vi . "'") . ", {$ki})>0";

                                        if ($flag == 1)
                                            $this->queryBuilder->andWhere($sql);
                                        else
                                            $param .= ($param ? " AND " : "") . $sql;
                                    } else {
                                        $sql .= ($sql ? " OR " : "") . $this->queryBuilder->expr()->$kk($ki, $isNumeric ? $vi : "'" . $vi . "'");
                                    }
                                }
                            }

                            if (empty($sql))
                                continue;

                            if ($flag == 1)
                                $this->queryBuilder->andWhere($sql);
                            else
                                $param .= ($param ? " AND " : "") . "({$sql})";

                            break;
                        case 'andX':
                            foreach ($vv as $vitem) {
                                if (!is_array($vitem))
                                    continue;

                                foreach ($vitem as $ki => $vi) {
                                    $kk = 'eq';
                                    if (is_array($vi)) {
                                        $kk = key($vi);
                                        $vi = end($vi);
                                    }

                                    $ki = explode('.', $ki);
                                    $ki = count($ki) > 1 ? implode('.', $ki) : $aliasName . end($ki);

                                    //判断字段结构是否为数字类型，非数字类型需加单引号
                                    if (!is_array($vi) && !in_array($fieldType, $_fieldType))
                                        $isNumeric = false;

                                    //if(is_numeric($vi))
                                    //    $isNumeric = true;

                                    if ($flag == 1)
                                        $this->queryBuilder->andWhere($this->queryBuilder->expr()->$kk($ki, $isNumeric ? $vi : "'" . $vi . "'"));
                                    else
                                        $param .= ($param ? " AND " : "") . $this->queryBuilder->expr()->$kk($ki, $isNumeric ? $vi : "'" . $vi . "'");
                                }
                            }
                            break;
                        case 'between':
                            if (count($vv) > 1) {
                                if ($flag == 1)
                                    $this->queryBuilder->andWhere($this->queryBuilder->expr()->$kk($k, $vv[0], $vv[1]));
                                else
                                    $param .= ($param ? " AND " : "") . $this->queryBuilder->expr()->$kk($k, $vv[0], $vv[1]);
                            }

                            break;
                        case 'isNull':
                        case 'isNotNull':
                            if ($flag == 1)
                                $this->queryBuilder->andWhere($this->queryBuilder->expr()->$kk($k));
                            else
                                $param .= ($param ? " AND " : "") . $this->queryBuilder->expr()->$kk($k);

                            break;
                    }
                }
            } else {
                $isNumeric = true;
                //判断字段结构是否为数字类型，非数字类型需加单引号
                if (!is_array($item) && !in_array($fieldType, $_fieldType))
                    $isNumeric = false;

                //if(is_numeric($item))
                //    $isNumeric = true;

                $item = $isNumeric && !is_array($item) ? (int)$item : $item;

                if ($flag == 1)
                    $this->queryBuilder->andWhere($this->queryBuilder->expr()->eq($k, $isNumeric ? $item : "'" . $item . "'"));
                else
                    $param .= ($param ? " AND " : "") . $this->queryBuilder->expr()->eq($k, $isNumeric ? $item : "'" . $item . "'");
            }
        }

        //过淲假删除数据
        if (isset($metadata->fieldNames['is_delete']) && !$isDelete) {
            if ($flag == 1)
                $this->queryBuilder->andWhere($aliasName . 'is_delete = 0');
            else
                $param .= ($param ? " AND " : "") . $aliasName . "is_delete=0";
        }
    }

    public function dbalAdd(array $data)
    {
        $metadata = self::getClassMetadata($this->table);
        $table = $metadata->table['name'];
        $identifierFieldNames = self::getIdentifierFieldNames();

        foreach ($data as $k => $v) {
            if (in_array($k, $identifierFieldNames) || !in_array($k, $metadata->fieldNames)) {
                unset($data[$k]);
                continue;
            }
        }

        $result = self::getConnection()->insert($table, $data);
        return $result;
    }

    /**
     * UNION ALL 查询
     * @param array $tables
     * @param array $params
     */
    public function getUNIONSearch(array $tables, array $params, array $params1)
    {
        $result = array();
        $result['data'] = array();

        //SQL参数
        self::mapRequest($params['criteria']);

        $SQL = $_field = "";

        //取字段
        foreach ($tables as $table) {
            if (empty($table))
                continue;

            $fieldData = self::getClassMetadata($this->get('core.common')->prefixEntityName($table))->getFieldNames();

            if (empty($_field))
                $_field = $fieldData;
            else
                $_field = array_intersect($_field, $fieldData);
        }

        $field = implode(',', $_field);

        foreach ($tables as $table) {
            if (empty($table))
                continue;

            //子表
            $pretable = $this->get('core.common')->prefixName($table);
            $table = ucfirst($this->bundle) . "Bundle:" . $this->get('core.common')->ucWords($table);

            $SQL .= $SQL ? " UNION ALL " : "";
            $SQL .= " SELECT {$field} FROM {$pretable} as s WHERE 1=1";

            if (isset($params1['criteria']) && $params1['criteria']) {
                foreach (array_keys($params['criteria']) as $k) {
                    if (!in_array($k, $_field, true))
                        unset($params['criteria'][$k]);
                }

                self::formulaFun($params1['criteria'], 's', $SQL, 0, $table);
            }
        }

        if (!empty($SQL)) {
            $SQL = "SELECT {$field} FROM ({$SQL}) as p WHERE 1=1";

            //子表查询条件
            if (isset($params['criteria']) && $params['criteria']) {
                foreach (array_keys($params['criteria']) as $k) {
                    if (!in_array($k, $_field, true))
                        unset($params['criteria'][$k]);
                }

                self::formulaFun($params['criteria'], '', $SQL, 0, $table);
            }

            $SQL .= " ORDER BY create_time DESC LIMIT {$this->pageSkip},{$this->pageSize}";
        }

        $stmt = self::getConnection()->prepare($SQL);
        $stmt->execute();

        $result['data'] = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $result['pageCount'] = count($result['data']);
        $result['pageIndex'] = $this->pageIndex;
        $result['pageSize'] = $this->pageSize;

        return $result;
    }

    public function getBinaryTreeSearch(array $data)
    {
        $result = array();
        $result['data'] = array();

        //SQL参数
        self::mapRequest($data);

        $searchFun = isset($data['searchFun']) ? $data['searchFun'] : 'EXISTS';

        //子表
        $innerName = $this->get('core.common')->prefixEntityName($data['subTableName']);

        $innerSql = "SELECT c.id FROM {$innerName} as c, {$innerName} as u
	    WHERE c.is_delete=0 AND c.id=p.{$data['subJoinField']} AND c.left_node BETWEEN u.left_node AND u.right_node";

        //子表查询条件
        if (isset($data['query1']) && is_array($data['query1'])) {
            //获取子表表结构
            $metadata = self::getClassMetadata($innerName);

            foreach (array_keys($data['query1']) as $k) {
                if (isset($metadata->fieldNames[$k]))
                    continue;
                unset($data['query1'][$k]);
            }

            self::formulaFun($data['query1'], 'u', $innerSql, 0);
        }

        //主表
        $mainSql = "SELECT p FROM " . $this->table . " p WHERE 1=1 AND {$searchFun} ({$innerSql})";

        //主表查询条件
        if (isset($data['query']) && is_array($data['query'])) {
            //获取主表表结构
            $metadata = self::getClassMetadata($this->table);

            foreach (array_keys($data['query']) as $k) {
                if (!isset($metadata->fieldNames[$k]))
                    unset($data['query'][$k]);
            }

            self::formulaFun($data['query'], 'p', $mainSql, 0);
        }

        //排序
        $order = "";

        foreach ($this->order as $k => $v) {
            if (empty($order))
                $order .= " ORDER BY p." . $k . " " . $v;
            else
                $order .= ", p." . $k . " " . $v;
        }

        $mainSql .= $order;

        $query = self::getEntityManager()->createQuery($mainSql);

        //分页
        $query->setFirstResult($this->pageSkip)->setMaxResults($this->pageSize);

        self::_useCache($query);

        //获得查询结果集
        $result = array();

        //计算总数
        $result['pageCount'] = self::_count($query);
        $result['pageSize'] = $this->pageSize;
        $result['pageIndex'] = $this->pageIndex;
        $result['data'] = $query->getResult($this->hydrationMode);

        return $result;
    }


    /**
     * 查询
     * @param array $params
     */
    public function findByBuilder(array $select, array $from, array $where = array())
    {
        $query = isset($where['query']) ? $where['query'] : array();
        unset($where['query']);

        self::mapRequest($where);

        //主表别名参数
        $aliasName = isset($where['aliasName']) ? $where['aliasName'] : 'p';
        unset($where['aliasName']);

        $this->queryBuilder = self::getRepository()->createQueryBuilder($aliasName);

        $this->queryBuilder->select($select);

        foreach ($from as $key => $item) {
            $this->queryBuilder->from($key, $item);

            //过淲假删除数据
            $this->queryBuilder->andWhere($item . '.is_delete = 0');

            //获取表结构
            $metadata = self::getClassMetadata($key);

            //查询条件
            foreach (array_keys($where) as $k) {
                $k = explode('.', $k);
                if (count($k) == 1 || $k[0] == $item) {
                    $k = end($k);
                    if (isset($metadata->fieldNames[$k]))
                        continue;
                    unset($where[$k]);
                }
            }

            //查询条件
            foreach (array_keys($query) as $k) {
                $k = explode('.', $k);
                if (count($k) == 1 || $k[0] == $item) {
                    $k = end($k);
                    if (isset($metadata->fieldNames[$k]))
                        continue;
                    unset($where[$k]);
                }
            }
        }

        //获取表结构
        $metadata = self::getClassMetadata($this->table);

        //查询条件
        if ($query) {
            //关联表别名参数
            $_aliasName = isset($query['aliasName']) ? $query['aliasName'] : 'u';
            unset($query['aliasName']);

            foreach (array_keys($query) as $k) {
                $k = explode('.', $k);
                if (count($k) == 1 || $k[0] == $_aliasName) {
                    $k = end($k);
                    if (isset($metadata->fieldNames[$k]))
                        continue;
                    unset($where[$k]);
                }
            }

            self::formulaFun($query, $_aliasName);

            unset($query);
        }

        //查询条件
        foreach (array_keys($where) as $k) {
            $k = explode('.', $k);
            if (count($k) == 1 || $k[0] == $aliasName) {
                $k = end($k);
                if (isset($metadata->fieldNames[$k]))
                    continue;
                unset($where[$k]);
            }
        }

        //排序
        foreach ($this->order as $k => $v) {
            $k = explode('.', $k);
            if (count($k) == 1 || $k[0] == $aliasName) {
                $k = end($k);
                if (!isset($metadata->fieldNames[$k]))
                    continue;

                $this->queryBuilder->addOrderBy($aliasName . "." . $k, $v);
            }
        }

        self::formulaFun($where, $aliasName);

        return self::getResult($aliasName);
    }

    /**
     * 内查询，返回数据数组，非对象数组
     * @param array $param
     */
    public function getInternalSearch(array $param = array())
    {
        $query = "";
        $query1 = "";
        $data = $this->get('request')->query->all();
        $tableName = $this->get('core.common')->prefixEntityName($param['tableName']);
        $metadata = self::getClassMetadata($tableName);
        $tableColumn = $metadata->columnNames;

        $subTableName = $this->get('core.common')->prefixEntityName($param['subTableName']);

        $metadata = self::getClassMetadata($subTableName);
        $subTableColumn = $metadata->columnNames;

        $param['order'] = isset($param['order']) && $param['order'] ? $param['order'] : 'id';

        //处理排序
        if (isset($param['order']) && $param['order']) {
            //获取表结构
            $metadata = self::getClassMetadata($this->table);

            $orders = explode(',', $param['order']);

            if (end($orders) == $param['order'])
                $orders = explode(' and ', $param['order']);

            foreach ($orders as $order) {
                $order = preg_split("/[\s|]+/", $order);

                $orderField = explode('.', $order[0]);

                if (!isset($tableColumn[end($orderField)]))
                    continue;

                $param['order'] = $param['aliasName'] . "." . $order[0];
                $param['orderBy'] = $this->orderBy;

                if (count($order) < 2)
                    continue;

                $param['orderBy'] = $order[1];
            }
        }

        foreach ($param['query'] as $k => $v) {
            if (!isset($tableColumn[$k])) {
                //如果字段不存在，则卸载字段
                unset($param['query'][$k]);
                continue;
            }
            if (!is_array($v))
                $param['query'][$k] = array('eq' => $v);
        }

        self::formulaFun($param['query'], $param['aliasName'], $query1, 0);

        foreach ($param['query1'] as $k => $v) {
            if (!isset($subTableColumn[$k])) {
                //如果字段不存在，则卸载字段
                unset($param['query1'][$k]);
                continue;
            }
        }
        //子表
        $subTableName = ucfirst($this->bundle) . "Bundle:" . $this->get('core.common')->ucWords($param['subTableName']);

        self::formulaFun($param['query1'], 's', $query, 0, $subTableName);

        $data = array();
        $data['pageCount'] = 0;
        $data['data'] = array();

        //分页
        $data['pageIndex'] = isset($param['pageIndex']) && $param['pageIndex'] > 0 ? (int)$param['pageIndex'] : 1;
        $data['pageSize'] = isset($param['pageSize']) && $param['pageSize'] > 0 ? (int)$param['pageSize'] : 100;

        if (!isset($param['tableName']) || empty($param['tableName']))
            return $data;

        if (!isset($param['subTableName']) || empty($param['subTableName']))
            return $data;

        //计算每次分页的开始位置
        $start = ($data['pageIndex'] - 1) * $data['pageSize'];

        $CONDITIONS = " ORDER BY {$param['order']} {$param['orderBy']} limit {$start}, {$data['pageSize']}";

        $query1 = $query1 ? " AND " . $query1 : "";

        $tableName = $this->get('core.common')->prefixName($param['tableName']);
        $subTableName = $this->get('core.common')->prefixName($param['subTableName']);

        $searchFun = isset($param['searchFun']) ? $param['searchFun'] : 'EXISTS';

        if ($query)
            $query = " AND {$query}";

        $subJoinField = isset($param['subJoinField']) ? explode("=", $param['subJoinField']) : '';

        switch (count($subJoinField)) {
            case 1:
                $param['query1'][$subJoinField[0]] = $param['aliasName'] . ".id";
                break;
            case 2:
                $param['query1'][$subJoinField[0]] = $subJoinField[1];
                break;
            default:
                $param['query1']['uid'] = $param['aliasName'] . ".id";
                break;
        }

        $query = " AND {$searchFun} ( SELECT " . (isset($subJoinField[0]) ? $subJoinField[0] : "uid") . " FROM {$subTableName} AS s WHERE
	    " . (isset($subJoinField[0]) ? ("s." . $subJoinField[0]) : "s.uid") . "=" . (isset($subJoinField[1]) ? $subJoinField[1] : "{$param['aliasName']}.id") . " {$query} )";

        $SQL = "SELECT COUNT(*) FROM {$tableName} AS {$param['aliasName']} WHERE 1=1{$query}{$query1} ";
        $SQL1 = "SELECT * FROM {$tableName} AS {$param['aliasName']} WHERE 1=1{$query}{$query1}{$CONDITIONS}";

        $stmt = self::getConnection()->prepare($SQL);
        $stmt->execute();
        $data['pageCount'] = $stmt->fetchColumn();

        if ($data['pageCount'] > 0) {
            $stmt = self::getConnection()->prepare($SQL1);

            $stmt->execute();

            $data['data'] = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        return $data;
    }

    /**
     * 获取二叉树节点
     * @param array $params
     */
    public function getNode(array $data)
    {
        //主表别名参数
        $aliasName = 'p';

        //SQL参数
        self::mapRequest($data);

        $this->queryBuilder = $this->getRepository()->createQueryBuilder($aliasName);

        $this->queryBuilder->from($this->table, 'u');

        $this->queryBuilder->andWhere($this->queryBuilder->expr()->between('p.left_node', 'u.left_node', 'u.right_node'));

        //查询条件
        if (isset($data['query']) && is_array($data['query'])) {
            //获取表结构
            $metadata = self::getClassMetadata($this->table);

            foreach (array_keys($data['query']) as $k) {
                if (!isset($metadata->fieldNames[$k]))
                    unset($data['query'][$k]);
            }
            self::formulaFun($data['query'], 'u');
        } else {
            //过淲假删除数据
            $this->queryBuilder->andWhere($aliasName . '.is_delete = 0');
        }

        return self::getResult($aliasName);
    }

    public function getSubNode(array $data)
    {
        $result = array();
        $result['data'] = array();

        //SQL参数
        self::mapRequest($data);

        //主表别名参数
        $aliasName = 'p';
        unset($data['aliasName']);

        $this->queryBuilder = self::getRepository()->createQueryBuilder($aliasName);

        $this->queryBuilder->innerJoin($this->table, 'c');
        $this->queryBuilder->andWhere($this->queryBuilder->expr()->between('p.left_node', 'c.left_node', 'c.right_node'));
        $this->queryBuilder->andWhere($this->queryBuilder->expr()->eq('p.right_node-p.left_node', 1));

        //排序参数
        $orderBy = array();
        if (isset($data['order'])) {
            $orderBy[$data['order']] = isset($data['orderBy']) ? $data['orderBy'] : "asc";

            unset($data['order']);
            unset($data['orderBy']);
        }

        //获取表结构
        $metadata = self::getClassMetadata($this->table);

        //查询条件
        if (isset($data['query']) && is_array($data['query'])) {
            foreach (array_keys($data['query']) as $k) {
                if (!isset($metadata->fieldNames[$k]))
                    unset($data[$k]);
            }
            self::formulaFun($data['query'], 'c');
        }

        //排序
        if (is_array($orderBy) && $orderBy) {
            foreach ($orderBy as $fieldName => $orientation) {
                if (!isset($metadata->fieldNames[$fieldName]))
                    continue;

                $this->queryBuilder->addOrderBy('p.' . $fieldName, $orientation);
            }
        }

        //分页
        $this->queryBuilder->setMaxResults($this->pageSize)->setFirstResult($this->pageSkip);
        $query = $this->queryBuilder->getQuery();
        self::_useCache($query);

        //获得查询结果集,true为数组结果集，false为对象结果集
        if ($this->findType)
            $result['data'] = $query->getArrayResult();
        else
            $result['data'] = $query->getResult();

        //计算总数
        $result['pageCount'] = self::_count($query);
        $result['pageSize'] = $this->pageSize;
        $result['pageIndex'] = $this->pageIndex;

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getTableName()
    {
        return self::unprefixName(self::getClassMetadata($this->table)->table['name']);
    }

    /**
     * 获得元数据，注意在本类中得到的全是原生的 metadata
     * @param string $class 格式：HouseBundle:Userinfo 或完全类名HouseBundle\Entity\Userinfo
     * @return object
     */
    final public function getClassMetadata($class)
    {
        return self::getEntityManager()->getClassMetadata($class);
    }

    /**
     * 获取主键
     */
    public function getIdentifierFieldNames()
    {
        return self::getClassMetadata($this->table)->getIdentifierFieldNames();
    }

    /**
     * 获取表字段
     */
    public function getExportFields()
    {
        return self::getClassMetadata($this->table)->getFieldNames();
    }

    public function getConnection()
    {
        return self::getEntityManager()->getConnection();
    }

    /**
     * @param $object
     *
     * @throws \InvalidArgumentException
     */
    protected function checkObject($object)
    {
        //重置ORM
        $this->get('doctrine')->resetManager();

        $entity = self::getClassMetadata($this->table)->name;

        if (!$object instanceof $entity) {
            throw new \InvalidArgumentException(sprintf(
                'Object must be instance of %s, %s given',
                $this->table, is_object($object) ? get_class($object) : gettype($object)
            ));
        }
    }

    /**
     * 删除二叉树节点
     * @param int $id
     */
    public function deleteNode($id)
    {
        $table = self::getTableName();
        $tableName = $this->get('core.common')->prefixName($table);

        $SQL = "SELECT left_node,right_node, right_node - left_node + 1 as width FROM {$tableName} WHERE id ={$id}";
        $stmt = self::getConnection()->prepare($SQL);
        $stmt->execute();
        $rs = $stmt->fetch();

        $left_node = $rs['left_node'];
        $right_node = $rs['right_node'];
        $width = $rs['width'];

        $stmts = array();
        $stmts[] = self::getConnection()->prepare("DELETE FROM {$tableName} WHERE left_node BETWEEN {$left_node} AND {$right_node}");
        $stmts[] = self::getConnection()->prepare("UPDATE {$tableName} SET right_node = right_node - {$width} WHERE right_node > {$right_node}");
        $stmts[] = self::getConnection()->prepare("UPDATE {$tableName} SET left_node = left_node - {$width} WHERE left_node > {$right_node}");

        foreach ($stmts as $stmt) {
            $stmt->execute();
        }

        return true;
    }

    /**
     * 基于结果集的输出
     * @param string $aliasName
     * @param string $multi
     * @return multitype:number NULL
     */
    protected function getResult($aliasName = 'p', $multi = true)
    {
        //获取表结构
        $metadata = self::getClassMetadata($this->table);

        //排序 (CASE WHEN sl.zjdate>=1493100826 THEN sl.zjdate WHEN sl.refreshdate>0 THEN sl.refreshdate ELSE sl.id END) AS HIDDEN ORD
        foreach ($this->order as $k => $v) {
            $order = preg_split("/[\s|]+/", $k);

            if (strtolower($order[0]) == 'case') {
                $this->queryBuilder->addSelect("({$k}) AS HIDDEN ORD ");
                $this->queryBuilder->addOrderBy('ORD', $v);
                continue;
            }

            if (!isset($metadata->fieldNames[$k]))
                continue;

            $this->queryBuilder->addOrderBy($aliasName . '.' . $k, $v);
        }

        //分组
        foreach ($this->groups as $groupName) {
            if (!isset($metadata->fieldNames[$groupName]))
                continue;

            $this->queryBuilder->addGroupBy($aliasName . '.' . $groupName);
        }

        //分页
        $this->queryBuilder->setFirstResult($this->pageSkip)->setMaxResults($this->pageSize);

        //计算总条数
        try {

            $query = $this->queryBuilder->getQuery();

            self::_useCache($query);

            $result = array();
            $result['pageSize'] = $this->pageSize;
            $result['pageIndex'] = $this->pageIndex;
            $result['data'] = $query->getResult($this->hydrationMode);

            //计算所有页的总数
            $result['pageCount'] = $multi ? self::_count($query) : count($result['data']);

            //返回结果集
            return $result;
        } catch (\Exception $e) {
            $this->get('core.common')->logicException($e);
        }
    }

    /**
     * 基于ID的回收站数据
     * @param int $id
     */
    public function getTrashById($id)
    {
        $info = $this->getRepository()->findById($id);

        return $info ? end($info) : "";
    }

    /**
     * 读取回收站多行数据(基本条件)
     * @param $criteria
     * @param $orderBy
     * @return multitype:number NULL
     */
    public function getTrash(array $criteria = array(), array $orderBy = null)
    {
        //排序
        if (is_array($orderBy)) {
            $order = "";
            foreach ($orderBy as $fieldName => $orientation) {
                $order .= ($order ? "," : "") . $fieldName . "|" . $orientation;
            }

            $criteria['order'] = isset($criteria['order']) && $criteria['order'] ? "," : "" . $order;
        }

        self::mapRequest($criteria);
        $this->queryBuilder = $this->getRepository()->createQueryBuilder('p');

        //获取表结构
        $metadata = self::getClassMetadata($this->table);

        //排序
        foreach ($this->order as $k => $v) {
            if (!isset($metadata->fieldNames[$k]))
                continue;

            $this->queryBuilder->addOrderBy('p.' . $k, $v);
        }

        $this->queryBuilder->andWhere('p.is_delete = :isDelete')->setParameter('isDelete', 1);

        //分页
        $this->queryBuilder->setFirstResult($this->pageSkip)->setMaxResults($this->pageSize);
        $query = $this->queryBuilder->getQuery();
        self::_useCache($query);

        //获得查询结果集
        $result = array();
        $result['pageCount'] = self::_count($query);
        $result['pageSize'] = $this->pageSize;
        $result['pageIndex'] = $this->pageIndex;
        $result['data'] = $query->getResult($this->hydrationMode);

        return $result;

    }

    /**
     * 获取当前查询的总数
     * @param $query Doctrine\ORM\Query;
     * @return integer
     */
    private function _count(Query $query)
    {
        $paginator = new Paginator($query, false);
        $count = $paginator->count();
        return $count;
    }

    /**
     * 判断是否启用缓存
     * @param Query $query
     */
    private function _useCache(Query $query)
    {
        if ($this->useCache) {
            $query->useResultCache(true, 1200);
        } else {
            $query->useQueryCache(false);//默认值是true
        }
    }

    public function __clone()
    {
    }

    //[BC] 即将清除
    public function systemAdd($entity)
    {
        return self::add($entity);
    }


}