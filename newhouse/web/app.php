<?php
use Symfony\Component\HttpFoundation\Request;

require_once __DIR__.'/../app/bootstrap.php.cache';

require_once __DIR__.'/../app/AppKernel.php';
require_once __DIR__.'/../app/AppCache.php';

//页面只能被本站页面嵌入到iframe或者frame中。
header('X-Frame-Options: SAMEORIGIN');

$kernel = new AppKernel('prod', false);
$kernel->loadClassCache();
$kernel = new AppCache($kernel);

//接受请求
$request = Request::createFromGlobals();

//处理请求
$response = $kernel->handle($request);

//返回响应
$response->send();

//完成一次请求后的处理
$kernel->terminate($request, $response);