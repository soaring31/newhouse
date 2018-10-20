<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package Tripod
* create date 2015年9月8日
*/
namespace CoreBundle\Functions;

use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Symfony\Component\Filesystem\Filesystem;
use Doctrine\ORM\Mapping\Driver\DatabaseDriver;
use Doctrine\ORM\Tools\EntityRepositoryGenerator;
use Doctrine\ORM\Tools\Export\ClassMetadataExporter;
use Doctrine\ORM\Tools\DisconnectedClassMetadataFactory;
use Doctrine\Bundle\DoctrineBundle\Command\DoctrineCommand;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * 数据库导入doctrine(orm)结构服务
 *
 */
class ImportMappingDoctrineCommand extends DoctrineCommand
{
    
    public function __construct(ContainerInterface $container)
    {
        parent::setContainer($container);
    }
    
    /**
     * 创建orm
     * @param string $name
     * @param string $type
     * @param string $overwrite
     * @throws \InvalidArgumentException
     * @return boolean
     */
    public function create($name, $type='yml', $overwrite=true)
    {
        if(empty($name))
            throw new \InvalidArgumentException("未指定表名");
        
        //创建数据库连接
        $em = $this->getEntityManager('default');
        $databaseDriver = new DatabaseDriver($em->getConnection()->getSchemaManager());
        $em->getConfiguration()->setMetadataDriverImpl($databaseDriver);

        $cmf = new DisconnectedClassMetadataFactory();
        $cmf->setEntityManager($em);
        
        //加表前缀
        $tableName = $this->get('core.common')->prefixName($name);
        
        //转义成psr-0标准
        $tableName = $this->get('core.common')->ucWords($tableName);

        //获取metadata数据
        $metadata = $cmf->getMetadataFor($tableName);
        
        if($metadata)
            self::createOrmByMetadata($metadata, $type, $overwrite);
        return true;
    }

    /**
     * 获取doctrine code
     * @param ClassMetadataInfo $metadata
     * @param string $type
     * @param string $overwrite
     */
    public function getDoctrineCode(ClassMetadataInfo $metadata, $type, $overwrite=true)
    {
        $cme = new ClassMetadataExporter();
        $exporter = $cme->getExporter($type);
         
        //是否覆盖
        $exporter->setOverwriteExistingFiles($overwrite);
         
        if ('annotation' === $type) {
            $entityGenerator = $this->getEntityGenerator();
    
            $exporter->setEntityGenerator($entityGenerator);
        }
         
        return $exporter->exportClassMetadata($metadata);
    }

    /**
     * 根据Metadata生成orm
     * @param ClassMetadataInfo $metadata
     * @param string $type [yml,yaml,xml,php,annotation]
     * @param string $overwrite
     * @throws \InvalidArgumentException
     */
    public function createOrmByMetadata(ClassMetadataInfo $metadata, $type, $overwrite=true)
    {
        $className = $metadata->name;
        $className = explode('\\', $className);

        $name = isset($className[0])&&$className[0]?$className[0]:$this->get('core.common')->getDefaultBundle();

        $bundle = $this->get('kernel')->getBundle($name);

        $destPath = $bundle->getPath();

        if ('yaml' === $type)
            $type = 'yml';

        switch($type)
        {
            case 'yaml':
            case 'php':
            case 'yml':
                $destPath .= '/Resources/config/doctrine';
                break;
            case 'annotation':
                $destPath .= '/Entity';
                break;
            default:
                throw new \InvalidArgumentException("不支持[".$type."]类型");
                break;
        }

        $className = end($className);

        $name = $metadata->table['name'];
        $metadata->table['name'] = $this->get('core.common')->unprefixName($name);
        $metadata->name = $bundle->getNamespace().'\\Entity\\'.$className;
        $metadata->namespace = dirname($metadata->name);

        if ('annotation' === $type)
            $path = $destPath.DIRECTORY_SEPARATOR.$className.'.php';
        else
            $path = $destPath.DIRECTORY_SEPARATOR.$className.'.orm.'.$type;
        
        if (!is_dir($dir = dirname($path)))
            mkdir($dir, 0777, true);

        if ('annotation' === $type){
            $this->createEntity($metadata, $bundle);
        }else{
            $code = self::getDoctrineCode($metadata, $type, $overwrite);
            
            file_put_contents($path, $code);
        }
    }

    /**
     * 生成Entity
     * @param object $bundle
     * @param ClassMetadataInfo $metadata
     */
    public function createEntity(ClassMetadataInfo $metadata, $bundle, $backupExisting = false)
    {
        $generator = $this->getEntityGenerator();
        $generator->setBackupExisting($backupExisting);
        $generator->setGenerateAnnotations(true);
        $generator->setRegenerateEntityIfExists(true);
    
        $repoGenerator = new EntityRepositoryGenerator();

        $path = $this->getBasePathForClass($metadata->name, $bundle->getNamespace()."/Entity", $bundle->getPath()."/Entity");
        
        if(isset($metadata->associationMappings)&&is_array($metadata->associationMappings))
        {
            foreach($metadata->associationMappings as $k=>$item)
            {
                $metadata->associationMappings[$k]['targetEntity'] = $this->fullyQualifiedClassName($item['targetEntity'], $metadata->namespace);
                $metadata->associationMappings[$k]['sourceEntity'] = $this->fullyQualifiedClassName($item['sourceEntity'], $metadata->namespace);
            }
        }

        //如果是users表，则需要从UsersEntity类继承
        //if($metadata->table['name']=='users')
        //    $generator->setClassToExtend("CoreBundle\\Entity\\UsersEntity implements \\Symfony\\Component\\Security\\Core\\User\\UserInterface");

//         if($metadata->table['name']=='auth_group')
//             $generator->setClassToExtend("CoreBundle\\Entity\\GroupEntity");

        //生成Entity
        $generator->generate(array($metadata), $path);

        if ($metadata->customRepositoryClassName && false !== strpos($metadata->customRepositoryClassName, $metadata->namespace)) {
            $repoGenerator->writeEntityRepositoryClass($metadata->customRepositoryClassName, $path);
        }
    }
    
    /**
     * 删除orm文件
     * @param ClassMetadataInfo $metadata
     * @param string $type
     * @throws \InvalidArgumentException
     */
    public function deleteOrmFile(ClassMetadataInfo $metadata, $bundle, $serviceName=null, $type='yml')
    {
        $entity = new $metadata->name();
         
        //反射类ReflectionObject
        $reflected = new \ReflectionObject($entity);
        
        //文件名称，不带后缀名
        $name = $reflected->getShortName();
        
        //类文件的绝对位置
        $classFilePath = $reflected->getFilename();
        
        $ormFilePath = dirname(dirname($classFilePath));
        
        if ('yaml' === $type)
            $type = 'yml';
        
        switch($type)
        {
            case 'yaml':
            case 'php':
            case 'yml':
                //获取所属Bundle的命名空间路径
                $bundlePath = $this->get('core.common')->getBundlePath($bundle);
                
                $dbFilePath = $bundlePath.'Resources'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'db.yml';

                $ormFilePath .= DIRECTORY_SEPARATOR.'Resources'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'doctrine'.DIRECTORY_SEPARATOR.$name.'.orm.'.$type;
                break;
            default:
                throw new \InvalidArgumentException("不支持[".$type."]类型");
                break;
        }

        $dbInfo = $this->get('core.ymlParser')->ymlRead($dbFilePath);

        if($serviceName)
        {
            //删除服务
            unset($dbInfo['services'][$serviceName]);

            $this->get('core.ymlParser')->ymlWrite($dbInfo, $dbFilePath);
        }

        
        //删除元数据文件
        $filesystem = new Filesystem();
        $filesystem->remove($ormFilePath);
        $filesystem->remove($classFilePath);
        return true;
    }
    
    /**
     * 删除元数据(entity)
     * @param ClassMetadataInfo $metadata
     */
    public function deleeEntity(ClassMetadataInfo $metadata)
    {
        
    }

    /**
     * @param   string $className
     * @return  string
     */
    protected function fullyQualifiedClassName($className, $namespace)
    {
        if ($className !== null && strpos($className, '\\') === false && strlen($namespace) > 0) {
            return $namespace . '\\' . $className;
        }
    
        return $className;
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
            return $this->getContainer()->get('request_stack')->getCurrentRequest();

        if (!$this->getContainer()->has($id))
            throw new \InvalidArgumentException("[".$id."]服务未注册。");
    
        return $this->getContainer()->get($id);
    }

    private function getBasePathForClass($name, $namespace, $path)
    {
        $c = 0;
        $namespace = str_replace('\\', '/', $namespace);
        $search = str_replace('\\', '/', $path);
        $destination = str_replace('/'.$namespace, '', $search, $c);

        if ($c != 1) {
            throw new \RuntimeException(sprintf('Can\'t find base path for "%s" (path: "%s", destination: "%s").', $name, $path, $destination));
        }

        return $destination;
    }
}