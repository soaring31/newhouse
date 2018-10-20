<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年6月22日
*/
namespace OAuthBundle\Services\Baidu;

use CoreBundle\Services\ServiceBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class BaiduMap extends ServiceBase
{
    protected $resourceOwner;
    protected $errorCode;
    protected $access_key;
    protected $geotable_id;
    protected $name = 'baidumap';   

    #数据表操作
    protected $listUrl = 'http://api.map.baidu.com/geodata/v3/geotable/list';
    protected $createTabUrl = 'http://api.map.baidu.com/geodata/v3/geotable/create';
    protected $getTabUrl = 'http://api.map.baidu.com/geodata/v3/geotable/detail';
    protected $updateTabUrl = 'http://api.map.baidu.com/geodata/v3/geotable/update';
    protected $deleteTabUrl = 'http://api.map.baidu.com/geodata/v3/geotable/delete';

    #位置点的操作
    protected $createUrl = 'http://api.map.baidu.com/geodata/v3/poi/create';
    protected $getUrl = 'http://api.map.baidu.com/geodata/v3/poi/list';
    protected $getPoiUrl = 'http://api.map.baidu.com/geodata/v3/poi/detail';

    #单个表字段间的操作
    protected $addTabUrl = 'http://api.map.baidu.com/geodata/v3/column/create';
 
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;  
        $this->resourceOwner = $this->get('oauth.security.oauth_utils')->getResourceOwner($this->name);
        $errorCode = new ErrorCode();
        $this->errorCode = $errorCode->getText();
        $this->access_key = $this->resourceOwner->getOption('client_secret');
        $this->geotable_id = $this->resourceOwner->getOption('client_id');
    }

    /**
     * 错误检测
     * @param string $info
     * @throws \LogicException
     */
    private function getError($info)
    {
        if(is_object($info) && !empty($info->status))
            throw new \LogicException(isset($this->errorCode[$info->status])?$this->errorCode[$info->status]:$info->message);

        if(is_array($info) && !empty($info['status']))
            throw new \LogicException(isset($this->errorCode[$info['status']])?$this->errorCode[$info['status']]:'['.$info['message'].']');
    }

    /**
     * 
     * 新建一个位置点
     *
     * @param array $input  位置相关数据
     */
    public function createPoi(array $input, $geotable_id = NULL)
    {
        if(!isset($input['latitude']) || !isset($input['longitude']))
            throw new \InvalidArgumentException('_缺少参数经度或者纬度: latitude/longitude');
        if(!isset($input['coord_type']))
            throw new \InvalidArgumentException('_缺少参数用户上传的坐标的类型: coord_type');
        if(!isset($input['title']))
            throw new \InvalidArgumentException('_缺少参数坐标点的名称: title');

        $input['ak'] = $this->access_key;
        $input['geotable_id'] = empty($geotable_id) ? $this->geotable_id : (int)$geotable_id;
        $result = $this->resourceOwner->doGetRequest($this->createUrl, $input);
        $this->getError($result);

        return $result;
    }

    /**
     * 
     * 根据title查询指定条件的位置点数据(一般会有多条)
     *
     * @param string $title        位置的名称 (模糊查询，类似strstr())
     * @param int    $geotable_id  保存表的id（不填将选取默认设置）
     * @param int    $page_index   分页索引 默认为0
     * @param int    $page_size    分页数目 默认为10，上限为200
     * @return Object
     */
    public function getPoiByTitle($title, $geotable_id = NULL, $page_index = 0, $page_size = 20)
    {
        if(!isset($title))
            throw new \InvalidArgumentException('_缺少参数坐标点的名称: title');

        $input = array();
        $input['ak'] = $this->access_key;
        $input['title'] = trim($title);
        $input['page_index'] = (int)$page_index;
        $input['page_size'] = (int)$page_size;
        $input['geotable_id'] = empty($geotable_id) ? $this->geotable_id : (int)$geotable_id;

        $result = $this->resourceOwner->doGetRequest($this->getUrl, $input, false);     
        $this->getError($result);

        return $result;
    }

    /**
     * 
     * 根据id(主键)查询指定条件的位置点数据(一定是唯一一条)
     *
     * @param string   $id           位置的id (主键唯一)
     * @param int      $geotable_id  保存表的id（不填将选取默认）
     * @return Object
     */
    public function getPoiById($id, $geotable_id = NULL)
    {
        if(empty($id))
            throw new \InvalidArgumentException('_缺少参数主键id: id');

        $input = array();
        $input['ak'] = $this->access_key;
        $input['id'] = trim($id);
        $input['geotable_id'] = empty($geotable_id) ? $this->geotable_id : (int)$geotable_id;

        $result = $this->resourceOwner->doGetRequest($this->getPoiUrl, $input, false);     
        $this->getError($result);

        return $result;
    }

    /**
     * 
     * 获取数据表的列表
     *
     * @param string $name  geotable的名字 (可选)
     */
    public function getList($name = '')
    {
        $input = array();
        $input['ak'] = $this->access_key;
        if(!empty($name))
            $input['name'] = $name;

        $result = $this->resourceOwner->doGetRequest($this->listUrl, $input, false);
        $this->getError($result);

        return $result;
    }

    /**
     * 
     * 新建表
     *
     * @param string $name          geotable的名字 (可选)
     * @param int    $is_published  0：未自动发布到云检索 1：自动发布到云检索；
     * @param int    $geotype       1：点；2：线；3：面。默认为1（当前不支持“线”）
     */
    public function createGeoTable($name, $is_published = 1, $geotype = 1)
    {
        if(empty($name))
            throw new \InvalidArgumentException('_缺少参数geotable的名字: name');

        $input = array();
        $input['ak'] = $this->access_key;
        $input['name'] = trim($name);
        $input['is_published'] = (int)$is_published;
        $input['geotype'] = (int)$geotype;

        $result = $this->resourceOwner->doGetRequest($this->createTabUrl, $input);
        $this->getError($result);

        return $result;
    }

    /**
     * 
     * 查找一个表的情况(geotable_id)
     *
     * @param int    $geotable_id       表的id
     */
    public function getGeoTable($geotable_id = NULL)
    {
        $input = array();
        $input['ak'] = $this->access_key;
        $input['id'] = empty($geotable_id) ? $this->geotable_id : (int)$geotable_id;

        $result = $this->resourceOwner->doGetRequest($this->getTabUrl, $input, false);
        $this->getError($result);

        return $result;
    }

    /**
     * 
     * 给表增加一个字段
     *
     * @param array   $input         字段相关数据
     * @param int    $geotable_id    表的id
     */
    public function addGeoTableColumn(array $input, $geotable_id = NULL)
    {
        if(!isset($input['name']))
            throw new \InvalidArgumentException('_缺少参数column的属性中文名称: name');
        if(!isset($input['key']))
            throw new \InvalidArgumentException('_缺少参数column存储的属性key: key');
        if(!isset($input['type']))
            throw new \InvalidArgumentException('_缺少参数存储的值的类型: type');
        if(!isset($input['max_length']))
            throw new \InvalidArgumentException('_缺少参数最大长度(type=string生效): max_length');
        if(!isset($input['is_sortfilter_field']))
            throw new \InvalidArgumentException('_缺少参数是否检索引擎的数值排序筛选字段: is_sortfilter_field');
        if(!isset($input['is_search_field']))
            throw new \InvalidArgumentException('_缺少参数是否检索引擎的文本检索字段: is_search_field');
        if(!isset($input['is_index_field']))
            throw new \InvalidArgumentException('_缺少参数是否存储引擎的索引字段: is_index_field');

        $input['ak'] = $this->access_key;
        $input['id'] = empty($geotable_id) ? $this->geotable_id : (int)$geotable_id;
        
        $result = $this->resourceOwner->doGetRequest($this->createUrl, $input);
        $this->getError($result);

        return $result;
    }

    /**
     * 
     * 修改表
     *
     * @param string $name          geotable的名字 (可选)
     * @param int    $is_published  0：未自动发布到云检索 1：自动发布到云检索；
     * @param int    $geotype       1：点；2：线；3：面。默认为1（当前不支持“线”）
     */
    public function updateGeoTable($name, $geotable_id = NULL, $is_published = 1, $geotype = 1)
    {
        if(empty($name))
            throw new \InvalidArgumentException('_缺少参数geotable的名字: name');

        $input = array();
        $input['ak'] = $this->access_key;
        $input['name'] = trim($name);
        $input['is_published'] = (int)$is_published;
        $input['geotype'] = empty($geotable_id) ? $this->geotable_id : (int)$geotable_id;

        $result = $this->resourceOwner->doGetRequest($this->updateTabUrl, $input);
        $this->getError($result);

        return $result;
    }

    /**
     * 
     * 删除表(geotable_id)注：当geotable里面没有有效数据时，才能删除geotable
     *
     * @param int    $geotable_id       表的id
     */
    public function deleteGeoTable($geotable_id)
    {
        if(empty($geotable_id))
            throw new \InvalidArgumentException('_缺少参数geotable的名字: name');

        $input = array();
        $input['ak'] = $this->access_key;
        $input['id'] = (int)$geotable_id;

        $result = $this->resourceOwner->doGetRequest($this->deleteTabUrl, $input);
        $this->getError($result);

        return $result;
    }

    
}