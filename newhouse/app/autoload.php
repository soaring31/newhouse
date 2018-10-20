<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Composer\Autoload\ClassLoader;

/**
 * @var ClassLoader $loader
 */

$loader = require dirname(dirname(__DIR__)).'/newcore/vendor/autoload.php';
$loader->add('',array(dirname(__DIR__) . '/site',dirname(__DIR__) . '/src'),true);

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

return $loader;
