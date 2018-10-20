<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年1月22日
*/
namespace CoreBundle\Util;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\DependencyInjection\Container;
use Sensio\Bundle\GeneratorBundle\Generator\Generator;

class BundleGenerator extends Generator
{
    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function generate($namespace, $bundle, $dir, $format, $structure, $dependency=true)
    {
        $dir .= '/'.strtr($namespace, '\\', '/');
        if (file_exists($dir))
        {
            if (!is_dir($dir))
                throw new \RuntimeException(sprintf('Unable to generate the bundle as the target directory "%s" exists but is a file.', realpath($dir)));

            $files = scandir($dir);
            if ($files != array('.', '..'))
                throw new \RuntimeException(sprintf('Unable to generate the bundle as the target directory "%s" is not empty.', realpath($dir)));
 
            if (!is_writable($dir))
                throw new \RuntimeException(sprintf('Unable to generate the bundle as the target directory "%s" is not writable.', realpath($dir)));
        }

        $basename = substr($bundle, 0, -6);
        $parameters = array(
            'bundle' => $bundle,
            'format' => $format,
            'namespace' => $namespace,
            'bundleflag' => $dependency?'08cms':'08themes',
            'bundle_basename' => $basename,
            'extension_alias' => Container::underscore($basename),
        );
        $this->filesystem->mkdir($dir.'/Resources/validation');        
        $this->renderFile('bundle/Bundle.php.twig', $dir.'/'.$bundle.'.php', $parameters);        
        $this->renderFile('bundle/index.html.twig.twig', $dir.'/Resources/views/Default/index.html.twig', $parameters);
        $this->renderFile('bundle/login.html.twig.twig', $dir.'/Resources/views/Default/login.html.twig', $parameters);
        
        if($dependency)
        {
            $this->renderFile('bundle/Extension.php.twig', $dir.'/DependencyInjection/'.$basename.'Extension.php', $parameters);
            $this->renderFile('bundle/Configuration.php.twig', $dir.'/DependencyInjection/Configuration.php', $parameters);
            $this->renderFile('bundle/Controller.php.twig', $dir.'/Controller/Controller.php', $parameters);
            $this->renderFile('bundle/DefaultController.php.twig', $dir.'/Controller/DefaultController.php', $parameters);
            $this->renderFile('bundle/DefaultControllerTest.php.twig', $dir.'/Tests/Controller/DefaultControllerTest.php', $parameters);
            if ('xml' === $format || 'annotation' === $format) {
                $this->renderFile('bundle/services.xml.twig', $dir.'/Resources/config/services.xml', $parameters);
                $this->filesystem->touch($dir.'/Resources/config/db.xml');
                $this->filesystem->touch($dir.'/Resources/config/twig.xml');
            } else {
                $this->renderFile('bundle/services.'.$format.'.twig', $dir.'/Resources/config/services.'.$format, $parameters);
                $this->filesystem->touch($dir.'/Resources/config/db.'.$format);
                $this->filesystem->touch($dir.'/Resources/config/twig.'.$format);
            }
    
            if ('annotation' != $format) {
                $this->renderFile('bundle/routing.'.$format.'.twig', $dir.'/Resources/config/routing.'.$format, $parameters);
            }
        }

        if ($structure) {
            $this->renderFile('bundle/messages.fr.xlf', $dir.'/Resources/translations/messages.fr.xlf', $parameters);

            $this->filesystem->mkdir($dir.'/Resources/doc');
            $this->filesystem->touch($dir.'/Resources/doc/index.rst');
            $this->filesystem->mkdir($dir.'/Resources/translations');
            $this->filesystem->mkdir($dir.'/Resources/public/css');
            $this->filesystem->mkdir($dir.'/Resources/public/images');
            $this->filesystem->mkdir($dir.'/Resources/public/js');            
            $this->filesystem->touch($dir.'/Resources/public/css/index.rst');
            $this->filesystem->touch($dir.'/Resources/public/images/index.rst');
            $this->filesystem->touch($dir.'/Resources/public/js/index.rst');
        }
    }
    
    public function createFile($template, $target, $parameters)
    {
        return $this->renderFile($template, $target, $parameters);
    }
    
    public function parseTemplatePath($template)
    {
        $data = $this->parseLogicalTemplateName($template);
    
        return $data['controller'].'/'.$data['template'];
    }
    
    protected function parseLogicalTemplateName($logicalname, $part = '')
    {
        $data = array();
    
        list($data['bundle'], $data['controller'], $data['template']) = explode(':', $logicalname);
    
        return ($part ? $data[$part] : $data);
    }
}