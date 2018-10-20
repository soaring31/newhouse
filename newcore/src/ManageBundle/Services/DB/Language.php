<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年06月28日
*/
namespace ManageBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
* 语言
* 
*/
class Language extends AbstractServiceManager
{
    protected $table = 'Language';
    protected $stag, $filePath;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
        
        //缓存标识,以数据库名称区别
        $this->stag = $this->get('core.common')->C('database_name')."_language";
        
        $this->filePath = $this->get('core.common')->getSiteRoot()."Config".DIRECTORY_SEPARATOR."config_language.yml";
    }

    /**
     * 添加
     * @param array $data
     * @return int id
     */
    public function add(array $data,  $info=null, $isValid=true)
    {
        return self::handleYmlData(parent::add($data, $info, $isValid));
    }
    
    /**
     * 更新
     * @param int	$id		基于ID的更新条件
     * @param array $data	更新的参数
     * @return bool
     */
    public function update($id, array $data, $info=null, $isValid=true)
    {
        return self::handleYmlData(parent::update($id, $data, $info, $isValid));
    }
    
    public function handleYmlData($data)
    {
        $result = array();
        $map = array();
        $map['status'] = 1;
        $map['order'] = 'sort|asc,id|asc';
        $info = parent::findBy($map, null, 10000);
    
        if(isset($info['data']))
        {
            foreach($info['data'] as $item)
            {
                $result['id'][$item->getId()]['name'] = $item->getName();
                $result['id'][$item->getId()]['ename'] = $item->getEname();
                $result['id'][$item->getId()]['checked'] = $item->getChecked();
                $result['id'][$item->getId()]['attributes'] = $item->getAttributes();
                $result['id'][$item->getId()]['issystem'] = $item->getIssystem();
                $result['id'][$item->getId()]['tplid'] = $item->getTplid();
                $result['id'][$item->getId()]['membertpl'] = $item->getMembertpl();
                $result['id'][$item->getId()]['userdb'] = $item->getUserdb();
                $result['id'][$item->getId()]['identifier'] = $item->getIdentifier();

                $result['ename'][$item->getEname()]['name'] = $item->getName();
                $result['ename'][$item->getEname()]['ename'] = $item->getEname();
                $result['ename'][$item->getEname()]['checked'] = $item->getChecked();
                $result['ename'][$item->getEname()]['attributes'] = $item->getAttributes();
                $result['ename'][$item->getEname()]['issystem'] = $item->getIssystem();
                $result['ename'][$item->getEname()]['tplid'] = $item->getTplid();
                $result['ename'][$item->getEname()]['membertpl'] = $item->getMembertpl();
                $result['ename'][$item->getEname()]['userdb'] = $item->getUserdb();
                $result['ename'][$item->getEname()]['identifier'] = $item->getIdentifier();

                $result['identifier'][$item->getIdentifier()]['id'] = $item->getId();
            }
        }
    
        $this->get('core.ymlParser')->ymlWrite($result, $this->filePath);
    
        unset($info);
    
        //重置缓存
        $this->get('core.common')->S($this->stag, $result, 86400);
        return $data;
    }
    
    /**
     * 直接从文件中读取
     * @param array $criteria
     * @return multitype:
     */
    public function getData($str, $flag=null)
    {
        $info = $this->get('core.common')->S($this->stag);
    
        if(empty($info))
        {
            $info = $this->get('core.ymlParser')->ymlRead($this->filePath);
    
            $this->get('core.common')->S($this->stag, $info, 86400);
        }
        if(empty($info))
            return array();
    
        switch($flag)
        {
            case 'ename':
                $info = isset($info['ename'])?$info['ename']:array();
                break;
            default:
                $info = isset($info['id'])?$info['id']:array();
                break;
        }

        return isset($info[$str])?$info[$str]:array();
    }
}