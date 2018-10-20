<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年07月04日
*/
namespace ManageBundle\Services\DB;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;
use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

/**
* 版本库
* 
*/
class Version extends AbstractServiceManager
{
    protected $table = 'Version';
    protected $filePath, $filesystem;
    
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
        
        $this->filesystem = new Filesystem();
        $this->filePath = $this->get('core.common')->getSiteRoot()."Version".DIRECTORY_SEPARATOR;
    }
    
    public function createVersion()
    {
        $version = 200000;

        //查询上一个版本号
        $info = parent::findOneBy(array('_userFile'=>false),array('name'=>'desc'));
        
        $map = array();
        $map['findType'] = 1;
        
        if(is_object($info))
        {
            $version = (int)$info->getName()+1;
            $map['update_time']['gte'] = $info->getCreateTime();
        }
        
        $this->filePath .= $version.DIRECTORY_SEPARATOR;

        
        //判断存储文件夹是否存在，不存在则创建
        if(!$this->filesystem->exists($this->filePath)){
            try {
                //创建文件夹
                $this->filesystem->mkdir($this->filePath);
            } catch (IOExceptionInterface $e) {
                throw new \LogicException("An error occurred while creating your directory at ".$e->getPath());
            }
        }
        
        self::_createHandle($map);
        
        //生成数据成功后记录
        $data = array();
        $data['name'] = $version;
        $data['starttime'] = is_object($info)?$info->getCreateTime():time();
        $data['endtime'] = time();
        $data['path'] = $this->filePath;
        
        parent::add($data, null, false);
        
        //对目录进行 zip打包
        return true;
    }
    
    private function _createHandle(array $map)
    {
        $mapField = array();
        $buckDB = array('models','model_attribute','model_form','model_form_attr');
        
        $ii = 0;
        foreach($buckDB as $name)
        {
            $result = array();
        
            $model = $this->get('db.models')->getData($name,'name');
        
            if(empty($model))
                continue;
        
            $info = $this->get($model['service'])->findBy($map, null, 10000);
        
            if(!isset($info['data']))
                continue;
        
            $iii = 0;
            foreach($info['data'] as $item)
            {
                if(empty($item['identifier']))
                    continue;
        
                //建立映射关系数据
                $mapField[$name][$item['id']] = $item['identifier'];
        
                //卸载ID
                unset($item['id']);
                unset($item['create_time']);
                unset($item['update_time']);
                unset($item['is_delete']);
                unset($item['attributes']);
        
                switch($name)
                {
                    //模型字段表
                    case 'model_attribute':
                        if(!isset($mapField['models'][$item['model_id']]))
                        {
                            $model = $this->get('db.models')->getData($item['model_id']);
        
                            if(empty($model))
                                continue;
        
                            $mapField['models'][$item['model_id']] = $model['identifier'];
                        }
        
                        $item['model_id'] = $mapField['models'][$item['model_id']];
                        break;
                        //表单主表
                    case 'model_form':
                        if(!isset($mapField['models'][$item['model_id']]))
                        {
                            $model = $this->get('db.models')->getData($item['model_id']);
        
                            $mapField['models'][$item['model_id']] = isset($model['identifier'])?$model['identifier']:'';
                        }
                        
                        if($item['bindform']>0&&!isset($mapField['model_form'][$item['bindform']]))
                        {
                            $model = $this->get('db.model_form')->getData($item['bindform']);
                            $mapField['model_form'][$item['bindform']] = isset($model['identifier'])?$model['identifier']:0;
                        }

                        $item['model_id'] = isset($mapField['models'][$item['model_id']])?$mapField['models'][$item['model_id']]:0;
                        $item['bindform'] = isset($mapField['model_form'][$item['bindform']])?$mapField['model_form'][$item['bindform']]:0;

                        $parentForm = $item['parent_form'];

                        $parentForm = explode(',', $parentForm);

                        $item['parent_form'] = array();

                        foreach($parentForm as $it)
                        {
                            $it = (int)$it;
                            if($it<=0)
                                continue;
                            
                            if(!isset($mapField['model_form'][$it]))
                            {
                                $model = $this->get('db.model_form')->getData($it);

                                $mapField['model_form'][$it] = isset($model['identifier'])?$model['identifier']:$it;
                            }

                            $item['parent_form'][] = $mapField['model_form'][$it];                            
                        }

                        break;
                        //表单字段表
                    case 'model_form_attr':
                        $model = $this->get('db.model_form')->getData($item['model_form_id']);
                        if(!isset($mapField['model_form'][$item['model_form_id']]))
                        {
                            $model = $this->get('db.model_form')->getData($item['model_form_id']);
        
                            if(empty($model))
                                continue;
        
                            $mapField['model_form'][$item['model_form_id']] = $model['identifier'];
                        }
        
                        $item['model_form_id'] = $mapField['model_form'][$item['model_form_id']];
                        break;
                }
                $iii++;
                $result[$item['identifier']] = $item;
            }
        
            if($iii>0)
            {
                $ii++;
                $this->get('core.ymlParser')->ymlWrite($result, $this->filePath.$name.".yml");
            }
        }

        if($ii==0)
            throw new \LogicException("最近没有更新数据");

        return true;
    }
    
    public function resetVersion($id)
    {
        ini_set('max_execution_time', '0');
        $map = array();
        $map['id'] = $id;
        $map['useCache'] = false;
        $info = parent::findOneBy($map);
        
        if(!is_object($info))
            return false;
        
        $this->filePath .= $info->getName().DIRECTORY_SEPARATOR;
        
        //path
        $finder = new Finder();

        $_iterator = $finder
        ->files()
        ->name("/(.yml$)/i")
        ->sortByName()
        ->in($this->filePath);

        $result = array();

        foreach ($_iterator as $file)
        {
            $result[str_replace(array('.yml'),"",$file->getBasename())] = $this->get('core.ymlParser')->ymlRead($file->getRealpath());
        }

        //优先更新models模型
        if(isset($result['models']))
        {
            foreach($result['models'] as $key=>$item)
            {
                $info = $this->get('db.models')->systemAdd($item,array('identifier'=>$key));
                $result['models'][$key]['id'] = is_object($info)?$info->getId():0;
            }
            
            $this->get('db.models')->handleYmlData(array());
            
            echo "db.models Success <br />";
        }

        if(isset($result['model_attribute']))
        {
            foreach($result['model_attribute'] as $key=>$item)
            {
                //如果主模型存在，则用主模型，不存在则从数据表中重新读取
                if(isset($result['models'][$item['model_id']]['id']))
                {
                    $item['model_id'] = $result['models'][$item['model_id']]['id'];
                }else{
                    $info = $this->get('db.models')->getData($item['model_id'], 'identifier');
                    $item['model_id'] = isset($info['id'])?$info['id']:0;
                }
                
                $this->get('db.model_attribute')->systemAdd($item,array('identifier'=>$key));
            }
            
            echo "db.model_attribute Success <br />";
        }

        if(isset($result['model_form']))
        {
            //未处理的新数据
            $noHandle = array();
            $modelForm = $result['model_form'];
            
            $ias = 0;
            
            //回滚流程
            modelForm:
            foreach($modelForm as $key=>$item)
            {
                //如果主模型存在，则用主模型，不存在则从数据表中重新读取
                if(isset($result['models'][$item['model_id']]['id']))
                {
                    $item['model_id'] = $result['models'][$item['model_id']]['id'];
                }else{
                    $info = $this->get('db.models')->getData($item['model_id'], 'identifier');
                    $item['model_id'] = isset($info['id'])?$info['id']:0;
                }

                if($item['bindform']&&!is_numeric($item['bindform']))
                {
                    if(isset($result['model_form'][$item['bindform']]['id'])&&$result['model_form'][$item['bindform']]['id']>0)
                    {
                        $item['bindform'] = $result['model_form'][$item['bindform']]['id'];
                    }else{
                        $info = $this->get('db.model_form')->getData($item['bindform'],'identifier');
    
                        if(isset($info['id']))
                            $item['bindform'] = $info['id'];
                        else
                            $noHandle[$key] = $result['model_form'][$key];
                    }
                }
                
                $parentForm = $item['parent_form'];
                $item['parent_form'] = array();
                foreach($parentForm as $ikk)
                {
                    if(isset($result['model_form'][$ikk]['id'])&&$result['model_form'][$ikk]['id']>0)
                    {
                        $item['bindform'] = $result['model_form'][$ikk]['id'];
                    }else{
                        $info = $this->get('db.model_form')->getData($ikk,'identifier');
                        
                        if(isset($info['id']))
                            $item['parent_form'][] = $info['id'];
                        else
                            $noHandle[$key] = $result['model_form'][$key];
                    }
                }               
                $info = $this->get('db.model_form')->systemAdd($item,array('identifier'=>$key));
                $result['model_form'][$key]['id'] = is_object($info)?$info->getId():0;
            }
            
            //生成文件数据
            $this->get('db.model_form')->handleYmlData(array());

            //回滚
            if($noHandle)
            {
                $ias++;
            
                $modelForm = $noHandle;
            
                if($ias<=1)
                    goto modelForm;
            }
            
            echo "db.model_form Success <br />";
            
            unset($noHandle);
            unset($modelForm);
        }

        if(isset($result['model_form_attr']))
        {
            foreach($result['model_form_attr'] as $key=>$item)
            {
                //如果主模型存在，则用主模型，不存在则从数据表中重新读取
                if(isset($result['model_form'][$item['model_form_id']]['id']))
                {
                    $item['model_form_id'] = $result['model_form'][$item['model_form_id']]['id'];
                }else{
                    $info = $this->get('db.model_form')->getData($item['model_form_id'], 'identifier');
                    $item['model_form_id'] = isset($info['id'])?$info['id']:0;
                }
                
                $this->get('db.model_attribute')->systemAdd($item,array('identifier'=>$key));
            }
            
            $this->get('db.model_attribute')->systemAdd($item,array('identifier'=>$key));
            
            echo "db.model_form_attr Success <br />";
        }

        $this->get('core.controller.command')->createAllEntity();
        
        die();
    }
}