<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年1月22日
*/
namespace CoreBundle\Functions;

use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Component\Finder\Finder;
use CoreBundle\Util\BundleGenerator;
use CoreBundle\Services\ServiceBase;
use Symfony\Component\Filesystem\Filesystem;
use Sensio\Bundle\GeneratorBundle\Command\Validators;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Sensio\Bundle\GeneratorBundle\Manipulator\KernelManipulator;
use Sensio\Bundle\GeneratorBundle\Manipulator\RoutingManipulator;

/**
 * 控制器执行终端命令
 */
class ControllerCommand extends ServiceBase
{
    protected $generator;
    protected $container;
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->generator = new BundleGenerator(new Filesystem());
        $this->generator->setSkeletonDirs($this->getSkeletonDirs());
    }

    /**
     * 创建Bundle
     */
    public function CreateBundle(array $options, $dependency=true)
    {
        $options['bundle'] = ucwords(trim($options['bundle']));
        $options['namespace'] = isset($options['namespace'])?ucwords(trim($options['namespace'])):$options['bundle'];

        if(!isset($options['bundle'])||empty($options['bundle']))
            throw new \LogicException('Bundle名称未设置');
        
        if(!preg_match('/^[a-zA-Z]+$/',$options['bundle']))
            throw new \LogicException('Bundle名称只允许字母');

        if(!isset($options['namespace'])||empty($options['namespace']))
            $options['namespace'] = $options['bundle'];

        //增加Bundle后缀
        if (!preg_match('/Bundle$/', $options['bundle']))
            $options['bundle'] .= "Bundle";

        //增加Bundle后缀
        if (!preg_match('/Bundle$/', $options['namespace']))
            $options['namespace'] .= "Bundle";

        $path = $this->get('kernel')->getRootdir();
        $path = dirname($path);

        $namespace = $options['namespace'];
        $bundle = $options['bundle'];
        $dir = isset($options['dir'])&&$options['dir']=='src'?$path.DIRECTORY_SEPARATOR.$options['dir']:$path.DIRECTORY_SEPARATOR."site";
        $format = isset($options['format'])&&$options['format']?$options['format']:'yml';
        $structure = isset($options['structure'])?(bool)$options['structure']:false;

        $namespace = Validators::validateBundleNamespace($namespace, false);
        $bundle = Validators::validateBundleName($bundle);
        $format = Validators::validateFormat($format);

        //创建Bundle目录
        $this->generator->generate($namespace, $bundle, $dir, $format, $structure, $dependency);

        //更新AppKernel文件
        $this->updateKernel($namespace, $bundle);

        //更新路由文件
        if($dependency)
            $this->updateRouting($bundle, $format);
    }

    /**
     * 创建Entity源
     * @param string $bundlename
     */
    public function createAllEntity($bundlename=null)
    {
        $map = array();
        $map['status'] = 1;
        $models = $this->get('db.models')->findBy($map);
        $this->get('core.memcache')->flushAll();
        if(isset($models['data']))
        {
            foreach($models['data'] as $model)
            {
                if(!is_object($model))
                    continue;

                try {
                    self::createEntity($model, true, $bundlename);
                } catch (\Exception $e) {

                }
            }
        }

        return true;
    }
    
    /**
     * 创建一个模型的 entity类，metadata数据，validate文件(暂时未生成)，重建数据表结构
     * @param string $bundlename
     */
    public function createEntity($info, $flag=true, $bundlename=null)
    {
        $metadata = self::getMetadata($info, $bundlename);


        if($flag)
        {
            //$bundle = $this->get('core.common')->getUserBundle();
            //$bundle = strtolower(str_replace('Bundle', '', $bundle));

            //执行数据库动作
            $schemaTool = new SchemaTool($this->get('doctrine')->getManager($this->get('core.common')->getUserDb()));
            $schemaTool->updateSchema(array($metadata), true);
        }

        //生成orm.yml文件
        $this->get('core.importdoctrine.command')->createOrmByMetadata($metadata, 'yml');

        //生成entity文件
        $this->get('core.importdoctrine.command')->createOrmByMetadata($metadata, 'annotation');
    }
    
    /**
     * 创建一个模型的 entity类，metadata数据，validate文件(暂时排除)
     * @param string $bundlename
     */
    public function refreshSchema($info, $bundlename=null)
    {
        $metadata = self::getMetadata($info, $bundlename);
        try {
            $schemaTool = new SchemaTool($this->get('doctrine')->getManager($this->get('core.common')->getUserDb()));
            $schemaTool->updateSchema(array($metadata), true);
        } catch (\Exception $e) {
            throw new \InvalidArgumentException($info->getName()."数据表结构更新失败!");
	    }
    }    
    /**
     * 创建一个模型的 validate文件 [注：目前并没有生成]//??//!!
     * @param string $bundlename
     */
    public function createValidatorInfo($info, $bundlename=null)
    {
        //清缓存
        $this->get('db.models')->retCache();
    
        if(!is_object($info))
            throw new \InvalidArgumentException("模型数据不存在或已被删除");
    
        $comment = $info->getTitle();
    
        $engineType = $info->getEngineType();
        $isBinary = $info->getIsBinary();
        $structure = $info->getStructure();
    
        //表名
        $name = $info->getName();
    
        //去掉表前缀
        $name = $this->get('core.common')->unprefixName($name);
    
        //$bundlename = $bundlename?$bundlename:($info->getBundle()?$info->getBundle():$this->get('core.common')->getDefaultBundle());
    
        $bundlename = $bundlename?$bundlename:$this->get('core.common')->getDefaultBundle();
        $bundlePath = $this->get('core.common')->getBundlePath($bundlename);
    
        //获取命名空间
        $bundleNamespace = $this->get('core.common')->getBundleNamespace($bundlename);

    
        //设置表名namespace psr-0命名规则
        $rootEntityName = $bundleNamespace."\\Entity\\".$this->get('core.common')->ucWords($name);


        $infoAttribute = $this->get('db.model_attribute')->findBy(array('model_id'=>$info->getId(),'useCache'=>0), array('sort'=>'asc'));
    

        $name = $this->get('core.common')->ucWords($name);
    
        //获取服务配置文件
        $filePath = $bundlePath."Resources".DIRECTORY_SEPARATOR."validation".DIRECTORY_SEPARATOR.$name.".yml";
    
        $filesystem = new Filesystem();
    
        //如果配置文件不存在，则创建
        if(!$filesystem->exists($filePath))
            $filesystem->touch($filePath);
    
        $validatorInfo = $this->get('core.ymlParser')->ymlRead($filePath);
    
        $validatorInfo[$rootEntityName]['properties'] = isset($validatorInfo[$rootEntityName]['properties'])?$validatorInfo[$rootEntityName]['properties']:array();
    
        //加字段
        if(isset($infoAttribute['data']))
        {
            foreach($infoAttribute['data'] as $item)
            {
                $field    = $item->getField();
                $fieldname= $item->getName();
                $length   = $item->getLength();
                $title    = $item->getTitle();
                $remark   = $item->getRemark();
                $default  = $item->getValue();
                $nullable = false;
    
                //加规则到校验配置文件
                if(!isset($validatorInfo[$rootEntityName]['properties'][$fieldname][0]))
                {
                    $validatorInfo[$rootEntityName]['properties'][$fieldname][0]['NotBlank']['message'] = ($title?$title:$fieldname)."不能为空";
                }
    
                if(!isset($validatorInfo[$rootEntityName]['properties'][$fieldname][1])&(int)$length>0)
                {
                    $validatorInfo[$rootEntityName]['properties'][$fieldname][1]['Length']['min'] = 1;
                    $validatorInfo[$rootEntityName]['properties'][$fieldname][1]['Length']['max'] = (int)$length;
                    $validatorInfo[$rootEntityName]['properties'][$fieldname][1]['Length']['minMessage'] = ($title?$title:$fieldname)."不能少于{{ limit }}字符";
                    $validatorInfo[$rootEntityName]['properties'][$fieldname][1]['Length']['maxMessage'] = ($title?$title:$fieldname)."不能大于{{ limit }}字符";
                    $validatorInfo[$rootEntityName]['properties'][$fieldname][1]['Length']['max'] = (int)$length;
                }
            }
        }

    }    
    /**
     * 按model配置，创建一个表的 Metadata 数据，注意并不创那家文件
     * @param string $bundlename
     */
    public function getMetadata($info, $bundlename=null)
    {
        //清缓存
        $this->get('db.models')->retCache();
    
        if(!is_object($info))
            throw new \InvalidArgumentException("模型数据不存在或已被删除");
    
        $comment = $info->getTitle();
    
        $engineType = $info->getEngineType();
        $isBinary = $info->getIsBinary();
        $structure = $info->getStructure();
    
        //表名,去掉表前缀
        $name = $info->getName();
        $name = $this->get('core.common')->unprefixName($name);
    
        //$bundlename = $bundlename?$bundlename:($info->getBundle()?$info->getBundle():$this->get('core.common')->getDefaultBundle());    
        $bundlename = $bundlename?$bundlename:$this->get('core.common')->getDefaultBundle();
        $bundlePath = $this->get('core.common')->getBundlePath($bundlename);
    
        //获取命名空间
        $bundleNamespace = $this->get('core.common')->getBundleNamespace($bundlename);
    
        //从源表读取数据构成基础表
        $metadata = $this->get('db.models')->getClassMetadata('ManageBundle:Clonebase',true);
        $metadata = clone($metadata);
    
        //设置表名namespace psr-0命名规则
        $rootEntityName = $bundleNamespace."\\Entity\\".$this->get('core.common')->ucWords($name);
        $metadata->setPrimaryTable(array('name' => $this->get('core.common')->prefixName($name)));
        $metadata->setParentClasses(array($rootEntityName));
        $metadata->name = $rootEntityName;
        $metadata->isRootEntity();
    
        //设置表属性
        $primaryTable = array('options' => array('comment'=>$comment, 'engine'=>$engineType?$engineType:'MyISAM'));
    
        $infoAttribute = $this->get('db.model_attribute')->findBy(array('model_id'=>$info->getId(),'useCache'=>0), array('sort'=>'asc'));
    
        //创建索引
        if(isset($infoAttribute['data']))
        {
            foreach($infoAttribute['data'] as $item)
            {
                if(!method_exists($item, 'getIsindex')||!$item->getIsindex())
                    continue;
    
                $primaryTable['indexes'][$item->getName()]['columns'][] = $item->getName();
            }
        }
    
        $metadata->setPrimaryTable($primaryTable);
    
    
        $classMetadata = new ClassMetadataBuilder($metadata);

        //加字段
        if(isset($infoAttribute['data']))
        {
            foreach($infoAttribute['data'] as $item)
            {
                $field    = $item->getField();
                $fieldname= $item->getName();
                $length   = $item->getLength();
                $title    = $item->getTitle();
                $remark   = $item->getRemark();
                $default  = $item->getValue();
                $nullable = false;
    
                //属性
                $mapping = array();
                $mapping['options'] = array('comment'=>$remark?$remark:$title, 'unsigned'=>false);
                $mapping['nullable'] = $nullable;
    
                if($default)
                {
                    $mapping['default'] = $default;
                    $mapping['options']['default'] = $default;
                }
    
                //字段添加至表结构
                $classMetadata->addField($fieldname, $field, $mapping);
    
                //设置表长度
                $metadata->fieldMappings[$fieldname]['length'] = (int)$length;
            }
        }
    
        //判断是否为二叉树结构
        if((bool)$isBinary)
        {
            //增加二叉树字段(广度)
            if(!isset($metadata->fieldNames['pid']))
                $classMetadata->addField('pid'
                    ,'integer'
                    ,array('options'=>array('comment'=>'父ID', 'unsigned'=>false), 'nullable'=>false));
    
            if(!isset($metadata->fieldNames['binary_tree']))
                $classMetadata->addField('binary_tree'
                    ,'boolean'
                    ,array('options'=>array('comment'=>'二叉树标识,1为启用', 'unsigned'=>false), 'nullable'=>false));
    
            if(!isset($metadata->fieldNames['left_node']))
                $classMetadata->addField('left_node'
                    ,'integer'
                    ,array('options'=>array('comment'=>'左节点', 'unsigned'=>false), 'nullable'=>false));
    
            if(!isset($metadata->fieldNames['right_node']))
                $classMetadata->addField('right_node'
                    ,'integer'
                    ,array('options'=>array('comment'=>'右节点', 'unsigned'=>false), 'nullable'=>false));
        }
    
        //判断表结构
        if((bool)$structure)
        {
            //增加纵表字段
            if(!isset($metadata->fieldNames['name']))
                $classMetadata->addField('name'
                    ,'string'
                    ,array('options'=>array('comment'=>'字段标识', 'unsigned'=>false), 'nullable'=>false, 'length'=>100));
    
            if(!isset($metadata->fieldNames['value']))
                $classMetadata->addField('value'
                    ,'string'
                    ,array('options'=>array('comment'=>'字段值', 'unsigned'=>false), 'nullable'=>false, 'length'=>100));
    
            if(!isset($metadata->fieldNames['title']))
                $classMetadata->addField('title'
                    ,'string'
                    ,array('options'=>array('comment'=>'字段标题', 'unsigned'=>false), 'nullable'=>false, 'length'=>100));
            if(!isset($metadata->fieldNames['mid']))
                $classMetadata->addField('mid'
                    ,'integer'
                    ,array('options'=>array('comment'=>'主表ID', 'unsigned'=>false), 'nullable'=>false));
        }else{
            //增加是否审核字段
            if(!isset($metadata->fieldNames['checked']))
                $classMetadata->addField('checked'
                    ,'boolean'
                    ,array('options'=>array('comment'=>'是否审核', 'unsigned'=>false), 'nullable'=>false));
    
            //增加属性表字段
            if(!isset($metadata->fieldNames['attributes']))
                $classMetadata->addField('attributes'
                    ,'string'
                    ,array('options'=>array('comment'=>'属性表字段', 'unsigned'=>false), 'nullable'=>false, 'length'=>10));
        }
    
        //增加排序字段
        if(!isset($metadata->fieldNames['sort']))
            $classMetadata->addField('sort'
                ,'integer'
                ,array('options'=>array('comment'=>'排序', 'unsigned'=>false), 'nullable'=>false));
    
        //增加系统字段
        if(!isset($metadata->fieldNames['issystem']))
            $classMetadata->addField('issystem'
                ,'boolean'
                ,array('options'=>array('comment'=>'是否系统字段', 'unsigned'=>false), 'nullable'=>false));
    
        //增加唯一标识字段
        if(!isset($metadata->fieldNames['identifier']))
            $classMetadata->addField('identifier'
                ,'string'
                ,array('options'=>array('comment'=>'唯一标识', 'unsigned'=>false), 'nullable'=>false, 'length'=>100));
    
        //增加创建时间字段
        if(!isset($metadata->fieldNames['create_time']))
            $classMetadata->addField('create_time'
                ,'integer'
                ,array('options'=>array('comment'=>'创建时间', 'unsigned'=>false), 'nullable'=>false));
    
        //增加更新字段
        if(!isset($metadata->fieldNames['update_time']))
            $classMetadata->addField('update_time'
                ,'integer'
                ,array('options'=>array('comment'=>'更新时间', 'unsigned'=>false), 'nullable'=>false));
    
        //增加假删除标识字段
        if(!isset($metadata->fieldNames['is_delete']))
            $classMetadata->addField('is_delete'
                ,'boolean'
                ,array('options'=>array('comment'=>'0正常，1假删除', 'unsigned'=>false), 'nullable'=>false));
    
        //设置表长度
        $metadata->fieldMappings['sort']['length'] = 5;
        $metadata->fieldMappings['is_delete']['length'] = 1;
        $metadata->fieldMappings['create_time']['length'] = 10;
        $metadata->fieldMappings['update_time']['length'] = 10;
    
        return $metadata;
    }
    /**
     * 创建控制器
     * @param array $options
     * @param UserInterface $user
     * @throws \LogicException
     */
    public function createController(array $options)
    {
        $user = $this->get('core.common')->getUser();
        if(empty($user)||!is_object($user))
            throw new \LogicException('请登入后再操作');

        if($this->get('request')->getMethod() == "POST")
        {
            $name = isset($options['name'])?trim($options['name']):'';
            $title = isset($options['title'])?trim($options['title']):'';
            $action = isset($options['action'])?trim($options['action']):'';
            $bundle = isset($options['bundle'])?trim($options['bundle']):'';

            if(!preg_match('/^[a-zA-Z]+$/',$name))
                throw new \LogicException('控制器名称只允许字母');

            if(empty($name))
                throw new \LogicException('控制器名称不能为空');

            if(empty($bundle))
                throw new \LogicException('控制器所属的Bundle必须选择');

            $userName = $user->getUsername();

            //把空格、换行符、英文分号、中文分号、中文逗号等替换成英文逗号的正则表达式(\n)|(\\s)|(\t)|
            $action = $action?preg_replace("/(\n)|(\\s)|(\t)|(，)|(;)|(；)/" ,',' ,$action):'';
            $action = explode(',', $action);

            $bundlePath = $this->get('core.common')->getBundlePath($bundle);
            $bundlePath .= "Controller".DIRECTORY_SEPARATOR;

            //单词首字母大写
            $name = ucwords($name);

            //增加Controller后缀
            if (!preg_match('/Controller$/', $name))
                $name = $name."Controller";

            $bundleNamespace = $this->get('core.common')->getBundleNamespace($bundle);

            $filesystem = new Filesystem();

            //判断文件是否存在，不存在则直接返回空值
            $Controller = $name.".php";
            if($filesystem->exists($bundlePath.$Controller)){
                return true;
                throw new \LogicException(sprintf("控制器【%s】已存在",$name));
            }

            $Y = date('Y');
            $m = date('m');
            $d = date('d');

            $actionFun = '';
            foreach ( $action as $value )
            {
                if(empty($value)) continue;
                $actionFun .= <<<str
    /**
    * 实现的{$value}方法
    */
    public function {$value}Action()
    {
        return \$this->render(\$this->getBundleName(), \$this->parameters);
    }

str;
            }
            $content = <<<str
<?php
/**
* @copyright Copyright (c) 2008 – {$Y} www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date {$Y}年{$m}月{$d}日
*/
namespace {$bundleNamespace}\\Controller;

/**
* {$title}
* @author {$userName}
*/
class {$name} extends Controller
{

{$actionFun}
}
str;
        }

        //写入控制器文件
        $filesystem->dumpFile($bundlePath.$Controller, $content);
    }

    /**
     * 预览控制器
     * @param array $data
     * @return string
     */
    public function previewController(array $data)
    {
        $contents = "";
        //文件名
        $name = isset($data['name'])?$data['name']:'';

        //搜索的bundle
        $searchName = isset($data['searchName'])?$data['searchName']:'';

        if($searchName)
        {
            $searchNamePath = $this->get('core.common')->getBundlePath($searchName)."Controller";
            $finder = new Finder();
            $_iterator = $finder
            ->files()
            ->name($name)
            ->in($searchNamePath);

            foreach ($_iterator as $file) {
                $contents = $file-> getContents();
            }
        }

        $tpl = <<<str
{$contents}
str;
        return $tpl;
    }

    /**
     * 创建方法
     * @param array $options
     * @param UserInterface $user
     */
    public function CreateAction(array $options, UserInterface $user)
    {
        $userName = $user->getUsername();
        $name = isset($options['name'])?strtolower(trim($options['name'])):'';
        $title = isset($options['title'])?trim($options['title']):'';
        $useForm = isset($options['useform'])?(int)$options['useform']:0;
        $useModel = isset($options['usemodel'])?(int)$options['usemodel']:0;
        $listGrid = isset($options['listgrid'])?trim($options['listgrid']):'';
        $listDesc = isset($options['listdesc'])?trim($options['listdesc']):'';
        $contentText = isset($options['content'])?trim($options['content']):'';
        $createTpl = isset($options['createtpl'])?(int)trim($options['createtpl']):0;
        $belongBundle = isset($options['belongbundle'])?trim($options['belongbundle']):'';
        $belongController= isset($options['belongcontroller'])?trim($options['belongcontroller']):'';

        $title = $title?$title:"实现的{$name}方法";
        
        if(!preg_match('/^[a-zA-Z0-9]+$/',$name))
            throw new \LogicException('方法只允许字母+数字');

        //换行符替换成八个空格的正则表达式(\n)|
        $contentText = $contentText?preg_replace("/(\n)/" ,'        ' ,$contentText):'';
        if($createTpl==1||$createTpl==2||$createTpl==4)
        {
            if(empty($name))
                throw new \LogicException('方法名称不能为空');

//             if($createTpl==2&&empty($listGrid))
//                 throw new \LogicException('列表参数不能为空');

            $contentText = <<<str
{$contentText}
        return \$this->render(\$this->getBundleName(), \$this->parameters);
str;
        }

        if(empty($belongBundle))
            throw new \LogicException('所属的Bundle不存在');
        if(empty($belongController))
            throw new \LogicException('所属的控制器不存在');

        $bundlePath = $this->get('core.common')->getBundlePath($belongBundle);

        if(empty($bundlePath))
            throw new \LogicException(sprintf('%s 不存在或已被删除', $belongBundle));

        $bundlePath .= "Controller".DIRECTORY_SEPARATOR.$belongController;

        $content = "";
        if (file_exists($bundlePath))
            $content = file_get_contents($bundlePath);

        if(empty($content))
            throw new \LogicException(sprintf('%s控制器不存在或已被删除', $belongController));

        //增加Action后缀
        if (!preg_match('/Action$/', $name))
            $name .= "Action";

        //判断方法是否已经存在
        $existPointer = strripos($content, $name);

        if($existPointer)
        {
            return true;
            throw new \LogicException(sprintf('%s方法已存在', $name));
        }

        $pointer = strripos($content, '}');
        $content = substr($content, 0, $pointer);

        if($createTpl==2||$createTpl==3)
        {
            if($useModel<=0)
                throw new \LogicException('所属模型 没有选择!!!');

            $modelInfo = $this->get('db.models')->getData($useModel);

            if(empty($modelInfo))
                throw new \LogicException('所属模型 不存在或已被删除!!!');

            //表名
            $tableName = $this->get($modelInfo['service'])->getTableName();
        }

        //curd类型
        if($createTpl==3)
        {
            //判断方法是否已经存在
            $existPointer = strripos($content, 'saveAction');

            if(!$existPointer){
                $content .= <<<str

    /**
    * 实现的save方法
    * {$userName}
    */
    public function saveAction()
    {
        if(\$this->get('request')->getMethod() == "POST")
        {
            \$ids = \$this->get('request')->get('id', '');
            
            \$data = \$this->get('request')->request->all();
            
            unset(\$data['csrf_token']);

            if(\$ids)
            {
                \$ids = explode(',', \$ids);
                foreach(\$ids as \$id)
                {
                    \$info = \$this->get('{$modelInfo['service']}')->update(\$id, \$data);
                }
            }else
                \$info = \$this->get('{$modelInfo['service']}')->add(\$data);

            return \$this->success('操作成功', '', false, array('id' => \$info->getId()));
        }

        return \$this->error('操作失败');
    }
str;
            }

            //判断方法是否已经存在
            $existPointer = strripos($content, 'deleteAction');

            if(!$existPointer){
                $content .= <<<str

    /**
    * 实现的delete方法
    * {$userName}
    */
    public function deleteAction()
    {
        \$ids = \$this->get('request')->get('id', '');

        if(\$ids)
        {
            \$ids = explode(',', \$ids);
            foreach(\$ids as \$id)
            {
                \$this->get('{$modelInfo['service']}')->delete(\$id);
            }
        }
        return \$this->success('操作成功');
    }
str;
            }

                $content .= <<<str

}
str;

        }else{

            $content .= <<<str

    /**
    * {$title}
    * {$userName}
    */
    public function {$name}()
    {
        {$contentText}
    }
}
str;
        }

        $flink = fopen($bundlePath, 'w');
        if ($flink) {
            $write = fwrite($flink, $content);

            if ($write)
                fclose($flink);
            else
                throw new \LogicException(sprintf('写入失败,检查 "%s"是否有写入权限', $bundlePath));

            switch($createTpl)
            {
                //表单类型
                case 1:
                //列表类型
                case 2:
                case 4:
                    //生成模版文件
                    //记录到view表
                    $data = array();
                    $data['title'] = $name;
                    $data['bundle'] = $belongBundle;
                    $data['controller'] = $belongController;
                    $data['useform'] = $useForm;

                    $views = $this->createViews($data, isset($options['clonetpl'])?$options['clonetpl']:'');

                    if($createTpl==2&&is_object($views))
                        $this->createList($views,$tableName, $listGrid, $listDesc);
                    break;
            }
            return true;
        }

        throw new \LogicException(sprintf('写入失败, "%s"', $bundlePath));
    }

    //生成列表
    public function createList($views, $tableName, $listGrid, $listDesc)
    {
        $data = array();
        $data['identName'] = 'info';
        $data['tableName'] = $tableName;
        $data['listGrid'] = $listGrid;
        $data['listDesc'] = $listDesc;

        //生成标识数据
        $this->get('core.ident')->createIdent($views->getId(), $data, $views);

        return true;
    }

    /**
     * 创建服务
     * @param string $bundle        所在的Bundle
     * @param array $data           参数{name=>模型名称,title=>模型描述, service_name=>服务名称}
     * @param string $userName      操作者用户名
     */
    public function createService($bundle, $data, $userName=null)
    {
        $name     = isset($data['name'])&&$data['name']?$this->get('core.common')->ucWords($data['name']):'';
        $title    = isset($data['title'])&&$data['title']?$data['title']:'';
        $userName = $userName?('@author '.$userName):'';
        $serviceName = isset($data['service_name'])?$data['service_name']:'';

        //Bundle的绝对路径
        $bundlePath = $this->get('core.common')->getBundlePath($bundle);

        if(empty($name))
            throw new \LogicException('服务名称不能为空');

        //获取所属Bundle的命名空间路径
        $bundleNamespace = $this->get('core.common')->getBundleNamespace($bundle);

        $Y = date('Y');
        $m = date('m');
        $d = date('d');

        $content = <<<str
<?php
/**
* @copyright Copyright (c) 2008 – {$Y} www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date {$Y}年{$m}月{$d}日
*/
namespace {$bundleNamespace}\\Services\\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
* {$title}
* {$userName}
*/
class {$name} extends AbstractServiceManager
{
    protected \$table = '{$name}';
    public function __construct(ContainerInterface \$container)
    {
        parent::__construct(\$container);
        parent::setTables(\$this->table);
    }
}
str;
        $filesystem = new Filesystem();

        //判断文件是否存在，不存在则写入，存在则跳过
        $phpFilePath = $bundlePath."Services".DIRECTORY_SEPARATOR."DB".DIRECTORY_SEPARATOR.$name.".php";
        if(!$filesystem->exists($phpFilePath))
            $filesystem->dumpFile($phpFilePath, $content);

        //生成服务配置
        if($serviceName)
        {
            //获取服务配置文件
            $filePath = $bundlePath."Resources".DIRECTORY_SEPARATOR."config".DIRECTORY_SEPARATOR."db.yml";

            //如果配置文件不存在，则创建
            if(!$filesystem->exists($filePath))
                $filesystem->touch($filePath);

            $dbInfo = $this->get('core.ymlParser')->ymlRead($filePath);
            if(!isset($dbInfo['services'][$serviceName]))
            {
                $dbInfo['services'][$serviceName]['class'] = $bundleNamespace."\\Services\\DB\\".$name;
                $dbInfo['services'][$serviceName]['arguments'][0] = "@service_container";

                //写入配置文件
                $this->get('core.ymlParser')->ymlWrite($dbInfo, $filePath);
            }
        }
    }

    /**
     * 创建模版
     * @param array $data
     */
    public function createViews(array $data, $clonetpl='')
    {
        if(!isset($data['title'])||empty($data['title']))
            throw new \LogicException('模版名称不能为空');

        if(!isset($data['bundle'])||empty($data['bundle']))
            throw new \LogicException('所属Bundle不能为空');

        if(!isset($data['controller'])||empty($data['controller']))
            throw new \LogicException('所属控制器不能为空');

        //Bundle的绝对路径
        $bundlePath = $this->get('core.common')->getBundlePath($data['bundle']);

        $controller = $data['controller'];
        //去掉.php后缀名
        if (preg_match('/Controller.php$/', $controller))
        {
            $controller = substr($controller, 0, -14);
        }

        //去掉.html.twig后缀
        if (preg_match('/.html.twig$/', $data['title']))
        {
            $data['title'] = substr($data['title'], 0, -10);
        }

        //去掉Action后缀
        if (preg_match('/Action$/', $data['title']))
        {
            $data['title'] = substr($data['title'], 0, -6);
        }

        $template = $data['bundle'].':'.$controller.':'.$data['title'].'.html.twig';

        $afterPath = 'Resources'.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.$this->generator->parseTemplatePath($template);
        
        $viewPath = $bundlePath.$afterPath;

        $filesystem = new Filesystem();
        $clonetpl = $clonetpl?$clonetpl:'controller'.DIRECTORY_SEPARATOR.'Template.html.twig.twig';

        //判断文件是否存在，不存在则创建
        if(!$filesystem->exists($viewPath))
           $this->generator->createFile($clonetpl, $viewPath, array());

        $theme = $this->get('request')->get('theme', '');
        if(is_array($theme))
        {
            foreach ($theme as $value)
            {
                $viewPath = $this->get('core.common')->getBundlePath($value, '08themes').$afterPath;
                if($filesystem->exists($viewPath))
                    continue;

                $this->generator->createFile($clonetpl, $viewPath, array());
            }
        }

        $data['name'] = $template;

        $useform = isset($data['useform'])?$data['useform']:array();

        $map = array();
        $map['title'] = $data['title'];
        $map['bundle'] = $data['bundle'];
        $map['controller'] = $data['controller'];
        $map['useform'] = is_array($useform)?implode(',',$useform):$useform;

        $count = $this->get('db.views')->count($map);

        if($count>0)
            return true;

        //记录到view表
        return $this->get('db.views')->add($data, null, false);
    }

    /**
     * 生成表单文件
     * @param BundleInterface $bundle
     * @param array $data
     */
    public function createFormType(BundleInterface $bundle, array $data)    
    {    
        $className = $data['entityClass'].'Type';
        $dirPath = $bundle->getPath().DIRECTORY_SEPARATOR.'Form';
        $classPath = $dirPath.DIRECTORY_SEPARATOR.$className.'.php';

        $formInfo = $this->get('core.form_bind')->getForm($data['name'], array());

        $fields = array();
        foreach($formInfo->all() as $key=>$item)
        {
            $attributes = $item->getConfig()->getAttributes();
            $fields[$key]['name'] = $item->getConfig()->getType()->getName();

            $fields[$key]['option'] = $this->get('core.common')->createArray(end($attributes));
        }

        self::createFile(DIRECTORY_SEPARATOR.'form'.DIRECTORY_SEPARATOR.'FormType.php.twig', $classPath, array(
            'fields'           => $fields,
            'namespace'        => $bundle->getNamespace(),
            'entity_namespace' => '',
            'entity_class'     => $this->get('core.common')->ucWords($data['entity']),
            'bundle'           => $bundle->getName(),
            'form_class'       => $className,
            'Y'                => date('Y'),
            'YMD'              => date('Y年m月d日'),
            'form_type_name'   => $data['name'],//strtolower(str_replace('\\', '_', $bundle->getNamespace()).($parts ? '_' : '').implode('_', $parts).'_'.substr($this->className, 0, -4)),
        ));
    }

    /**
     * 生成文件
     * @param string $clonetpl   克隆模版
     * @param unknown $target    目标路径
     * @param array $parameters  参数
     */
    public function createFile($clonetpl, $target, array $parameters)
    {
        return $this->generator->createFile($clonetpl, $target, $parameters);
    }

    /**
     * 获取初始化生成文件的模版目录
     */
    protected function getSkeletonDirs()
    {
        $skeletonDirs = array();

        $clonePath  = dirname($this->get('kernel')->getRootdir()).DIRECTORY_SEPARATOR;
        $clonePath .= "Template".DIRECTORY_SEPARATOR."CloneTpl";

        if (is_dir($clonePath)) {
            $skeletonDirs[] = $clonePath;
        }else{
            $path = $this->get('kernel')->getRootdir();
            $path = dirname($path);
            $dir  = $path.DIRECTORY_SEPARATOR."vendor".DIRECTORY_SEPARATOR;
            $dir .= "sensio".DIRECTORY_SEPARATOR."generator-bundle".DIRECTORY_SEPARATOR;
            $dir .= "Sensio".DIRECTORY_SEPARATOR."Bundle".DIRECTORY_SEPARATOR."GeneratorBundle".DIRECTORY_SEPARATOR;
            $dir .= "Resources".DIRECTORY_SEPARATOR;
            $skeletonDirs[] = $dir.'skeleton';
            $skeletonDirs[] = $dir;
        }

        return $skeletonDirs;
    }

    /**
     * 更新AppKernel文件
     * @param string $namespace
     * @param string $bundle
     */
    protected function updateKernel($namespace, $bundle)
    {
        $auto = true;
        $kernel = $this->get('kernel');
        $manip = new KernelManipulator($kernel);
        try {
            $ret = $auto ? $manip->addBundle($namespace.'\\'.$bundle) : false;

            if (!$ret) {
                $reflected = new \ReflectionObject($kernel);
                throw new \LogicException(sprintf('编辑%s失败，请手动编辑', $reflected->getFilename()));
            }
        } catch (\RuntimeException $e) {
            throw new \LogicException(sprintf('Bundle %s 已存在 AppKernel::registerBundles().', $namespace.'\\'.$bundle));
        }
    }

    /**
     * 更新路由文件
     * @param string $bundle
     * @param string $format
     */
    protected function updateRouting($bundle, $format)
    {
        $auto = true;
        $routePath = $this->container->getParameter('kernel.root_dir').DIRECTORY_SEPARATOR;
        $routePath .= "config".DIRECTORY_SEPARATOR."routing.yml";

        $routing = new RoutingManipulator($routePath);
        try {
            $ret = $auto ? $routing->addResource($bundle, $format) : true;
            if (!$ret)
                throw new \LogicException('编辑 routing.yml 失败，请手动编辑');
        } catch (\RuntimeException $e) {
            throw new \LogicException(sprintf('Bundle %s is already imported.', $bundle));
        }
    }
}