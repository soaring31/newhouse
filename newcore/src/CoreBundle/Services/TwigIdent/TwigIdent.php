<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2015年9月28日
*/
namespace CoreBundle\Services\TwigIdent;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * 模版数据标识配置读取管理
 */
class TwigIdent extends \Twig_Loader_Filesystem
{
    protected $locator;
    protected $parser;
    protected $container;
    protected $filesystem;

    public function __construct(ContainerInterface $container, $templatepath)
    {
        $this->filesystem = new Filesystem();

        //判断文件夹是否存在，不存在则创建
        $identFilePath = $templatepath.DIRECTORY_SEPARATOR;

        if (!is_dir($identFilePath))
            $this->filesystem->mkdir($identFilePath);

        parent::__construct(array($identFilePath));

        $this->container= $container;
        $this->locator  = $container->get('templating.locator');
        $this->parser   = $container->get('templating.name_parser');
    }

    /**
     * 查找模版文件的yml配置文件
     * @param string $template
     */
    public function findTemplateYml($template, $filePath=null)
    {
        //初始化
	    $result = array();

	    //查询模版数据标识配置文件的绝对路径
	    $fileInfo = self::getYmlVal($template,$filePath);

	    if(empty($fileInfo))
	        return array();

	    $pageSize = $this->get('request')->get('pageSize', 8);
	    $pageIndex = $this->get('request')->get('pageIndex', 1);

        foreach($fileInfo as $db=>$item)
        {
            $item['pageSize'] = isset($item['pageSize'])?$item['pageSize']:$pageSize;
            $item['pageIndex'] = $pageIndex&&$item['pageSize']>1?$pageIndex:$item['pageIndex'];
            $item['order'] = isset($_REQUEST['order'])?trim($_REQUEST['order']):$item['order'];
            $item['orderBy'] = isset($_REQUEST['orderBy'])?trim($_REQUEST['orderBy']):$item['orderBy'];
            $item['findType'] = 1;
            $item['_attribute'] = isset($item['attribute'])?(bool)$item['attribute']:false;

            unset($item['attribute']);
            
            $_pageSize = $item['pageSize'];

            $dataModel = isset($item['dataModel'])?(int)$item['dataModel']:0;

            if($dataModel==1)
                $item['pageSize'] = '2000';

            //支持三元运算符(A?B)
            $item['tableName'] = $this->get('core.common')->getParams($item['tableName']);

            if(isset($item['subTableName']))
                $item['subTableName'] = $this->get('core.common')->getParams($item['subTableName']);

            if(isset($item['join'])&&is_array($item['join']))
            {
                foreach($item['join'] as $joink=>$joinv)
                {
                    $joinkk = $this->get('core.common')->getParams($joink);
                    if($joinkk )
                    {
                        unset($item['join'][$joink]);

                        //支持三元运算符(A?B)
                        $joink = $this->get('core.common')->getParams($joink);
                        $item['join'][$joinkk] = $joinv;
                    }
                }
            }

            //表名转换psr-0标准
            $tableName = $this->get('core.common')->ucWords($item['tableName']);

            //指定表
            $this->get('core.table_manage')->setTables($tableName);

            $model = $this->get('db.models')->getData($item['tableName'], 'name');

            if(empty($model))
            {
                throw new \InvalidArgumentException(sprintf("模型数据%s不存在或已被删除",$item['tableName']));
            }

            $serviceName = isset($model['service'])?$model['service']:'';

            if(empty($serviceName))
                throw new \InvalidArgumentException("模型服务数据不存在或已被删除");

            // 处理 GET 参数
            $url = $this->get('request')->getRequestUri();
            $info = parse_url($url);
            parse_str(isset($info['query']) ? $info['query'] : '', $REQUEST);
            
            // 处理路由参数
            $_route_params = $this->get('request')->attributes->get('_route_params','');
            if($_route_params) {
                foreach($_route_params as $k => $v) {
                    if(isset($REQUEST[$k]) && ($v == '' || $v == 'no' || $v == '0')) {
                        continue;
                    }
                    $REQUEST[$k] = $v;
                }
            }
            
            // 处理 POST 参数             
            if($this->get('request')->getMethod() == "POST") {
                $REQUEST = array_merge($REQUEST,$this->get('request')->request->All());
            }
            
            $REQUEST = $this->get('core.common')->getQueryParam($REQUEST);

            $metadata = $this->get($serviceName)->getClassMetadata();

            foreach($metadata->column as $v)
            {
                if(!isset($REQUEST[$v]))
                    continue;

                if(isset($item['autoQuery'])&&$item['autoQuery']==1)
                    $item['query'][$v] = isset($item['query'][$v])?$item['query'][$v]:$REQUEST[$v];

                if(isset($item['query'][$v]))
                {
                    if(is_numeric($item['query'][$v]))
                        continue;

                    if(is_array($item['query'][$v]))
                    {
                        foreach($item['query'][$v]  as $its)
                        {
                            if(is_numeric($its))
                                continue;

                            if($its===""||$its==="%%")
                                unset($item['query'][$v]);
                        }
                    }elseif($item['query'][$v]===""||$item['query'][$v]=="no"){
                        unset($item['query'][$v]);
                    }
                }
            }

            if(isset($item['query'])) {
                $item['query'] = $this->get('core.common')->getQueryArray($item['query']);
            }

            if (strstr($url,'news/list') || strstr($url,'cate_pushs=zhugezixun') || strstr($url,'cate_pushs=fczx') || strstr($url,'cate_pushs=zg_rhzx')){
                $item['query']['area'] = ["in"=>["{$_SESSION['_sf2_attributes']['area']}",'0']];
            }

            if(isset($item['query1'])) {
                $item['query1'] = $this->get('core.common')->getQueryArray($item['query1']);      
            }

            $item['order'] = isset($item['order'])&&$item['order']?$this->get('core.common')->getParams($item['order']):$item['order'];
            $item['findType'] = true;//强制模板标识的数据全为数组返回，不使用对象返回
            
            //获取结果
            switch($dataModel)
            {
                //内嵌查询
                case 2:
                    // 该方法只返回数组，不会返回对象
                    $result[$db] = $this->get($serviceName)->getInternalSearch($item);
                    
                    //判断是否查询属性表
                    if(isset($item['_attribute'])&&$item['_attribute'])
                        $result[$db] = $this->get($serviceName)->getAttributeTable($result[$db]);
                    break;
                //二叉树子查询
                case 3:
                    $result[$db] = $this->get($serviceName)->getNode($item);

                    $this->handleTree($item['pageIndex'], $item['pageSize'], $result[$db]);
                    break;
                //二叉树叶子查询
                case 4:

                    $result[$db] = $this->get($serviceName)->getBinaryTreeSearch($item);
                    break;
                default:
                    if($item['pageSize']==1)
                    {
                        $result[$db] = array();
                        $result[$db]['pageSize'] = 1;
                        $result[$db]['pageCount'] = 1;
                        $item['query']['aliasName'] = $item['aliasName'];
                        $item['query']['findType']  = isset($item['query']['findType'])?$item['query']['findType']:1;
                        $result[$db]['pageIndex'] = isset($item['pageIndex'])?(int)$item['pageIndex']:1;

                        $result[$db]['data'][0] = $this->get($serviceName)->findOneBy($item['query'],array(),$item['_attribute']);
                        if(empty($result[$db]['data'][0]))
                        {
                            $result[$db]['pageCount'] = 0;
                            $result[$db]['data'] = array();
                        }
                    }else
                        $result[$db] = $this->get($serviceName)->getTableByAll($item['pageIndex'], $item['pageSize'], $item);
                    break;
            }

            $item['listGrid'] = isset($item['listGrid'])?$item['listGrid']:array();

            //处理列表参数
            self::handleGrids($item['listGrid']);

            $result[$db]['listGrid'] = $item['listGrid'];
            $result[$db]['listDesc'] = isset($item['listDesc'])?$item['listDesc']:"";

            //处理树形结构
            if($dataModel==1)
                $this->handleTree($item['pageIndex'], $_pageSize, $result[$db]);
        }

        return $result;
    }

    /**
     * 查找模版文件的yml配置文件(基于ID)
     * @param string $template
     * @param string $id
     */
    public function findTemplateYmlById($template, $id)
    {
        //初始化
        $result = array();

        if(empty($id))
            return $result;

        //查询模版数据标识配置文件
        $fileInfo = self::getYmlVal($template);

        foreach($fileInfo as $db=>$item)
        {
            //表名转换psr-0标准
            $tableName = $this->get('core.common')->ucWords($item['tableName']);

            //指定表
            $this->get('core.table_manage')->setTables($tableName);

            //获取结果
            $result[$db] = $this->get('core.table_manage')->getTableById($id);
        }
        return $result;
    }

    /**
     * 获取yml文件的值
     * @param string $template
     */
    public function getYmlVal($template, $filePath=null)
    {
        //查询模版数据标识配置文件的绝对路径
        $filePath = $filePath?$filePath:self::getYml($template);

        //有则读出配置文件内容，无则赋值为空数组
        return $filePath?$this->get('core.ymlParser')->ymlRead($filePath):array();
    }

    /**
     * 查找yml配置文件
     * @param string $template
     */
    private function getYml($template)
    {
        $logicalName = (string) $template;

        if (isset($this->cache[$logicalName])) {
            return $this->cache[$logicalName];
        }

        $file = null;

        try {
            //获取文件名置(twig规则)
            $file = parent::findTemplate($logicalName);
        } catch (\Twig_Error_Loader $e) {
            //获取文件名置(symfony2规则)
            try {
                $template = $this->parser->parse($template);
                $file = $this->locator->locate($template);
            } catch (\Exception $e) {

            }
        }

        if (false === $file || null === $file)
            return '';

        //替换后缀名
        $file = str_replace("html.twig", "yml",$file);

        $file = str_replace("/views/", "/ident/", $file);

        self::createYmlFile($file);

        //返回文件路径
        return $this->cache[$logicalName] = $file;
    }

    public function createYmlFile($file)
    {
        if (!is_dir(dirname($file)))
            $this->filesystem->mkdir(dirname($file));

        //判断文件是否存在
        if(!is_file($file))
            $this->filesystem->touch($file);

        return true;
    }

    /**
     * 生成标识数据
     * @param string $pid
     * @param array $data
     */
    public function createIdent($pid, array $data, $views=null, $filePath=null)
    {
        if(!is_object($views)&&$filePath==null)
        {
            $map = array();
            $map['id'] = (int)$pid;
            $views = $this->get('db.views')->findOneBy($map);

            $name = is_object($views)?$views->getName():'';

            if(empty($name))
                throw new \InvalidArgumentException("模版数据不存在或已被删除");

            $filePath = is_object($views)?self::getYml($views->getName()):'';
        }
        if(empty($filePath))
            $filePath = is_object($views)?self::getYml($views->getName()):''; 

        $data['searchFun'] = isset($data['searchFun'])?$data['searchFun']:'EXISTS';

        $data['dataModel'] = isset($data['dataModel'])?(int)$data['dataModel']:0;

        $data['aliasName'] = isset($data['aliasName'])?trim($data['aliasName']):'p';

        $data['subTableName'] = isset($data['subTableName'])?trim($data['subTableName']):"";

        $data['subJoinField'] = isset($data['subJoinField'])?trim($data['subJoinField']):"";

        if(!isset($data['identName'])||empty($data['identName']))
            throw new \InvalidArgumentException("标识名称不能为空");

        if(!isset($data['tableName'])||empty($data['tableName']))
            throw new \InvalidArgumentException("表名称不能为空");

        if(!isset($data['aliasName'])||empty($data['aliasName']))
            throw new \InvalidArgumentException("别名不能为空");

        if($data['dataModel']==2&&empty($data['subTableName']))
            throw new \InvalidArgumentException("子表名称不能为空");

        if($data['dataModel']==2&&empty($data['subJoinField']))
            throw new \InvalidArgumentException("子表关联字段不能为空");

        $info = $this->get('core.ymlParser')->ymlRead($filePath);

        //把换行符、英文分号、中文分号、中文逗号等替换成英文逗号的正则表达式(\n)|(，)|(;)|(；)|
        $data['listGrid'] = isset($data['listGrid'])&&$data['listGrid']?preg_replace("/(\r\n)/" ,'\r\n' ,$data['listGrid']):'';

        $data['listGrid'] = $data['listGrid']?explode('\r\n',$data['listGrid']):array();

        $info[$data['identName']]['tableName'] = $data['tableName'];
        $info[$data['identName']]['subTableName'] = $data['subTableName'];
        $info[$data['identName']]['aliasName'] = $data['aliasName'];
        $info[$data['identName']]['listGrid'] = $data['listGrid'];
        $info[$data['identName']]['listDesc'] = $data['listDesc'];
        $info[$data['identName']]['subJoinField'] = $data['subJoinField'];
        $info[$data['identName']]['storagetype'] = isset($data['storagetype'])?(int)$data['storagetype']:3;
        $info[$data['identName']]['pageIndex'] = isset($data['pageIndex'])&&$data['pageIndex']?(int)$data['pageIndex']:1;
        $info[$data['identName']]['pageSize'] = isset($data['pageSize'])?(int)$data['pageSize']:8;
        $info[$data['identName']]['groupBy'] = isset($data['groupBy'])?trim($data['groupBy']):'';
        $info[$data['identName']]['order'] = isset($data['order'])?trim($data['order']):'';
        $info[$data['identName']]['orderBy'] = isset($data['orderBy'])?trim($data['orderBy']):'ASC';
        $info[$data['identName']]['searchFun'] = $data['searchFun'];
        $info[$data['identName']]['dataModel'] = $data['dataModel'];
        $info[$data['identName']]['attribute'] = isset($data['attribute']) ? $data['attribute'] : 0;
        $info[$data['identName']]['autoQuery'] = isset($data['autoQuery']) ? $data['autoQuery'] : 0;

        $info[$data['identName']]['alias'] = array();
        if(isset($data['dataAliasK']))
        {
            foreach($data['dataAliasK'] as $k=>$v)
            {
                $info[$data['identName']]['alias'][$v] = trim($data['dataAliasV'][$k]);
            }
        }

        $info[$data['identName']]['query'] = array();
        $info[$data['identName']]['query1'] = array();

        if(isset($data['dataQueryS']))
        {
            foreach($data['dataQueryS'] as $k=>$v)
            {
                $info[$data['identName']]['query'][$v][$data['dataQueryK'][$k]] = trim($data['dataQueryV'][$k]);
            }
        }

        if(isset($data['dataQueryS1']))
        {
            foreach($data['dataQueryS1'] as $k=>$v)
            {
                $info[$data['identName']]['query1'][$v][$data['dataQueryK1'][$k]] = trim($data['dataQueryV1'][$k]);
            }
        }

        //写入配置文件
        $this->get('core.ymlParser')->ymlWrite($info, $filePath);

        return true;
    }

    /**
     * 生成关联标识数据
     * @param string $pid
     * @param array $data
     */
    public function createJoin($pid, array $data)
    {
        $map = array();
        $map['id'] = (int)$pid;
        $views = $this->get('db.views')->findOneBy($map);
        $name = is_object($views)?$views->getName():'';

        if(empty($name))
            throw new \InvalidArgumentException("模版数据不存在或已被删除");

        if(!isset($data['identName'])||empty($data['identName']))
            throw new \InvalidArgumentException("标识参数错误");

        if(!isset($data['tableName'])||empty($data['tableName']))
            throw new \InvalidArgumentException("表名称不能为空");

        if(!isset($data['aliasName'])||empty($data['aliasName']))
            throw new \InvalidArgumentException("别名不能为空");

        $filePath = self::getYml($name);
        $info = $this->get('core.ymlParser')->ymlRead($filePath);

        if(!isset($info[$data['identName']]))
            throw new \InvalidArgumentException(sprintf("标识%s不存在或已被删除",$data['identName']));

        $info[$data['identName']]['join'][$data['tableName']] = array();
        $info[$data['identName']]['join'][$data['tableName']]['aliasName'] = $data['aliasName'];
        $info[$data['identName']]['join'][$data['tableName']]['joinType'] = isset($data['jointype'])?$data['jointype']:'join';

        $info[$data['identName']]['join'][$data['tableName']]['alias'] = array();
        if(isset($data['dataAliasK']))
        {
            foreach($data['dataAliasK'] as $k=>$v)
            {
                $info[$data['identName']]['join'][$data['tableName']]['alias'][$v] = trim($data['dataAliasV'][$k]);
            }
        }

        $info[$data['identName']]['join'][$data['tableName']]['query'] = array();
        if(isset($data['dataQueryS']))
        {
            foreach($data['dataQueryS'] as $k=>$v)
            {
                $info[$data['identName']]['join'][$data['tableName']]['query'][$v][$data['dataQueryK'][$k]] = trim($data['dataQueryV'][$k]);
            }
        }

        //写入配置文件
        $this->get('core.ymlParser')->ymlWrite($info, $filePath);

        return true;
    }

    /**
     *
     * @param int $pid
     * @param string $id
     */
    public function deleteIdent($pid, $id, $filePath=null)
    {
        if($filePath==null)
        {
            $map = array();
            $map['id'] = (int)$pid;
            $views = $this->get('db.views')->findOneBy($map);
            $name = is_object($views)?$views->getName():'';

            if(empty($name))
                return true;

            $filePath = self::getYml($name);
        }

        $info = $this->get('core.ymlParser')->ymlRead($filePath);

        unset($info[$id]);

        //写入配置文件
        $this->get('core.ymlParser')->ymlWrite($info, $filePath);

        return true;
    }

    /**
     *
     * @param int $pid
     * @param string $identName
     * @param string $tableName
     */
    public function deleteJoin($pid, $identName, $tableName, $filePath=null)
    {
        if(empty($identName))
            throw new \InvalidArgumentException("标识参数错误");

        if($tableName)
        {
            if($filePath==null)
            {
                $map = array();
                $map['id'] = (int)$pid;
                $views = $this->get('db.views')->findOneBy($map);
                $name = is_object($views)?$views->getName():'';

                if(empty($name))
                    throw new \InvalidArgumentException("模版数据不存在或已被删除");

                $filePath = self::getYml($name);
            }

            $info = $this->get('core.ymlParser')->ymlRead($filePath);

            unset($info[$identName]['join'][$tableName]);

            //写入配置文件
            $this->get('core.ymlParser')->ymlWrite($info, $filePath);
        }

        return true;
    }

    /**
     * 处理树形结构
     */
    public function handleTree($pageIndex, $pageSize, &$info)
    {
        if(!isset($info['data']))
            return false;
        $info['data'] = $this->get('core.common')->getTree($info['data'],0);
        $info['pageCount'] = count($info['data']);
        $info['pageSize'] = $pageSize;
        $info['data'] = $this->get('core.common')->pagination($pageIndex, $pageSize, $info['data']);
    }

    /**
     * 处理列表参数
     */
    public function handleGrids(&$grids)
    {
        $action = $this->get('core.common')->getActionName();
        foreach ( $grids as &$value ) {
            // 字段:标题:链接
            $val = explode ( ':', $value );
            // 支持多个字段显示
            $fields = explode ( ',', $val[0] );

            //字段处理
            foreach($fields as &$vo)
            {
                $voa = array();
                $matches = array();
                $voa['field'] = $vo;//lcfirst($this->get('core.common')->ucWords($vo));
                if (preg_match ( '/\[([a-zA-Z_0-9]+)\]/', $vo, $matches )) {
                    $voa['field'] = str_replace ( $matches[0], '', $vo );
                    $voa['func'] = $matches[1];
                }
                $vo = $voa;
            }

            $value = array (
                'fields' => $fields,
                'title' => array(),
                'action' => $action
            );

            $value['title']['params'] = $val[1];

            //标题处理
            $matches = array();

            if (preg_match('/\[([a-zA-Z_0-9]+)\]/', $value['title']['params'], $matches)) {
                $value['title']['params'] = str_replace ( $matches[0], '', $value['title']['params'] );
                $value['title']['func'] = $matches[1];
            }

            $value['title']['params'] = explode( '|', $value['title']['params'] );

            //链接处理
            if (isset ( $val[2] )) {
                // 链接信息
                $value['href'] = array();
                $links = explode ( ',', $val[2]);

                $i = 0;
                foreach ($links as $link)
                {
                    $params = "";
                    $funcname = "";

                    $matches = array();
                    if (preg_match('/\[([a-zA-Z_0-9]+)\]/', $link, $matches)) {
                        $params = str_replace ( $matches[0], '', $link );
                        $funcname = $matches[1];
                    }

                    if(empty($funcname))
                        continue;

                    $i++;

                    $value['href'][$i]['func'] = $funcname;
                    $value['href'][$i]['params'] = explode('|', $params);
                }
            }
        }
    }

    /**
     * 获得服务
     * @param int $id
     * @throws \InvalidArgumentException
     */
    protected function get($id)
    {
        /**
         * 兼容3.0之前的版本request服务
         */
        if($id=='request')
            return $this->container->get('request_stack')->getCurrentRequest();

        if (!$this->container->has($id))
            throw new \InvalidArgumentException("[".$id."]服务未注册。");

        return $this->container->get($id);
    }

    public function __clone(){}
}