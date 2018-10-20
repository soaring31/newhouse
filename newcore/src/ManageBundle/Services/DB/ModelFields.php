<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年12月28日
*/
namespace ManageBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
* 方案字段
* 
*/
class ModelFields extends AbstractServiceManager
{
    protected $table = 'ModelFields';
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
    }

    public function add(array $data,  $info=null, $isValid=true)
    {
        $this->_fieldType($data);

        if(!isset($data['name'])||empty($data['name']))
            throw new \InvalidArgumentException('字段名称不能为空');

        //检测系统保留字
        $this->_reservedWord($data['name']);

        //判断字段是否已存在
        $count=$this->count(array('name'=>$data['name'],'plan_id'=>$data['plan_id']));

        if($count>0)
            throw new \InvalidArgumentException(sprintf('[%s]字段已存在', $data['name']));

        return parent::add($data, null, $isValid);
    }


    public function update($id, array $data, $info=null, $isValid=true)
    {
        if(isset($data['type']))
            $this->_fieldType($data);

        if(isset($data['name']))
            $this->_reservedWord($data['name']);

        return parent::update($id, $data, null, $isValid);
    }

    private function _fieldType(array &$data)
    {
        $_type = array();
        $_type['integer']  = "integer";
        $_type['numeric']  = "integer";
        $_type['bigint']   = "bigint";
        $_type['text']     = "text";
        $_type['textarea'] = "text";
        $_type['date']     = "date";
        $_type['time']     = "time";
        $_type['datetime'] = "integer";
        $_type['bool']     = "boolean";
        $_type['boolean']  = "boolean";
        $_type['select']   = "integer";
        $_type['radio']    = "smallint";
        $_type['smallint'] = "smallint";
        $_type['editor']   = "text";
        $_type['blob']     = "text";
        $_type['file']     = "text";
        $_type['float']    = "float";

        $data['field'] = isset($data['type'])&&isset($_type[$data['type']])?$_type[$data['type']]:'string';

        //默认字段长度
        if(!isset($data['length'])||(int)$data['length']<=0)
        {
            switch($data['field'])
            {
                case 'bigint':
                    $data['length'] = 19;
                    break;
                case 'integer':
                case 'date':
                case 'datetime':
                    $data['length'] = 10;
                    break;
                case 'smallint':
                    $data['length'] = 2;
                    break;
                case 'boolean':
                    $data['length'] = 1;
                    break;
                case 'blob':
                case 'text':
                    $data['length'] = 0;
                    break;
                default:
                    $data['length'] = 100;
                    break;
            }
        }

        if(in_array($data['type'], array('blob','text')))
            $data['length'] = 0;
    }

    /**
     * mysql保留字
     */
    private function _reservedWord($str)
    {
        $word = array();
        $word['ADD'] = "";
        $word['ALL'] = "";
        $word['ALTER'] = "";
        $word['ANALYZE'] = "";
        $word['AND'] = "";
        $word['AS'] = "";
        $word['ASC'] = "";
        $word['ASENSITIVE'] = "";
        $word['BEFORE'] = "";
        $word['BETWEEN'] = "";
        $word['BIGINT'] = "";
        $word['BINARY'] = "";
        $word['BLOB'] = "";
        $word['BOTH'] = "";
        $word['BY'] = "";
        $word['CALL'] = "";
        $word['CASCADE'] = "";
        $word['CASE'] = "";
        $word['CHANGE'] = "";
        $word['CHAR'] = "";
        $word['CHARACTER'] = "";
        $word['CHECK'] = "";
        $word['COLLATE'] = "";
        $word['COLUMN'] = "";
        $word['CONDITION'] = "";
        $word['CONNECTION'] = "";
        $word['CONSTRAINT'] = "";
        $word['CONTINUE'] = "";
        $word['CONVERT'] = "";
        $word['CREATE'] = "";
        $word['CROSS'] = "";
        $word['CURRENT_DATE'] = "";
        $word['CURRENT_TIME'] = "";
        $word['CURRENT_TIMESTAMP'] = "";
        $word['CURRENT_USER'] = "";
        $word['CURSOR'] = "";
        $word['DATABASE'] = "";
        $word['DATABASES'] = "";
        $word['DAY_HOUR'] = "";
        $word['DAY_MICROSECOND'] = "";
        $word['DAY_MINUTE'] = "";
        $word['DAY_SECOND'] = "";
        $word['DEC'] = "";
        $word['DECIMAL'] = "";
        $word['DECLARE'] = "";
        $word['DEFAULT'] = "";
        $word['DELAYED'] = "";
        $word['DELETE'] = "";
        $word['DESC'] = "";
        $word['DESCRIBE'] = "";
        $word['DETERMINISTIC'] = "";
        $word['DISTINCT'] = "";
        $word['DISTINCTROW'] = "";
        $word['DIV'] = "";
        $word['DOUBLE'] = "";
        $word['DROP'] = "";
        $word['DUAL'] = "";
        $word['EACH'] = "";
        $word['ELSE'] = "";
        $word['ELSEIF'] = "";
        $word['ENCLOSED'] = "";
        $word['ESCAPED'] = "";
        $word['EXISTS'] = "";
        $word['EXIT'] = "";
        $word['EXPLAIN'] = "";
        $word['FALSE'] = "";
        $word['FETCH'] = "";
        $word['FLOAT'] = "";
        $word['FLOAT4'] = "";
        $word['FLOAT8'] = "";
        $word['FOR'] = "";
        $word['FORCE'] = "";
        $word['FOREIGN'] = "";
        $word['FROM'] = "";
        $word['FULLTEXT'] = "";
        $word['GOTO'] = "";
        $word['GRANT'] = "";
        $word['GROUP'] = "";
        $word['HAVING'] = "";
        $word['HIGH_PRIORITY'] = "";
        $word['HOUR_MICROSECOND'] = "";
        $word['HOUR_MINUTE'] = "";
        $word['HOUR_SECOND'] = "";
        $word['IF'] = "";
        $word['IGNORE'] = "";
        $word['IN'] = "";
        $word['INDEX'] = "";
        $word['INFILE'] = "";
        $word['INNER'] = "";
        $word['INOUT'] = "";
        $word['INSENSITIVE'] = "";
        $word['INSERT'] = "";
        $word['INT'] = "";
        $word['INT1'] = "";
        $word['INT2'] = "";
        $word['INT3'] = "";
        $word['INT4'] = "";
        $word['INT8'] = "";
        $word['INTEGER'] = "";
        $word['INTERVAL'] = "";
        $word['INTO'] = "";
        $word['IS'] = "";
        $word['ITERATE'] = "";
        $word['JOIN'] = "";
        $word['KEY'] = "";
        $word['KEYS'] = "";
        $word['KILL'] = "";
        //$word['LABEL'] = "";
        $word['LEADING'] = "";
        $word['LEAVE'] = "";
        $word['LEFT'] = "";
        $word['LIKE'] = "";
        $word['LIMIT'] = "";
        $word['LINEAR'] = "";
        $word['LINES'] = "";
        $word['LOAD'] = "";
        $word['LOCALTIME'] = "";
        $word['LOCALTIMESTAMP'] = "";
        $word['LOCK'] = "";
        $word['LONG'] = "";
        $word['LONGBLOB'] = "";
        $word['LONGTEXT'] = "";
        $word['LOOP'] = "";
        $word['LOW_PRIORITY'] = "";
        $word['MATCH'] = "";
        $word['MEDIUMBLOB'] = "";
        $word['MEDIUMINT'] = "";
        $word['MEDIUMTEXT'] = "";
        $word['MIDDLEINT'] = "";
        $word['MINUTE_MICROSECOND'] = "";
        $word['MINUTE_SECOND'] = "";
        $word['MOD'] = "";
        $word['MODIFIES'] = "";
        $word['NATURAL'] = "";
        $word['NOT'] = "";
        $word['NO_WRITE_TO_BINLOG'] = "";
        $word['NULL'] = "";
        $word['NUMERIC'] = "";
        $word['ON'] = "";
        $word['OPTIMIZE'] = "";
        $word['OPTION'] = "";
        $word['OPTIONALLY'] = "";
        $word['OR'] = "";
        $word['ORDER'] = "";
        $word['OUT'] = "";
        $word['OUTER'] = "";
        $word['OUTFILE'] = "";
        $word['PRECISION'] = "";
        $word['PRIMARY'] = "";
        $word['PROCEDURE'] = "";
        $word['PURGE'] = "";
        $word['RAID0'] = "";
        $word['RANGE'] = "";
        $word['READ'] = "";
        $word['READS'] = "";
        $word['REAL'] = "";
        $word['REFERENCES'] = "";
        $word['REGEXP'] = "";
        $word['RELEASE'] = "";
        $word['RENAME'] = "";
        $word['REPEAT'] = "";
        $word['REPLACE'] = "";
        $word['REQUIRE'] = "";
        $word['RESTRICT'] = "";
        $word['RETURN'] = "";
        $word['REVOKE'] = "";
        $word['RIGHT'] = "";
        $word['RLIKE'] = "";
        $word['SCHEMA'] = "";
        $word['SCHEMAS'] = "";
        $word['SECOND_MICROSECOND'] = "";
        $word['SELECT'] = "";
        $word['SENSITIVE'] = "";
        $word['SEPARATOR'] = "";
        $word['SET'] = "";
        $word['SHOW'] = "";
        $word['SMALLINT'] = "";
        $word['SPATIAL'] = "";
        $word['SPECIFIC'] = "";
        $word['SQL'] = "";
        $word['SQLEXCEPTION'] = "";
        $word['SQLSTATE'] = "";
        $word['SQLWARNING'] = "";
        $word['SQL_BIG_RESULT'] = "";
        $word['SQL_CALC_FOUND_ROWS'] = "";
        $word['SQL_SMALL_RESULT'] = "";
        $word['SSL'] = "";
        $word['STARTING'] = "";
        $word['STRAIGHT_JOIN'] = "";
        $word['TABLE'] = "";
        $word['TERMINATED'] = "";
        $word['THEN'] = "";
        $word['TINYBLOB'] = "";
        $word['TINYINT'] = "";
        $word['TINYTEXT'] = "";
        $word['TO'] = "";
        $word['TRAILING'] = "";
        $word['TRIGGER'] = "";
        $word['TRUE'] = "";
        $word['UNDO'] = "";
        $word['UNION'] = "";
        $word['UNIQUE'] = "";
        $word['UNLOCK'] = "";
        $word['UNSIGNED'] = "";
        $word['UPDATE'] = "";
        $word['USAGE'] = "";
        $word['USE'] = "";
        $word['USING'] = "";
        $word['UTC_DATE'] = "";
        $word['UTC_TIME'] = "";
        $word['UTC_TIMESTAMP'] = "";
        $word['VALUES'] = "";
        $word['VARBINARY'] = "";
        $word['VARCHAR'] = "";
        $word['VARCHARACTER'] = "";
        $word['VARYING'] = "";
        $word['WHEN'] = "";
        $word['WHERE'] = "";
        $word['WHILE'] = "";
        $word['WITH'] = "";
        $word['WRITE'] = "";
        $word['X509'] = "";
        $word['XOR'] = "";
        $word['YEAR_MONTH'] = "";
        $word['ZEROFILL'] = "";
        $word['findType'] = "";
        $word['pageIndex'] = "";
        $word['pageSize'] = "";
        $word['order'] = "";
        $word['orderBy'] = "";
        $word['autoQuery'] = "";
        $word['identifier'] = "";
        $word['create_time'] = "";
        $word['update_time'] = "";
        $word['is_delete'] = "";
        $word['checked'] = "";
        $word['useCache'] = "";
        $word['attributes'] = "";

        if(isset($word[strtoupper($str)]))
            throw new \InvalidArgumentException(sprintf('[%s]为系统保留字', $str));
    }
}