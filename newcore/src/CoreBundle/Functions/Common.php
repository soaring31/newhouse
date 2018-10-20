<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package Tripod
* create date 2015-4-14
*/
namespace CoreBundle\Functions;

use Doctrine\ORM\Query\Expr;
use CoreBundle\Services\ServiceBase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * 公共函数服务
 *
 */
class Common extends ServiceBase
{
    /**
     * 容器
     * @var object
     */
    protected $container;

    /**
     * 表前缀
     * @var string
     */
    protected $prefix;

    /**
     * 默认bundle
     */
    protected $bundle;

    /**
     * 加密串
     */
    protected $encodekey = 'n7eaojb';

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->prefix = $container->getParameter('_tblprefix');
    }

    public function getUserDb()
    {
        $multidb = (int)self::C('multidb');

        //启用多库
        if($multidb==1){
            try {
                $locale =$this->get('request')->getLocale();
                $lang = $this->get('db.language')->getData($locale, 'ename');

                if(isset($lang['userdb'])&&$lang['userdb'])
                    return $lang['userdb'];
            } catch (\Exception $e) {

            }

            $user = self::getUser();

            if(method_exists($user, 'getUserdb')&&$user->getUserdb())
                return $user->getUserdb();
        }

        $userBundle = strtolower(self::getUserBundle());

        //去掉bundle后缀
        if (preg_match('/bundle$/', $userBundle))
            $userBundle = substr($userBundle, 0, -6);

        return $userBundle;
    }
    
    /**
     * 用于决定使用哪个 entity_manager
     * @return string
     */
    public function getUserBundle()
    {
        //取默认参数
        $defaultEntityManager = self::ucWords(self::C('doctrine.default_entity_manager'));

        //判断是否嵌入Bundle后缀
        if (!preg_match('/Bundle$/', $defaultEntityManager))
            $defaultEntityManager = $defaultEntityManager."Bundle";

        return $defaultEntityManager;

        $multidb = (int)self::C('multidb');

        //不启用多库
        if($multidb==0)
            return $defaultEntityManager;

        $bundle = self::getBundleName();

        $user = self::getUser();

        if(method_exists($user, 'getUserdb')&&$user->getUserdb()&&$user->getMid()==1)
            return $user->getUserdb();

        if($bundle=='Symfony:Bundle:FrameworkBundle')
            $bundle = $defaultEntityManager;

        return $bundle?$bundle:$defaultEntityManager;
    }

    /**
     * 嵌入表前缀
     * @param string $name
     * @return string
     */
    public function prefixName($name)
    {
        //判断是否嵌入过表前缀
        if (false === strpos($name, $this->prefix))
            $name = $this->prefix . $name;

        return $name;
    }

    /**
     * 过滤表前缀
     * @param string $name
     * @return mixed
     */
    public function unprefixName($name)
    {
        //判断是否嵌入过表前缀
        if (false !== strpos($name, $this->prefix))
            $name = str_replace(array($this->prefix),"",$name);

        return $name;
    }

    /**
     * 嵌入Bundle前缀
     * @param string $name
     * @return string
     */
    public function prefixEntityName($name, $bundle="")
    {
        $name = self::ucWords($name);
        $bundle = $bundle ? $bundle.':' : self::getUserBundle().':';
        //判断是否嵌入过Bundle前缀
        if (false === strpos($name, ':')) {
            $name = $bundle.$name;
        }
        return $name;
    }

    /**
     * 去除 Bundle 前缀
     * @param string $name xxxBundle:YYY
     * @return string YYY
     */
    public function unprefixEntityName($name)
    {
        if(false !== $pos = strpos($name, ':')) {
            $name = substr($name,1 + $pos);
        }
        return $name;
    }

    /**
     * psr-0命名规则
     * @param string $str
     * @return string
     */
    public function ucWords($str)
    {
        return $this->get('core.common.base')->ucWords($str);
    }
    /**
     * 去驼峰
     * @param string $str
     * @return string
     */    
    public function unCamelize($camelCaps,$separator='_')
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', "$1" . $separator . "$2", $camelCaps));
    }
    
    /**
     * 是否多城市版
     * @return boolean
     */
    public function isMultiCity()
    {
        return !$this->get('core.common.base')->isSingleCity();
    }
    /**
     * 对象转成数组
     * @param object $metadata
     * @param int $id
     */
    public function toArray($metadata)
    {
        $data = array();

        if(!is_object($metadata))
            return $data;

        foreach($metadata->column as $v)
        {
            $data[$v] = $metadata->entity->{"get" . $this->ucWords($v)}();
        }

        return $data;
    }

    /**
     * 存储类型
     * @param string $type
     * @return Ambigous <multitype:string , string>
     */
    public function storageTypes($type='')
    {
        $storagetype = array(
            1=>'数据库'
            ,2=>'文件缓存'
            ,3=>'所有'
        );
        return $type?(isset($storagetype[$type])?$storagetype[$type]:$type):$storagetype;
    }

    /**
     * 运算符号
     * @param string $type
     */
    public function operationSymbol($type='')
    {
        $operationSymbol = array(
            'eq'=>'='
            ,'neq'=>'<>'
            ,'lt'=>'<'
            ,'lte'=>'<='
            ,'gt'=>'>'
            ,'gte'=>'>='
            ,'like'=>'like'
            ,'notLike'=>'notLike'
            ,'in'=>'in'
            ,'notIn'=>'notIn'
            ,'between'=>'between'
            ,'andX'=>'andX'
            ,'orX'=>'orX'
            ,'find'=>'find'
        );
        return $type?(isset($operationSymbol[$type])?$operationSymbol[$type]:$type):$operationSymbol;
    }

    /**
     * 标识类型名
     * @param string $type
     * @return Ambigous <multitype:string , string>
     */
    public function identTypeName($type='')
    {
        $identTypeName = array(
            'identName'=>'标识名称'
            ,'tableName'=>'表&nbsp;&nbsp;名&nbsp;&nbsp;称'
            ,'aliasName'=>'表&nbsp;&nbsp;别&nbsp;&nbsp;名'
            ,'storagetype'=>'存储类型'
            ,'alias'=>'字段别名'
            ,'query'=>'查询参数'
            ,'join'=>'关联查询'
            ,'pageIndex'=>'当前页码'
            ,'pageSize'=>'每页数量'
            ,'order'=>'排序字段'
            ,'orderBy'=>'排&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;序'
            ,'joinType'=>'关联类型'
        );
        return $type?(isset($identTypeName[$type])?$identTypeName[$type]:$type):$identTypeName;
    }

    /**
     * 获取默认Bundle名称
     * @return mixed
     */
    public function getDefaultBundle()
    {
        return self::getUserBundle();
    }

    /**
     * 获取表前缀
     * @return string
     */
    public function getTblprefix()
    {
        return $this->prefix;
    }

    /**
     * 判断请求是否为ajax
     * @return boolean
     */
    public function isAjax()
    {
        return $this->get('core.common.base')->isAjax();
    }

    /**
     * 判断是否为手机
     * @return boolean
     */
    public function isMobile($phone)
    {
        $str = array();
        //手机号码验证
        $partern = '/^\d{11,13}$/i';
        preg_match($partern, $phone, $str);

        return empty($str)?false:true;
    }

    /**
     * 判断是否为手机浏览器
     * @return boolean
     */
    public function isMobileClient()
    {
        // 如果来访的是搜索引擎，则返回false，代表全部都作PC端处理
        if ($this->isCrawler())
            return false; 
        $isApp = $this->get('request')->get('_isApp','');
        if($isApp)
            return true;
        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
        if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
            return true;

        // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息,找不到为flase,否则为true
        if (isset ($_SERVER['HTTP_VIA']))
            return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
        // 脑残法，判断手机发送的客户端标志,兼容性有待提高
        if (isset ($_SERVER['HTTP_USER_AGENT']))
        {
            
            $clientkeywords = array ('nokia',
                'sony',
                'ericsson',
                'mot',
                'samsung',
                'htc',
                'sgh',
                'lg',
                'sharp',
                'sie-',
                'philips',
                'panasonic',
                'alcatel',
                'lenovo',
                'iphone',
                'ipod',
                'blackberry',
                'meizu',
                'android',
                'netfront',
                'symbian',
                'ucweb',
                'windowsce',
                'palm',
                'operamini',
                'operamobi',
                'openwave',
                'nexusone',
                'cldc',
                'midp',
                'wap',
                'mobile',
                'MQQBrowser',
                'mqqbrowser',
                'QBWebViewType',
                'qbwebviewtype'
            );
            // 从HTTP_USER_AGENT中查找手机浏览器的关键字
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])))
                return true;
        }

        // 协议法，因为有可能不准确，放到最后判断
        if (isset ($_SERVER['HTTP_ACCEPT']))
        {
            // 如果只支持wml并且不支持html那一定是移动设备
            // 如果支持wml和html但是wml在html之前则是移动设备
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html'))))
                return true;
        }

        return false;
    }




    /**
     * 获得当前路由的bundle名称
     */
    public function getBundleName($flag=false)
    {
        $controller = $this->get('request')->get('_controller');

        //去掉反斜杠
        $pattern = array();
        $pattern[0] = "/\\\\/";
        $pattern[1] = "/\//";
        $controller = preg_replace($pattern,":",$controller);

        //正则匹配取Bundle之前的字符串
        $pattern = "/(.*)Bundle:/";
        $matches = array();

        if(preg_match($pattern, $controller, $matches))
            return $flag?strtolower($matches[1]):$matches[1]."Bundle";

        return "";
    }

    /**
     * 获得当前路由的控制器名称
     */
    public function getControllerName($controller=null)
    {
        $controller = $controller?$controller:$this->get('request')->get('_controller');

        $pattern = "#Controller\\\\([a-zA-Z0-9]*)Controller#";

        $matches = array();

        if(preg_match($pattern, $controller, $matches))
            return strtolower($matches[1]);

        $matches = explode(":", $controller);

        return isset($matches[1])?strtolower($matches[1]):'';
    }

    /**
     * 获得当前路由的动作名称
     */
    public function getActionName($controller=null)
    {
        $controller = $controller?$controller:$this->get('request')->get('_controller');

        $pattern = "#::([a-zA-Z0-9]*)Action#";

        $matches = array();

        if(preg_match($pattern, $controller, $matches))
            return strtolower($matches[1]);

        $matches = explode(":", $controller);

        //去掉Action后缀
        if(isset($matches[2]))
            return preg_replace('/Action$/', '', $matches[2]);

        //去掉Action后缀
        if(isset($matches[1]))
            return preg_replace('/Action$/', '', $matches[1]);

        return "";
    }

    /**
     * 获得指定Bundle
     */
    public function getBundle($bundlename, $str='08cms')
    {
        $bundlePath = "";
        $bundles = self::getBundles($str);

        if(!isset($bundles[$bundlename]))
            return $bundlePath;

        return new $bundles[$bundlename]();
    }
    
    /**
     * 获得所有的已加载Bundle
     */
    public function getBundles($str='08cms')
    {
        $data = array();
    
        $bundles = self::getParameter('kernel.bundles');
    
        foreach($bundles as $k=>$bundle)
        {
            $info = new $bundle();
            if(method_exists($info, 'getCompany')&&$str==$info->getCompany())
                $data[$k] = $bundle;
        }
        return $data;
    }

    /**
     * 数据输出
     * ajax请求默认输出去json格式
     * 非ajax请求有跳转的跳转，无跳转的直接到错误模版输出
     * @param string $message
     * @param int $status
     * @param string $jumpUrl
     * @param bool $ajax
     * @return array()
     */
    public function showMessage($message, $status=false, array $newdata=array(), $jumpUrl='', $ajax=false)
    {
        $data			= array();
        $data['info']	= $message;
        $data['status']	= $status;
        $data['url']    = $jumpUrl;
        //$data['jumpUrl']= $jumpUrl;
        $data['waitSecond']= 5;

        //参数合并
        $data = array_merge($data,$newdata);

        //AJAX提交
        if(true === $ajax || self::isAjax())
            return $this->ajaxReturn($data, 'json');

        //非Ajax输出模版
        if($status)
            return $this->get('templating')->renderResponse('CoreBundle:Dispatch:success.html.twig', $data);
        else
            return $this->get('templating')->renderResponse('CoreBundle:Dispatch:error.html.twig', $data);
    }

    public function getQueryArray($str)
    {
        $params = array();

        if(empty($str))
            return $params;

        if(is_array($str))
            $params = $str;
        else{
            //把空格、换行符、英文分号、中文分号、中文逗号等替换成英文逗号的正则表达式(\n)|(\\s)|(\t)|
            $str = preg_replace("/(\r\n)|(\\r)|(\\n)/" ,'&' ,$str);

            //查询字符串解析到变量中
            parse_str($str, $params);
        }

        foreach($params as $k=>&$v)
        {
            $vvvs = $v;
            //排序字段
            if($k=='orderBy'||$k=='order'||$k=='groupBy')
                continue;

            if(is_array($v))
            {
                $expr = key($v);
                $item = end($v);
                $item = is_array($item)?$item:explode("|",$item);
            }else{
                $item = explode("|",$v);
                $expr = 'eq';

                if(count($item)>1)
                    $expr = array_shift($item);
            }

            $v = array();
            if($expr!='orX'&&$expr!='andX')
            {
                $value = self::getParams(array_pop($item));

                if(!is_numeric($value)&&$value=="")
                {
                    unset($params[$k]);
                    continue;
                }
            }

            switch($expr)
            {
                case 'eq':
                case 'neq':
                case 'lt':
                case 'lte':
                case 'gt':
                case 'gte':
                case 'find':
                case 'like':
                case 'notLike':
                    $itemss = explode("|",$value);
                    if(count($itemss)>1)
                    {
                        $itemsv = explode(',',end($itemss));

                        $_itemsk = array_shift($itemss);

                        if($_itemsk=='between')
                        {
                            if(count($itemsv)==2)
                                $v[$_itemsk] = $itemsv;
                            else
                                $v['gte'] = end($itemsv);
                        }else{
                            $v[$_itemsk] = end($itemsv);
                        }
                    }else
                        $v[$expr] = $value;
                    break;
                case 'in':
                case 'notIn':
                    if(is_array($vvvs))
                        $v[$expr] = self::getParams(end($vvvs));
                    else
                        $v[$expr] = self::getParams($value);

                    $v[$expr] = !is_array($v[$expr])?explode(',',$v[$expr]):$v[$expr];
                    break;
                case 'orX':
                case 'andX':
                    if(is_array($item))
                    {
                        foreach($item as $its)
                        {
                            if(is_array($its))
                            {
                                if(is_array(end($its)))
                                {
                                    $itsKey = key($its);
                                    $its = end($its);
                                    $v[$expr][][$itsKey][key($its)] = self::getParams(end($its));
                                }else{
                                    $v[$expr][][key($its)]['eq'] = self::getParams(end($its));
                                }
                            }else{
                                $its = explode(',',$its);

                                $value = self::getParams(end($its));

                                if(!is_numeric($value)&&$value=="")
                                    continue;

                                if(count($its)==2)
                                    $v[$expr][][$its[0]]['eq'] = $value=='null'?'':$value;

                                if(count($its)==3)
                                    $v[$expr][][$its[0]][$its[1]] = $value=='null'?'':$value;
                            }
                        }
                    }
                    if(empty($v))
                        unset($params[$k]);
                    break;
                case 'between':

                    $vals = explode(',', $value);

                    if(count($vals)>1)
                        $item = array_merge($item, $vals);
                    else
                        $item[] = end($vals);

                    //如只有一个参数则转换为大于等于(gte)条件
                    if(count($item)==1)
                        $v['gte'] = end($item);

                    if(count($item)>1)
                    {
                        $param = array();
                        $param[0] = self::getParams(array_shift($item));
                        $param[1] = self::getParams(array_shift($item));
                        $v[$expr] = $param;
                    }
                    break;
                default:

                    $v['eq'] = self::getParams($expr);
                    break;
            }
        }

        return $params;
    }

    public function getMapArray($str, $obj=null)
    {
        $params = array();

        if(empty($str))
            return $params;

        if(is_array($str))
            $params = $str;
        else{
            //把空格、换行符、英文分号、中文分号、中文逗号等替换成英文逗号的正则表达式(\n)|(\\s)|(\t)|
            $str = preg_replace("/(\r\n)|(\\r)|(\\n)/" ,'&' ,$str);

            //查询字符串解析到变量中
            parse_str($str, $params);
        }

        foreach($params as &$v)
        {
            $v = self::getParams($v, $obj);
        }

        return $params;
    }

    /**
     * 查询参数组装
     * 把空格、换行符、英文分号、中文分号、中文逗号等替换成英文&的正则表达式
     * @param string $str
     * @return multitype:|multitype:string
     */
    public function getQueryParam($str, $obj=null)
    {
        $params = array();

        if(empty($str))
            return $params;

        if(is_array($str))
            $params = $str;
        else{
            //把空格、换行符、英文分号、中文分号、中文逗号等替换成英文逗号的正则表达式(\n)|(\\s)|(\t)|
            $str = preg_replace("/(\r\n)|(\\r)|(\\n)/" ,'&' ,$str);

            //查询字符串解析到变量中
	        parse_str($str, $params);
        }

        foreach($params as $k=>$v)
        {
            $item = explode("|",$v);


            $expr = count($item)>1?array_shift($item):end($item);


            $v = array();

            switch($expr)
            {
                case 'eq':
                case 'neq':
                case 'lt':
                case 'lte':
                case 'gt':
                case 'gte':
                case 'find':
                case 'max':
                case 'min':
                case 'like':
                case 'notLike':
                    $v[$expr] = self::getParams(array_shift($item), $obj);
                    break;
                case 'in':
                case 'notIn':
                    $v[$expr] = self::getParams(array_shift($item));
                    break;
                case 'orX':
                    //$kid = array_shift($item);
                    foreach($item as $_itm)
                    {
                        $itm = explode(',',$_itm);

                        if(count($itm)==2)
                            $v['orX'][][array_shift($itm)]['eq'] = self::getParams(array_shift($itm), $obj);
                        else
                            $v['orX'][][array_shift($itm)][array_shift($itm)] = self::getParams(array_shift($itm), $obj);
                    }
                    break;
                case 'between':

                    //如只有一个参数则转换为大于等于(gte)条件
                    if(count($item)==1)
                    {
                        $item = explode(',', end($item));
                        if(count($item)==1)
                            $v['between'] = self::getParams(array_shift($item), $obj);
                    }


                    if(count($item)>1)
                    {
                        $param = array();
                        $param[0] = self::getParams(array_shift($item), $obj);
                        $param[1] = self::getParams(array_shift($item), $obj);
                        $v[$expr] = $param;
                    }

                    break;
                case 'time':
                    //如只有一个参数则转换为大于等于(gte)条件
                    if(count($item)==1)
                        $v['eq'] = time()+array_shift($item);

                    $v[array_shift($item)] = time()+array_shift($item);

                    break;
                default:
                    $v = self::getParams($expr, $obj);
                    break;
            }
            unset($params[$k]);
            if(is_numeric($k))
                $params[$k] = $v;
            else
                $params[self::getParams($k)] = $v;
        }

        return $params;
    }

    public function getQueryExt($str)
    {
        return self::getParams($str);
    }

    /**
     * 获取site绝对路径
     */
    public function getSiteRoot()
    {
        $root_dir = dirname(self::getParameter('kernel.root_dir'));
        return $root_dir.DIRECTORY_SEPARATOR;
    }

    /**
     * 获取temp绝对路径
     */
    public function getTempRoot()
    {
        $root_dir = dirname(self::getParameter('kernel.root_dir'));
        return $root_dir.DIRECTORY_SEPARATOR."temp".DIRECTORY_SEPARATOR;
    }

    /**
     * 获取web绝对路径
     */
    public function getWebRoot()
    {
        $root_dir = dirname(self::getParameter('kernel.root_dir'));
        return $root_dir.DIRECTORY_SEPARATOR."web".DIRECTORY_SEPARATOR;
    }

    /**
     * 获取licence绝对路径
     */
    public function getLicenceRoot()
    {
        $root_dir = dirname(self::getParameter('kernel.root_dir'));
        return $root_dir.DIRECTORY_SEPARATOR."licence".DIRECTORY_SEPARATOR;
    }

    /**
     * 获得配置文件路径
     * @param string $bundlename
     */
    public function getConfigPath($bundlename, $filename)
    {
        $routePath = "";
        $bundlesPath = self::getBundlePath();

        if(!$bundlesPath)
            return $routePath;
    }

    /**
     * 获得路径，基于Bundle名
     * @param string $bundlename
     */
    public function getBundlePath($bundlename, $type = '08cms')
    {
        $bundlePath = "";
        $bundles = self::getBundles($type);

        if(!isset($bundles[$bundlename]))
            return $bundlePath;

        $bundleInfo = new $bundles[$bundlename]();

        return $bundleInfo->getPath().DIRECTORY_SEPARATOR;
    }

    /**
     * 获取标识存放目录
     * @param string $bundlename
     */
    public function getIdentPath($bundlename)
    {
        return self::getBundlePath($bundlename)."Resources".DIRECTORY_SEPARATOR."ident".DIRECTORY_SEPARATOR;
    }

    /**
     * 获得命名空间，基于Bundle名
     * @param string $bundlename
     * @return string
     */
    public function getBundleNamespace($bundlename)
    {
        $bundlename = self::ucWords($bundlename);

        //判断是否嵌入Bundle后缀
        if (!preg_match('/Bundle$/', $bundlename))
            $bundlename = $bundlename."Bundle";

        $bundlePath = "";
        $bundles = self::getBundles();

        if(!isset($bundles[$bundlename]))
            return $bundlePath;

        $bundleInfo = new $bundles[$bundlename]();

        return $bundleInfo->getNamespace();
    }

    /**
     * 生成唯一标识
     */
    public function createIdentifier()
    {
        return md5(uniqid(md5(microtime(true)),true));
    }

    /**
     * 加密算法之加密（支持中文）
     * 注：目前基本用于 url 加密，加密的数据不用于保存，且与第三方的加解密无关
     * @param String $string 需要加密的字串
     * @param String $skey 加密EKY
     * @return String
     */
    public function encode($string, $skey = 'hex')
    {
        $this->get('core.rsa')->reset("rsa512");
        $return = $this->get('core.rsa')->encrypt($string, $skey);
        return $return;
    }

    /**
     * 加密算法之解密（支持中文）
     * @param String $string 需要解密的字串
     * @param String $skey 解密KEY
     * @return String
     */
    public function decode($string, $skey = 'hex')
    {
        $this->get('core.rsa')->reset("rsa512");
        $string = $this->get('core.rsa')->decrypt($string, $skey);

        if($string === false)
            throw new \InvalidArgumentException("数据解码错误");

        return $string;
    }

    /**
     * 异或加加密
     * 注：用于微信公众号 appid，会保存到数据库
     * @param $key 密钥
     */
    public function xorEncode($str)
    {
        //相当于动态密钥
        $key = md5($this->encodekey);
        $tmp = "";
        for($i=0;$i<strlen($str);$i++){
            $tmp.=substr($str,$i,1) ^ substr($key,$i,1);
        }
        return self::base_encode($tmp);
    }

    /**
     * 异或加解密
     * @param $key 密钥
     */
    public function xorDecode($str)
    {
        $key = md5($this->encodekey);
        $str = self::base_decode($str);
        $tmp = "";
        for($i=0;$i<strlen($str);$i++){
            $tmp.=substr($str,$i,1) ^ substr($key,$i,1);
        }
        return $tmp;
    }

    /**
     * 根据ID计算文件夹目录结构
     * @param int $id
     * @return string
     */
    public function getFileDir($id)
    {
        $_id = $id;
        if((int)$id>0){
            $id = sprintf("%09d", $id);
            return substr($id, 0, 3) . DIRECTORY_SEPARATOR . substr($id, 3, 2) . DIRECTORY_SEPARATOR . substr($id, 5, 2).DIRECTORY_SEPARATOR.$_id;
        }

        throw new \InvalidArgumentException("ID必须为数字");
    }

    /**
     * 树形结构数据组装 (递归, 注：有时间改成迭代)
     * @param object|array $arr
     * @param number $pid
     * @param string $name
     */
    public function treeAssemble(array $arr, $pid=0, $name='pid')
    {
        $data = array();

        //如果pid为0,则取最小id
        if($pid==0)
        {
            $ppid = 99999999;
            foreach($arr as $v)
            {
                if(is_object($v))
                    $v = $this->get('serializer')->normalize($v);

                if($v[$name]<$ppid)
                {
                    $ppid = $v[$name];
                    $pid = $v[$name];
                }
            }
        }

        //循环
        foreach($arr as $k=>$v)
        {
            if(is_object($v))
                $v = $this->get('serializer')->normalize($v);

            $id = $v['id'];
            if($v[$name]==$pid)
            {
                $data[$id] = $v;

                unset($arr[$k]);

                //组装树
                $data[$id]['next'] = self::treeAssemble($arr, $id, $name);
            }
        }

        return $data;
    }

    public function treeAssemble1(array $arr, $pid=0, $name='pid')
    {
        $data = array();

        foreach($arr as $k=>$v)
        {
            if(is_object($v))
                $v = $this->get('serializer')->normalize($v);

            $id = $v['id'];
            if($v[$name]==$pid)
            {
                $data[$id] = $v;

                unset($arr[$k]);

                $data[$id] = self::treeAssemble1($arr, $id, $name);
            }
        }

        return $data;
    }

    public function getTodayDate()
    {
        $date = new \DateTime(date('Y-m-d'));
        return $date->format('U');
    }

    /**
     * 上下架
     * @param number $flag
     * @param string $model
     */
    public function gettoday($flag = 0, $model = null)
    {
        $qx = (int)$flag;

        if ($flag && $model)
        {
            $user = self::getUser();
            if(!is_object($user))
                return ;

            $groupId = $user->getUserinfo()->getGroupid();

            if ($groupId)
            {
                $info = $this->get('house.userconfig')->findOneBy(array('role'=>$groupId), array(), false);
                if ($info)
                {
                    switch ($model)
                    {
                        case 'sale':
                        case 'rent':
                            //租售有效期
                            $qx = $info->getRentalperiod()*24*60*60;
                            break;
                        case 'demand':
                            //需求有效期限
                            $qx = $info->getDemandqx()*24*60*60;
                            break;
                    }
                }
            }
        }

        return time()+$qx;
    }

    /**
     *  通过数据库获取所有元素，通过下面函数构造树形结构(迭代算法)
     * @param array $menus
     * @return multitype:\CoreBundle\Functions\stdClass
     */
    public function getTree(array $menus, $pid=0, &$menuobjs = array(), $falg=true, $hasSuffix=false)
    {
        $tree = array();
        $notrootmenu = array();

        //取最小id
        if($pid==0)
        {
            $ppid = 99999999;
            foreach($menus as $menu)
            {
                if(is_object($menu))
                    $mpid = $menu->getPid();
                else
                    $mpid = $menu['pid'];

                if($mpid<$ppid)
                {
                    $ppid = $mpid;
                    $pid = $mpid;
                }
            }
        }

        //循环
        foreach($menus as $menu)
        {
            //判断是对象类型还是数组类型
            if(is_object($menu))
            {
                $id = $menu->getId();
                $mpid = $menu->getPid();

                //对url参数的操作
                if(method_exists($menu, 'getUrlparams')&&$falg)
                {
                    $menu->setUrlparams(self::getQueryParam($menu->getUrlparams()));

                    if(method_exists($menu, 'getCategory')&&$menu->getCategory())
                        $menu->setUrlparams(array_merge($menu->getUrlparams(),array('category'=>$menu->getCategory())));
                }
            }else{
                $id = $menu['id'];
                $mpid = $menu['pid'];

                //对url参数的操作
                if(isset($menu['urlparams'])&&$menu['urlparams']&&$falg)
                {
                    $menu['urlparams'] = self::getQueryParam($menu['urlparams']);

                    if(isset($menu['category'])&&$menu['category'])
                        $menu['urlparams'] = array_merge($menu['urlparams'],array('category'=>$menu['category']));
                }
            }

            $menuobj = new \stdClass();
            $menuobj->menu = $menu;

            $menuobj->nodes = array();
            $menuobjs[$id] = $menuobj;

            //根目录
            if ($pid==$mpid)
                $tree[$id] = $menuobj;
            else
                $notrootmenu[$id]=$menuobj;
        }

        foreach($notrootmenu as $menuobj)
        {
            $menu = $menuobj->menu;

            if(is_object($menu))
            {
                $id = $menu->getId();
                $mpid = $menu->getPid();
            }else{
                $id = $menu['id'];
                $mpid = $menu['pid'];
            }

            if ($hasSuffix)
                $menuobjs[$mpid]->nodes[]=$menuobj;
            else
                $menuobjs[$mpid]->nodes[$id]=$menuobj;
        }

        unset($menuobjs);
        unset($notrootmenu);

        return $tree;
    }

    /**
     * 组装基于于数组的树 (迭代算法)
     * @param array $menus
     * @param number $pid
     * @param array $menuobjs
     */
    public function getTreeByArray(array $menus, $pid=0, &$menuobjs = array())
    {
        $tree = array();
        $notrootmenu = array();
        foreach($menus as $menu)
        {
            $menuobj = new \stdClass();
            $menuobj->menu = $menu;
            $id = $menu['id'];
            $menuobj->nodes = array();
            $menuobjs[$id]=$menuobj;

            //根目录
            if ($pid==$menu['pid'])
                $tree[$id] = $menuobj;
            else
                $notrootmenu[$id]=$menuobj;
        }

        foreach($notrootmenu as $menuobj)
        {
            $menu = $menuobj->menu;
            $id = $menu['id'];
            $menuobjs[$menu['pid']]->nodes[$id]=$menuobj;
        }

        return $tree;
    }

    /**
     * 二叉数结构(迭代算法)
     * @param array $menus
     * @param array $ids
     * @param array $menuobjs
     */
    public function getBinaryTree(array $menus, array $ids, &$menuobjs = array())
    {
        $tree = array();
        $notRootMenu = array();

        foreach($menus as $menu)
        {
            $menuobj = new \stdClass();
            $menuobj->menu = $menu;
            $menuobj->nodes = array();
            $menuobjs[$menu->getId()]=$menuobj;

            //根目录
            if (isset($ids[$menu->getId()]))
                $tree[$menu->getId()] = $menuobj;
            else
                $notRootMenu[$menu->getPid()]=$menuobj;
        }

        foreach($notRootMenu as $menuobj)
        {
            $menu = $menuobj->menu;
            $menuobjs[$menu->getId()]->nodes[$menu->getPid()]=$menuobj;
        }
        return $tree;
    }

    /**
     * 获取基于level的树(迭代算法)
     * @param array $menus
     * @param int $level
     * @param array $menuobjs
     * @return multitype:\stdClass
     */
    public function getTreeByLevel(array $menus, $level, &$menuobjs = array())
    {
        $tree = array();
        $notRootMenu = array();
        foreach($menus as $menu)
        {
            $menuobj = new \stdClass();
            $menuobj->menu = $menu;
            $menuobj->nodes = array();
            $menuobjs[$menu->getId()]=$menuobj;

            //根目录
            if ($menu->getLevel()==$level)
                $tree[$menu->getId()] = $menuobj;
            else
                $notRootMenu[$menu->getId()] = $menuobj;
        }

        foreach($notRootMenu as $menuobj)
        {
            $menu = $menuobj->menu;
            $menuobjs[$menu->getPid()]->nodes[$menu->getId()] = $menuobj;
        }

        return $tree;
    }

    /**
     * 根据ID计算文件夹子目录结构
     * @param int $id
     * @return string
     */
    public function getFileSubDir($id)
    {
        if((int)$id>0)
        {
            $id = sprintf("%09d", $id);
            return substr($id, 0, 3) . DIRECTORY_SEPARATOR . substr($id, 3, 3);
        }

        throw new \InvalidArgumentException("ID必须为数字");
    }

    /**
     * 获得拼音首字大写字母
     * @param string $str
     * @return string
     */
    public function getCamelChars($str)
    {
        if(empty($str))
            return '';

        $fchar = ord($str{0});

        if($fchar >= ord("A") and $fchar <= ord("z") )
            return strtoupper($str{0});

        $s1 = iconv("UTF-8","gb2312", $str);
        $s2 = iconv("gb2312","UTF-8", $s1);

        if($s2 == $str)
            $s = $s1;
        else
            $s = $str;

        $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
        if($asc >= -20319 and $asc <= -20284) return "A";
        if($asc >= -20283 and $asc <= -19776) return "B";
        if($asc >= -19775 and $asc <= -19219) return "C";
        if($asc >= -19218 and $asc <= -18711) return "D";
        if($asc >= -18710 and $asc <= -18527) return "E";
        if($asc >= -18526 and $asc <= -18240) return "F";
        if($asc >= -18239 and $asc <= -17923) return "G";
        if($asc >= -17922 and $asc <= -17418) return "I";
        if($asc >= -17417 and $asc <= -16475) return "J";
        if($asc >= -16474 and $asc <= -16213) return "K";
        if($asc >= -16212 and $asc <= -15641) return "L";
        if($asc >= -15640 and $asc <= -15166) return "M";
        if($asc >= -15165 and $asc <= -14923) return "N";
        if($asc >= -14922 and $asc <= -14915) return "O";
        if($asc >= -14914 and $asc <= -14631) return "P";
        if($asc >= -14630 and $asc <= -14150) return "Q";
        if($asc >= -14149 and $asc <= -14091) return "R";
        if($asc >= -14090 and $asc <= -13319) return "S";
        if($asc >= -13318 and $asc <= -12839) return "T";
        if($asc >= -12838 and $asc <= -12557) return "W";
        if($asc >= -12556 and $asc <= -11848) return "X";
        if($asc >= -11847 and $asc <= -11056) return "Y";
        if($asc >= -11055 and $asc <= -10247) return "Z";
        return null;
    }

    /**
     * 判断方法是否存在并返回该值
     * @param object $ob
     * @param string $field
     */
    public function methodExists($ob, $field, $empty_data='')
    {
        $empty_data = self::getParams($empty_data);

        if(is_object($ob)){
            $func = "get" . self::ucWords($field);
            $info = method_exists($ob, $func)?$ob->$func():$empty_data;

            if(is_bool($info))
                return $info?1:0;

            return $info;

        }elseif(is_array($ob)){
            $func = lcfirst(self::ucWords($field));
            $info = isset($ob[$func])?$ob[$func]:(isset($ob[$field])?$ob[$field]:$empty_data);

            if(is_bool($info))
                return $info?1:0;

            return $info;
        }

        return false;
    }

    /**
     * 删除目录文件
     * @param string $dir
     * @return boolean
     */
    public function delDir($dir)
    {
        $filesystem = new Filesystem();
        $filesystem->remove($dir);

        return true;
    }

    /**
     * 获取用户地理IP信息
     * @param string $dir
     * @return boolean
     */
    public function getClientIp()
    {
        return $this->get('request')->getClientIp();
    }

    /**
     * 复制目录
     * @param string $src 原目录d
     * @param string $dst 复制到的目录
     */
    public function copyDir($src,$dst)
    {
        $dir = opendir($src);
        @mkdir($dst);
        while(false !== ( $file = readdir($dir)) )
        {
            if (( $file != '.' ) && ( $file != '..' ))
            {
                if ( is_dir($src . '/' . $file) )
                    self::copyDir($src .'/' . $file,$dst . '/' . $file);
                else
                    copy($src . '/' . $file,$dst . '/' . $file);
            }
        }

        closedir($dir);
    }

    /**
     * 更新资源包
     * @param string $bundle
     * @throws \InvalidArgumentException
     */
    public function upResource($bundle, $str='08cms')
    {
        if($bundle=="CoreBundle")
            $str = "08core";

        $bundles = self::getBundles($str);

        if(!isset($bundles[$bundle]))
            throw new \InvalidArgumentException(sprintf('无效的[%s]包', $bundle));

        $bundleInfo = new $bundles[$bundle]();

        $src_dir = $bundleInfo->getPath();

        $src_dir .= DIRECTORY_SEPARATOR."Resources".DIRECTORY_SEPARATOR."public";

        $dst_dir = dirname($this->get('request')->server->get('SCRIPT_FILENAME'));

        //正则匹配取Bundle之前的字符串
        $pattern = "/(.*)Bundle/";
        $matches = array();
        preg_match($pattern, $bundle, $matches);

        $dst_dir .= DIRECTORY_SEPARATOR."bundles".DIRECTORY_SEPARATOR.strtolower($matches[1]);

        $filesystem = new Filesystem();
        $filesystem->remove($dst_dir);
        $filesystem->mkdir($dst_dir);

        //判断文件夹是否存在，不存在则创建
        if(!$filesystem->exists($src_dir))
            $filesystem->mkdir($src_dir);

        self::copyDir($src_dir, $dst_dir);
    }

    public function getExpr($expr, $x, $y, $aliasName="")
    {
        $x = $aliasName?$aliasName.".".$x:$x;
        $exprF = new Expr();
        if(!is_array($y))
        {
            $y = preg_replace("/[|]/" ,',' ,$y);

            if($expr=='in'||$expr=='notIn')
                $y = trim($y,"'");

            $y = explode(",",$y);
        }

        switch($expr)
        {
            //等于
            case 'eq':
            //不等于
            case 'neq':
            //小于
            case 'lt':
            //小于等于
            case 'lte':
            //大于
            case 'gt':
            //大于等于
            case 'gte':
            case 'find':
            case 'like':
            case 'notLike':
                $values = $exprF->{$expr}($x, $y[0]);
                break;
            case 'in':
            case 'notIn':
                $values = $exprF->{$expr}($x, $y);
                break;
            case 'orX':
                $values = "";
                break;
            case 'between':
                //如只有一个参数则转换为大于等于(gte)条件
                if(count($y)==1)
                    $values = $exprF->gte($x, $y[0]);

                if(count($y)>1)
                    $values = $exprF->{$expr}($x, $y[0], $y[1]);
                break;
            case 'time':
                break;
            default:
                $values = $exprF->eq($x, $y[0]);
                break;
        }

        if(is_object($values))
            $values = $values->__toString();

        return $values;
    }

    /**
     * 获得user信息
     */
    public function getUser()
    {
        $token = $this->get('security.token_storage')->getToken();
        if (null === $token)
            return;

        if (!is_object($user = $token->getUser()))
            return;

        return $user;
    }

    /**
     * 获得attributes信息
     */
    public function getAttributes()
    {
        $user = self::getUser();

        $token = $this->get('security.token_storage')->getToken();

        if(!is_object($user))
            return is_object($token)?$token->getAttributes():array();

        //刷新权限
        if(!$this->get('request')->getSession()->get('reloadrole', 0))
        {
            $token = $this->get('core.rbac')->resetToken($token);
            $this->get('request')->getSession()->set('reloadrole', 1);
        }

        $attributes = $token->getAttributes();
        //强制分站管理员进入授权的分站后台
        $this->get('core.area')->forceAuthorizedArea();

        $token = array();
        $token['menus'] = isset($attributes['menus'])?$attributes['menus']:$this->get('core.rbac')->getMenuNodes($this->getUser()->getRules());

        return $token?$token:array();
    }

    public function getTokenAttr()
    {
        $user = self::getUser();

        if(!is_object($user))
        {
            $token = $this->get('security.token_storage')->getToken();
            return $token?$token->getAttributes():array();
        }

        $token = $this->get('security.token_storage')->getToken();

        return $token->getAttributes();
    }

    /**
     * 获得角色信息
     */
    public function getRoles()
    {
        $token = $this->get('security.token_storage')->getToken();
        if($token)
        {
            $roles = $token->getRoles();
            return current($roles);
        }
        return array();
    }

    /**
     * ajax输出数据
     * @param string $data
     * @param string $type
     */
    public function ajaxReturn($data,$type='')
    {
        $type = $this->get('request')->get('datatype', $type);

        if(empty($type))
            $type = self::isAjax()?'json':'EVAL';

        $data = $this->get('serializer')->normalize($data);

        switch (strtoupper($type)){
            case 'JSON' :
                // 返回JSON数据格式到客户端 包含状态信息,兼容5.5.x以上版本
                die(preg_replace_callback("#\\\\u([0-9a-f]{4})#i", function($match){
                    return iconv('UCS-2BE', 'UTF-8', pack('H4', $match[1]));
                }, json_encode($data)));
//                 die(preg_replace("#\\\\u([0-9a-f]{4})#ie", "iconv('UCS-2BE', 'UTF-8', pack('H4', '\\1'))", json_encode($data)));
                die(json_encode($data));
                break;
            case 'JSONP':
                // 返回JSON数据格式到客户端 包含状态信息

                $jsonp = new JsonResponse($data);
                $jsonp->setCallback('n08cms');
                die($jsonp->getContent());
                return $jsonp->getContent();
                break;
            case 'EVAL' :
                // 返回可执行的js脚本
                die(isset($data['msg'])?$data['msg']:$data);
                break;
            default     :
                // 用于扩展其他返回格式数据
                die(isset($data['msg'])?$data['msg']:$data);
                break;
        }
    }

    /**
     * 三目运算参数
     * @param string $str
     * @param string $obj
     */
    public function getParams($str, $obj=null)
    {
        $info = "";

        if($str=="null")
            return $info;

        $matches = array();
        $vvv = explode('?', $str);
        $user = self::getUser();

        //读$_REQUEST
        if (preg_match('/\[([a-zA-Z_0-9]+)\]/', $vvv[0], $matches))
        {
            $str = "";
            $io = $this->get('request')->get($matches[1],false);
            if($io===false&&count($vvv)<=1)
                return "";

            if($io!==false)
                $info = str_replace ( $matches[0], $io, $vvv[0] );
        }

        //读取指定对象数据(只适合监听)
        if (preg_match('/\<#([a-zA-Z_0-9]+)\>/', $vvv[0], $matches))
        {
            $str = "";

            if(is_object($obj))
            {
                //psr-0命名规则
                $field = self::ucWords($matches[1]);
                $field = "get".$field;

                if(!method_exists($obj, $field)&&count($vvv)<=1)
                    return "";

                if(method_exists($obj, $field))
                    $info = str_replace ( $matches[0], $obj->$field(), $vvv[0] );
            }else{
                if(!isset($obj[$matches[1]])&&count($vvv)<=1)
                    return "";

                if(isset($obj[$matches[1]]))
                    $info = str_replace ( $matches[0], $obj[$matches[1]], $vvv[0] );
            }

        //读取common服务
        }elseif (preg_match('/\<@([a-zA-Z_0-9|]+)\>/', $vvv[0], $matches)){
            $str = "";

            $matchesStr = explode('|', $matches[1]);

            $matches[1] = array_shift($matchesStr);


            //取得指定类的所有的方法名，并且组成一个数组
            //$common = get_class_methods($this->get('core.common'));

            if(!method_exists($this, $matches[1]))
                return "";

            //读取common内方法
            $info = str_replace ( $matches[0], call_user_func_array("self::{$matches[1]}",$matchesStr), $vvv[0] );

        }elseif (preg_match('/\<!([a-zA-Z]+)\>/', $vvv[0], $matches)){
            return 'null';
        }else{
            //读用户表
            if (preg_match('/\<\$([a-zA-Z_0-9]+)\>/', $vvv[0], $matches))
            {
                $str = "";

                //psr-0命名规则
                $field = self::ucWords($matches[1]);
                $field = "get".$field;

                if (!method_exists($user, 'getUserinfo'))
                    return '';

                $userinfo = $user->getUserinfo();

                if(!method_exists($userinfo, $field)&&count($vvv)<=1)
                    return "";

                if(method_exists($userinfo, $field))
                    $info = str_replace ( $matches[0], $userinfo->$field(), $vvv[0] );
            }elseif (preg_match('/\<([a-zA-Z_0-9]+)\>/', $vvv[0], $matches)){
                $str = "";
                //psr-0命名规则
                $field = self::ucWords($matches[1]);
                $field = "get".$field;

                if(!method_exists($user, $field)&&count($vvv)<=1)
                    return "";

                if(method_exists($user, $field))
                    $info = str_replace ( $matches[0], $user->$field(), $vvv[0] );
            }
        }

        //读session
        if (preg_match('/\{([a-zA-Z_0-9]+)\}/', $vvv[0], $matches)){
            $_info = $this->get('request')->getSession()->get($matches[1],'');
            $_info = is_array($_info)?implode(',',$_info):$_info;

            $info = str_replace ( $matches[0], $_info, $vvv[0] );
        }

        if(empty($info)&&isset($vvv[1]))
        {
            $info = $vvv[1];

            if (preg_match('/\[([a-zA-Z_0-9]+)\]/', $vvv[1], $matches))
            {
                $io = $this->get('request')->get($matches[1],false);
                if($io===false)
                    return "";

                $info = str_replace ( $matches[0], $io, $vvv[1] );
            }

            if (preg_match('/\<([a-zA-Z_0-9]+)\>/', $vvv[1], $matches))
            {
                //psr-0命名规则
                $field = self::ucWords($matches[1]);
                $field = "get".$field;

                if(!method_exists($user, $field))
                    return "";

                $info = str_replace ( $matches[0], $user->$field(), $vvv[1] );
            }

            if (preg_match('/\{([a-zA-Z_0-9]+)\}/', $vvv[1], $matches))
            {
                $sessionStr = $this->get('request')->getSession()->get($matches[1],'');
                $sessionStr = is_array($sessionStr)?implode(',',$sessionStr):$sessionStr;

                $info = str_replace ( $matches[0], $sessionStr, $vvv[1] );
            }
        }

        return $info==""&&!is_numeric($info)?$str:$info;
    }

    /**
     * 比较变量
     */
    public function comp(array $form, array $info)
    {

        $attr = isset($form['attr'])&&$form['attr']?self::getQueryParam($form['attr']):array();

        if(!isset($attr['vid'])||empty($attr['vid']))
            return false;

        $name = lcfirst(self::ucWords($form['name']));

        $vid = lcfirst(self::ucWords($attr['vid']));

        if(!isset($info[$name])||!isset($info[$vid]))
            return false;

        if($info[$name]===$info[$vid])
            return true;

        $message = isset($form['error_info'])&&$form['error_info']?$form['error_info']:sprintf("%s不一致",$form['label']);
        throw new \InvalidArgumentException($message);
    }

    public function C($str)
    {
        return self::getParameter($str);
    }

    public function handleVars($vars)
    {
        // 解析参数
        if(is_string($vars))
            parse_str($vars,$vars);
        elseif(!is_array($vars))
            $vars = array();

        if(is_array($vars))
        {
            foreach($vars as &$vv)
            {
                if(!is_array($vv))
                    continue;

                $values = end($vv);

                switch(key($vv))
                {
                    case 'eq':
                        $vv = is_array($values)?implode(',',$values):$values;
                        break;
                    case 'andX':
                    case 'orX':
                        if(!is_array($vv))
                            continue;

                            $expr = key($vv);

                            $arr = array();
                            if(is_array(end($vv)))
                            {

                                foreach(end($vv) as $its)
                                {
                                    $arrs = array();
                                    $arrs[] = key($its);
                                    $its = end($its);
                                    if(is_array($its))
                                    {
                                        $arrs[] = key($its);
                                        $arrs[] = end($its);
                                    }else{
                                        $arrs[] = $its;
                                    }
                                    $arr[] = implode(',', $arrs);
                                }
                            }

                            $vv = $expr.'|'.implode('|',$arr);
                            break;
                    default:
                        $vv = key($vv)."|".(is_array($values)?implode(',',$values):$values);
                        break;
                }
            }
        }
        return $vars;
    }

    public function U($url='', $vars='', $domain=false)
    {
        // 解析URL
        $info = parse_url($url);
        
        if(!empty($info['host']))
            return $url;
        
        //基路径
        $baseUrl = $this->get('request')->getScriptName();
        $baseUrl = str_replace("/app.php","",$baseUrl);

        $dataType = (bool)$this->get('request')->get('_isApp', '');

        if(empty($url))
        {
            $urlArr = array();
            //$urlArr[] = self::getBundleName(true);
            $urlArr[] = self::getControllerName();
            $urlArr[] = self::getActionName();

            $url = implode('/',$urlArr);
        }

        $urlArr = explode('/',$url);

        //匹配路径标记
        $matchTag = false;

        if(count($urlArr)==2&&$urlArr[0]==self::getControllerName()&&$urlArr[1]==self::getActionName())
            $matchTag = true;

        if(count($urlArr)==1&&$urlArr[0]==self::getActionName())
            $matchTag = true;

        if($matchTag)
        {
            try {
                //匹配路由
                $router = $this->get('router')->match($this->get('request')->getPathInfo());

                return $this->get('router')->generate($router['_route'], self::handleVars($vars), $domain);

            }catch (\Exception $e) {
                $matchTag = false;
            }
        }

        if(!$matchTag)
        {
            $routeName = str_replace("/", "_", $url);

            $match = $this->get('router')->getRouteCollection()->get($routeName);

            if($match)
            {
                $options = $match->getOptions();
                if(!isset($options['status'])||$options['status']==true)
                {
                    try {
                        //匹配路由
                        $router = $this->get('router')->match($match->getPath());
                        return $this->get('router')->generate($router['_route'], self::handleVars($vars), $domain);             
                    }catch (\Exception $e) {
    
                    }
                }
            }
        }

        //读取默认bundle
        $defaultPrefix = self::C('_defaultprefix');

        $bundle = self::getBundleName();

        //bundle集
        $bundles = self::getBundles();

        if(!isset($bundles[$bundle]))
            $bundle = $defaultPrefix?$defaultPrefix:self::getBundleName(true);
        
        if(!isset($bundles[$bundle]))
            return '/';

        $bundleArr = array();

        foreach(array_keys($bundles) as $key)
        {
            $bundleArr[strtolower(str_replace("Bundle","",$key))] = "";
        }

        $item = explode('\\',$bundles[$bundle]);

        //去掉最后一个数组
        array_pop($item);

        $_bundle = end($item);

        // 解析URL
        $info = parse_url($url);

        if(!empty($info['path']))
        {
            $info['path'] = str_replace($baseUrl,"",$info['path']);
            $pathArr = explode("/",$info['path']);

            $action = strtolower($pathArr?array_pop($pathArr):self::getActionName());
            $controller = $pathArr?strtolower(array_pop($pathArr)):self::getControllerName();
            $bundle = $pathArr?strtolower(array_pop($pathArr)):strtolower(str_replace("Bundle","",$_bundle));

            if(!$dataType)
            {
                $controller = $controller!="index"?$controller:"";
                $action = $action!="index"?$action:"";
            }

            $bundle = isset($bundleArr[$bundle])?$bundle:strtolower(str_replace("Bundle","",$_bundle));

            $urlArr = array();

            $urlArr[] = $baseUrl;

            if($bundle==$defaultPrefix)
                $bundle = "";

            if($info['path']!="/")
            {
                if($bundle)
                    $urlArr[] = $bundle;

                if($controller)
                    $urlArr[] = $controller;

                if($action)
                    $urlArr[] = $action;

                $url = implode('/', $urlArr);
            }
        }

        //处理URL参数
        $url = self::setUrlParam($url, $vars, $info);

        $url = $domain?self::ensureUrlIsAbsolute($url):$url;

        unset($vars);
        unset($info);
        unset($urlArr);
        unset($pathArr);
        unset($_bundle);
        unset($bundleArr);
        unset($routeName);
        unset($item);
        unset($bundle);
        unset($bundles);
        unset($controller);
        unset($action);
        unset($baseUrl);

        return $url?$url:'/';
    }

    /**
     * 处理URL参数
     * @param string $url
     * @param string $vars
     * @param string $info
     */
    public function setUrlParam($url, $vars, $info)
    {
        return $this->get('core.common.base')->handleUrlParam($url, $vars, $info);
    }

    public function ensureUrlIsAbsolute($url, array $info=array())
    {
        $request = $this->get('request');

        if (false !== strpos($url, '://') || 0 === strpos($url, '//'))
            return $url;

        $host = isset($info['host'])?$info['host']:$request->server->get('HTTP_HOST');

        if ('' === $host)
            return $url;

        $scheme = isset($info['scheme'])?$info['scheme']:$this->get('router.request_context')->getScheme();

        if ('#' === $url)
            return $scheme.'://'.$host.$request->server->get('REQUEST_URI').'#';
        
        return $scheme.'://'.$host.$url;
    }

    /*
     *功能：php多种方式完美实现下载远程图片保存到本地
     *参数：文件url,保存文件名称，使用的下载方式
     *当保存文件名称为空时则使用远程文件原来的名称
     */
    public function downImage($url, $filename='', $type=false)
    {
         if($url=='')
            return false;

        //创建文件名
        if($filename=='')
        {
            $info = parse_url($url);
            $_path = explode("/",$info['path']);

            $filename = end($_path);
        }

        //文件保存路径
        if($type){
            $ch = curl_init();
            $timeout = 5;
            curl_setopt($ch,CURLOPT_URL,$url);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
            $img=curl_exec($ch);
            curl_close($ch);
        }else{
            ob_start();
            readfile($url);
            $img = ob_get_contents();
            ob_end_clean();
        }

        //文件大小
        $fp2 = @fopen($filename,'a');

        fwrite($fp2,$img);
        fclose($fp2);
        return $filename;
    }

    public function getContents($url, $recu = 0, $limit=0, $post='', $cookie='', $timeout=15)
    {
        if (empty($url))
            throw new \InvalidArgumentException('空的url请求' . $recu);

        $return = '';
        $matches = parse_url($url);
        $scheme = $matches['scheme'];
        $host = $matches['host'];
        $path = $matches['path'] ? $matches['path'].(@$matches['query'] ? '?'.$matches['query'] : '') : '/';
        $port = !empty($matches['port']) ? $matches['port'] : 80;

        if (function_exists('curl_init') && function_exists('curl_exec'))
        {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $scheme.'://'.$host.':'.$port.$path);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

            if ($post)
            {
                curl_setopt($ch, CURLOPT_POST, 1);
                $content = is_array($post) ? http_build_query($post) : $post;
                curl_setopt($ch, CURLOPT_POSTFIELDS, urldecode($content));
            }

            if ($cookie)
                curl_setopt($ch, CURLOPT_COOKIE, $cookie);

            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_TIMEOUT, 900);
            $data = curl_exec($ch);
            $status = curl_getinfo($ch);
            $errno = curl_errno($ch);
            curl_close($ch);

            if ($errno || $status['http_code'] != 200)
                return;
            else
                return !$limit ? $data : substr($data, 0, $limit);
        }

        if ($post)
        {
            $content = is_array($post) ? urldecode(http_build_query($post)) : $post;
            $out = "POST $path HTTP/1.0\r\n";
            $header = "Accept: */*\r\n";
            $header .= "Accept-Language: zh-cn\r\n";
            $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
            $header .= "User-Agent: ".@$_SERVER['HTTP_USER_AGENT']."\r\n";
            $header .= "Host: $host:$port\r\n";
            $header .= 'Content-Length: '.strlen($content)."\r\n";
            $header .= "Connection: Close\r\n";
            $header .= "Cache-Control: no-cache\r\n";
            $header .= "Cookie: $cookie\r\n\r\n";
            $out .= $header.$content;
        } else {
            $out = "GET $path HTTP/1.0\r\n";
            $header = "Accept: */*\r\n";
            $header .= "Accept-Language: zh-cn\r\n";
            $header .= "User-Agent: ".@$_SERVER['HTTP_USER_AGENT']."\r\n";
            $header .= "Host: $host:$port\r\n";
            $header .= "Connection: Close\r\n";
            $header .= "Cookie: $cookie\r\n\r\n";
            $out .= $header;
        }

        $fpflag = 0;
        $errstr = "";

        $fp = false;

        if (function_exists('stream_socket_client'))
            $fp = @stream_socket_client($host.":".$port, $errno, $errstr, $timeout,STREAM_CLIENT_ASYNC_CONNECT|STREAM_CLIENT_CONNECT);

        if (!$fp)
        {
            $context = stream_context_create(array(
                'http' => array(
                    'method' => $post ? 'POST' : 'GET',
                    'header' => $header,
                    'content' => $content,
                    'timeout' => $timeout,
                ),
            ));
            $fp = @fopen($scheme.'://'.$host.':'.$port.$path, 'b', false, $context);
            $fpflag = 1;
        }

        if (!$fp)
            return '';

        stream_set_blocking($fp, true);
        stream_set_timeout($fp, $timeout);
        @fwrite($fp, $out);
        $status = stream_get_meta_data($fp);
        if (!$status['timed_out'])
        {
            while (!feof($fp) && !$fpflag)
            {
                if (($header = @fgets($fp)) && ($header == "\r\n" ||  $header == "\n"))
                    break;
            }
            if ($limit)
                $return = stream_get_contents($fp, $limit);
            else
                $return = stream_get_contents($fp);
        }
        @fclose($fp);
        return $return;
    }

    /**
     * 全局缓存设置和读取
     * @param string $name 缓存名称
     * @param mixed $value 缓存值
     * @param integer $expire 缓存有效期（秒）
     * @param string $type 缓存类型
     * @param array $options 缓存参数
     * @return mixed
     */
    public function S($name='', $value='', $expire=null, $type='',$options=null)
    {
        //if(strtolower($this->getParameter('_cachetype')) == 'memcache')
        $cache = $this->get('core.memcache');
        if($name==='') {
            $cache->deleteAll();
        }
        $name .= $type ? '_'.$type : '';
        if ('' !== $value) {
            if (is_null($value)) {
                $result = $cache->delete($name);// 删除缓存
                return $result;
            }else {
                $result = $cache->save($name, $value, $expire);// 保存数据
            }
            return $result;
        }

        $value = $cache->fetch($name);
        return $value;
    }

    /**
     * 清除缓存
     */
    public function cleanCache()
    {
        $file_dir = $this->getParameter('kernel.root_dir').DIRECTORY_SEPARATOR;
        $file_dir .= "cache".DIRECTORY_SEPARATOR."prod";
        self::delDir($file_dir);
        self::S();
    }

    /**
     * 数组分页函数  核心函数  array_slice
     * 用此函数之前要先将数据库里面的所有数据按一定的顺序查询出来存入数组中
     * $pageIndex   当前第几页
     * $pageSize   每页多少条数据
     * $array   查询出来的所有数组
     * @return array 返回查询数据
     */
    public function pagination($pageIndex, $pageSize, $array)
    {
        return $this->get('core.common.base')->pagination($pageIndex, $pageSize, $array);
    }

    /**
     * 获得参数值
     * @param string $name  参数名称
     */
    public function getParameter($name)
    {
        return $this->container->getParameter($name);
    }

	//清理html文本中的样式、js等
	public function htmlClear($str)
	{
		$str = preg_replace("/<sty.*?\\/style>|<scr.*?\\/script>|<!--.*?-->/is", '', $str);
		$str = preg_replace("/<\\/?(?:p|div|dt|dd|li)\\b.*?>/is", '<br>', $str);
		$str = preg_replace("/\s+/", '', $str);
		$str = preg_replace("/<br\s*\\/?>/is", "\r\n", $str);
		$str = strip_tags($str);

		return str_replace(
			array('&lt;', '&gt;', '&nbsp;', '&quot;', '&ldquo;', '&rdquo;', '&amp;'),
			array('<','>', ' ', '"', '"', '"', '&'),
			$str
		);
	}

	public function mhtmlSpecialchars($string, $quotes = ENT_QUOTES, $delete_rep = false)
	{
		if(is_array($string)) {
			foreach($string as $key => $val)
			    $string[$key] = self::mhtmlspecialchars($val, $quotes, $delete_rep);
		} else { // 2:ENT_COMPAT:默认,仅编码双引号; 3:ENT_QUOTES:编码双引号和单引号; 0:ENT_NOQUOTES:不编码任何引号;
			if ( $delete_rep )
			{
				$string = str_replace(array(' ', '%20', '%27', '*', '\'', '"', '/', ';', '#', '--'), '', $string);
			}
			$string = htmlspecialchars($string, $quotes);
		}
		return $string;
	}

	/**
	 * 开发模式下异常处理
	 * @param \Exception $e
	 * @throws \LogicException
	 */
	public function logicException(\Exception $e)
	{
	    if(self::getParameter('kernel.environment')=='dev')
	    {
	        self::ajaxReturn(array('msg'=>$e->getMessage()));
	        throw new \LogicException($e->getMessage());
	    }
	}

    /**
     * 安全字串（也可用于XSS）
     *
     * @param  string $value 要过滤的值
     * @return string        返回已经过滤过的值
     *
     * @since  1.0
     */
    public function safeStr($value)
    {
       $search = 'abcdefghijklmnopqrstuvwxyz';
       $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
       $search .= '1234567890!@#$%^&*()';
       $search .= '~`";:?+/={}[]-_|\'\\';

       for ($i = 0; $i < strlen($search); $i++)
       {
            // @ @ search for the hex values
            $value = preg_replace('/(&#[xX]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $value);

            // @ @ 0{0,7} matches '0' zero to seven times
            $value = preg_replace('/(&#0{0,8}'.ord($search[$i]).';?)/', $search[$i], $value);
       }

       // now the only remaining whitespace attacks are \t, \n, and \r
       #$ra1 = array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style',
       #             'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'base');
       $ra1 = array('<javascript', '<vbscript', '<expression', '<applet', '<script', '<object', '<iframe',
	                '<frame', '<frameset', '<ilayer', '<bgsound', '<base');

       $ra2 = array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut',
           'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate',
           'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut',
           'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend',
           'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange',
           'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete',
           'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover',
           'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange',
           'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted',
           'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');

       $ra = array_merge($ra1, $ra2);

       $found = true; // keep replacing as long as the previous round replaced something
       while ($found == true)
       {
            $val_before = $value;
            for ($i = 0; $i < sizeof($ra); $i++)
            {
                $pattern = '/';
                for ($j = 0; $j < strlen($ra[$i]); $j++)
                {
                    if ($j > 0)
                    {
                        $pattern .= '(';
                        $pattern .= '(&#[xX]0{0,8}([9ab]);)';
                        $pattern .= '|';
                        $pattern .= '|(&#0{0,8}([9|10|13]);)';
                        $pattern .= ')*';
                    }
                    $pattern .= $ra[$i][$j];
                }

                $pattern .= '/i';

                $replacement = substr($ra[$i], 0, 2).'<!--08CMS-->'.substr($ra[$i], 2); // add in <> to nerf the tag
                $value = preg_replace($pattern, $replacement, $value); // filter out the hex tags

                // no replacements were made, so exit the loop
                if ($val_before == $value)                    
                    $found = false;
            }
       }

       return $value;
    }

    /**
     * 反编码经过 htmlspecialchars 函数编码过的字符串
     *
     * @param  mixed  $values   已经编码过的字符串或数组
     * @param  array  $varnames 要反编码的变量名称与值，如果未指定则反编码所有
     * @since  nv50
     */
    public function deRepGlobalValue($values, array $varnames = array(), $quotes = ENT_QUOTES )
    {
        if ( is_array($values) )
        {
            foreach($values as &$value )
            {
                $value = self::deRepGlobalValue($value, $varnames, $quotes);
            }
        }else{
            $values = (string) $values;
            if ( !empty($varnames) )
            {
                foreach ( $varnames as $value )
                {
                    if(empty($value))
                        continue;
                    
                    if ( false !== strpos($values, $value) )
                        $values = str_replace($value, htmlspecialchars_decode($value, $quotes), $values);
                }
            }else{
                $values = htmlspecialchars_decode($values, $quotes);
            }
        }

        return $values;
    }

    public function makeoption($arr, $key='', $default='')
    {
        $str = $default ? "<option value=\"\">$default</option>\n" : '';
        if(is_array($arr))
        {
            foreach($arr as $k => $v)
            {
                $str .= "<option value=\"$k\"";

                if( (is_array($key) && in_array($k, $key)) || ($k == $key && empty($k) == empty($key)) )
                    $str .= ' selected="selected"';

                $str .= ">$v</option>\n";
            }
        }
        return $str;
    }

    /**
     * 加密
     * @param string $str
     * @return mixed
     */
    public function base_encode($str)
    {
        $src  = array("/","+","=");
        $dist = array("_a","_b","_c");
        $old  = base64_encode($str);
        return str_replace($src,$dist,$old);
    }

    /**
     * 解密
     * @param string $str
     * @return string
     */
    public function base_decode($str)
    {
        $src = array("_a","_b","_c");
        $dist  = array("/","+","=");
        $old  = str_replace($src,$dist,$str);
        return base64_decode($old);
    }

    /**
     * 追加到文件
     * @param string $file
     * @param string $data
     */
    public function saveFile($file, $data)
    {
        file_put_contents($file, date('Y-m-d H:i:s').":".(is_array($data)?json_encode($data):$data)."\r\n", FILE_APPEND);
    }
    
    /**
     * 对URL进行{@see self::encodeUrl}编码过的URL解码  ??$onlyEncodeChinese待查??
     *
     * @param  mixed $urls 要解码的URL
     * @return mixed       返回已经解码的URL
     * @since  nv50
     */
    public function decodeUrl( $urls )
    {
        return $this->get('core.array')->map('rawurldecode', $urls);
    }    

    /**
     * 对URL进行urlencode编码（让支持数组）
     *
     * @param  mixed $urls              要编码的URL
     * @param  bool  $onlyEncodeChinese 只编码中文
     * @return mixed                    返回已经编码的URL
     *
     * @since  nv50
     */
    public function encodeUrl( $urls, $onlyEncodeChinese = true )
    {
        if (!$onlyEncodeChinese) {
            // 此处的 map 支持嵌套数组吗??待查
            return $this->container->get('core.array')->map('rawurlencode', $urls);
        }
        
        if (is_array($urls)) {
            foreach ($urls as &$url) {
                if (is_array($url))
                    $url = self::encodeUrl($url);
                else
                    $url = self::getEncodeChinese($url);
            }
        }else {
            $urls = self::getEncodeChinese((string) $urls);
        }

        return $urls;
    }

    /**
     * 获取只编码中文的字符串（目前只支持UTF-8编码的字符串）
     *
     * @param  string $string 要编码的字符串
     * @return string         返回只有中文经过了编码后的字符串
     **/
    public function getEncodeChinese($string)
    {
        $chinse = array();
        if (preg_match_all('/[\x7f-\xff]+/', (string) $string, $chinse))
        {
            $array = array();
            foreach ($chinse[0] as &$_chinse)
            {
                $array[] = urlencode($_chinse);
            }

            $string = str_replace($chinse[0], $array, $string);
        }

        return $string;
    }

	/**
     * 生成一个唯一订单号
     *
     * @param  mixed $type 要生成的订单复制程度，0为基本型
     * @return mixed       返回已订单号
     * @since  nv50
     */
    public function makeOrderId($type = 0)
    {
        if(empty($type))
            return date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);

        $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
        $orderSn = $yCode[intval(date('Y')) - 2011] . strtoupper(dechex(date('m'))) . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));
        return $orderSn;
    }

    /**
     * 对坐标进行geohash编码
     *
     * @param  float $latitude   纬度
     * @param  float $longitude  经度
     * @param  int   $deep       编码深度
     */
    public function encode_geohash($latitude, $longitude, $deep = 12)
    {
        return $this->get('core.common.base')->encode_geohash($latitude, $longitude, $deep);
    }

    /**
     * 对坐标进行geohash解码
     *
     * @param  string $geohash   geohash编码的字符串
     */
    public function decode_geohash($geohash)
    {
        return $this->get('core.common.base')->decode_geohash($geohash);
    }

    /**
     * 对两个坐标求距离
     *
     * @param  float $lat1,$lat2   纬度
     * @param  float $lng1,$lng2   经度
     */
    public function getDistance($lat1, $lng1, $lat2, $lng2)
    {
        return $this->get('core.common.base')->getDistance($lat1, $lng1, $lat2, $lng2);
    }

    public function getThemeName()
    {
        $user = self::getUser();

        $bundle = self::getBundleName();

        if(method_exists($user, 'getUsertplid')&&$bundle!="ManageBundle")
            $bundle = $user->getUsertplid()?$user->getUsertplid():$bundle;

        //判断手机浏览器
        if(self::isMobileClient())
        {
            //去掉bundle后缀
            if (preg_match('/Bundle$/', $bundle))
                $bundle = substr($bundle, 0, -6)."mobileBundle";
        }else{
            if($this->container->has('db.auth_theme'))
            {
                $map = array();
                $map['bundle'] = self::getBundleName();
                $map['controller'] = self::getControllerName();
                $map['action'] = self::getActionName();
                $count = $this->get('db.auth_theme')->getData($map);
                if($count)
                    $bundle = $this->getBundleName();
            }
        }

        return $bundle;
    }

    public function handleEventListen(array $eventListen, $obj, $modelName)
    {
        if(!$eventListen) {
            return true;
        }

        $user = self::getUser();

        foreach( $eventListen as $event)
        {
            try {
                $data = array();

                $op = is_object($event)?$event->getOp():$event['op'];

                $modelName = is_object($event)?$event->getModelName():$event['model_name'];

                $modelPath = is_object($event)?$event->getModelPath():$event['model_path'];

                $bindPath = is_object($event)?$event->getBindPath():$event['bind_path'];

                $field = is_object($event)?$event->getField():$event['field'];

                //映射类型
                $mapType = is_object($event)?$event->getMapType():$event['map_type'];

                //映射字段
                $mapParam = is_object($event)?$event->getMapParam():$event['map_param'];

                //查询条件
                $condtion = self::getQueryParam(is_object($event)?$event->getCondtion():$event['condtion'],$obj);

                //映射条件
                $mapWhere = self::getQueryParam(is_object($event)?$event->getMapWhere():$event['map_where'],$obj);

                //禁用汇总统计
                $condtion['_multi'] = isset($condtion['_multi'])?$condtion['_multi']:false;

                switch($mapType)
                {
                    //更新数据
                    case 0:
                        if($modelPath&&$bindPath&&$mapParam)
                        {                                
                            if(!empty($mapWhere['id'])) {
                                $mapWhere = array('id' => (int)$mapWhere['id']);
                            }
                            $this->get($bindPath)->dbalUpdate(self::getQueryParam($mapParam,$obj), $mapWhere);
                        }
                        break;
                    //统计
                    case 1:
                        if($modelPath&&$bindPath&&$mapParam)
                        {
                            //统计数据
                            $data[$mapParam] = $this->get($modelPath)->count($condtion);
                            if(!empty($mapWhere['id'])) {
                                $mapWhere = array('id' => (int)$mapWhere['id']);
                            }
                            $this->get($bindPath)->dbalUpdate($data, $mapWhere);
                        }

                        break;

                    //点击量
                    case 2:
                        $field = self::ucWords($mapParam);

                        if($modelPath && $bindPath && $mapParam)
                        {
                            if($modelPath == $bindPath)
                            {
                                if(is_array($obj))
                                {
                                    if(isset($obj[$mapParam]) && isset($obj['id'])) {
                                        $data[$mapParam] = (int)$obj[$mapParam] + 1;
                                        $this->get($modelPath)->dbalUpdate($data, array('id' => (int)$obj['id']));
                                    }
                                }else{
                                    if(method_exists($obj, "get".$field))
                                    {
                                        $data[$mapParam] = (int)$obj->{"get".$field}() + 1;
                                        $this->get($modelPath)->dbalUpdate($data, array('id' => (int)$obj->getId()));
                                    }
                                }
                            }else{
                                $sobj = $this->get($bindPath)->findOneBy($mapWhere, array(), false);
                                if(method_exists($sobj, "get".$field))
                                {
                                    $data[$mapParam] = (int)$sobj->{"get".$field}() + 1;
                                    $this->get($bindPath)->dbalUpdate($data, array('id' => (int)$sobj->getId())); 
                                }
                                unset($sobj);
                            }
                        }
                        break;
                    //日志
                    case 3:
                        if($modelPath&&$bindPath)
                        {
                            //更新数据
                            $addData = array();
                            $addData['type'] = 'log';
                            switch ($op)
                            {
                                case 'select':
                                    $addData['name'] = '查询';
                                    break;
                                case 'update':
                                    $addData['name'] = '更新';
                                    break;
                                case 'insert':
                                    $addData['name'] = '添加';
                                    break;
                                case 'delete':
                                    $addData['name'] = '删除';
                                    break;
                                default:
                                    $addData['name'] = $op;
                                    break;
                            }
                            $addData['value'] = json_encode($this->get('serializer')->normalize($obj));
                            $addData['models'] = $modelName;
                            $addData['title'] = $this->get('request')->getClientIp();
                            $addData['operation'] = is_object($user) ? $user->getUsername() : '游客';
                            $addData['uid'] = is_object($user) ? $user->getId() : '游客';
                            
                            $this->get($bindPath)->dbalAdd($addData);
                        }
                        break;

                    //极值
                    case 4:
                        if($modelPath&&$bindPath&&$mapParam)
                        {
                            //统计数据
                            $result = $this->get($modelPath)->findBy($condtion);

                            if(!isset($result['data'][0][$field]))
                                continue;

                            $data = array();
                            $data[$mapParam] = isset($result['data'][0][$field])?$result['data'][0][$field]:0;
                            
                            if(!empty($mapWhere['id'])) {
                                $mapWhere = array('id' => (int)$mapWhere['id']);
                            }

                            $this->get($bindPath)->dbalUpdate($data, $mapWhere);
                        }
                        break;
                    //佣金统计
                    case 5:
                        if($modelPath&&$bindPath&&$mapParam)
                        {
                            if ($condtion['status'] == 5)
                            {
                                $data = array();
                                $userinfo = $this->get($bindPath)->findOneBy($condtion);
                                $data['unnum'] = $userinfo->getUnnum() + 1; 
                                $mapWhere = array('id' => $userinfo->getId());
                                
                                $this->get($bindPath)->dbalUpdate($data, $mapWhere);
                            } elseif ($condtion['status'] == 4) {//佣金统计

                                //获取佣金数
                                $result = $this->get($modelPath)->findOneBy(array('id'=>$mapWhere['id']));
                                $data[$mapParam] = $result->getYongjin();

                                if (empty($data))
                                    return;

                                //更新数据
                                $info = $this->get($bindPath)->findOneBy(array('uid'=>$mapWhere['uid']), array(), false);

                                // 判断是否有分销上级
                                if ($info->getFxpid())
                                {
                                    // 获取提成比例 $valid_period
                                    $sellrateInfo = $this->get('db.mconfig')->findOneBy(array('name'=>'sellrate'), array(), false);
                                    $valid_period = $sellrateInfo->getValue() / 100;
                                    $info1 = $this->get($bindPath)->findOneBy(array('uid'=>(int)$info->getFxpid()), array(), false);

                                    $data1 = array();
                                    $data1[$mapParam] = ($data[$mapParam] * $valid_period) + $info1->getYongjin();
                                    $this->get($bindPath)->dbalUpdate($data1, array('id' => $info1->getId()));
                                }

                                $data[$mapParam] = $data[$mapParam] + $info->getYongjin();
                                $this->get($bindPath)->dbalUpdate($data, array('id' => $info->getId()));
                            }
                        }
                        break;
                    //联动
                    case 6:
                        //触发字段
                        $field = self::ucWords($field);

                        //映射字段
                        if (strstr($mapParam,'='))
                            $mapParam = self::getQueryParam($mapParam,$obj);

                        if($modelPath&&$bindPath&&$mapParam)
                        {
                            //获取数据
                            if ($op=='delete' && isset($condtion['id']))
                                $result = $this->get($modelPath)->getTrashById($condtion['id']);
                            else
                                $result = $this->get($modelPath)->findOneBy($condtion);

                            if ($result)
                            {
                                $data = array();

                                if (!is_array($mapParam)&&$field == '')
                                    $data[$mapParam] = 0;
                                elseif (method_exists($result, "get".$field))
                                    $data[$mapParam] = $result->{"get".$field}();
                                else
                                    $data = $mapParam;

                                //修改数据
                                if (!empty($mapWhere))
                                {
                                    //if(isset($mapWhere['id']) && (int)$mapWhere['id']>0 && count($mapWhere)==1)
                                    //原代码中有这个，所以暂时不强制只要有id就只按id来查询的处理了。
                                    $this->get($bindPath)->dbalUpdate($data, $mapWhere);
                                } else {
                                    //新增数据
                                    $this->get($bindPath)->dbalAdd($data);

                                }
                            }
                        }
                        break;
                    //佣金提现
                    case 7:
                        if($modelPath&&$bindPath&&$mapParam)
                        {
                            $method ='get'. ucfirst($field);

                            $data = array();
                            $data[$mapParam] = method_exists($obj, $method)?$obj->{$method}():0;

                            if(empty($data))
                                return;

                            //更新数据
                            if(isset($mapWhere['uid'])&&(int)$mapWhere['uid']>0){
                                
                                $info = $this->get($bindPath)->createOneIfNone(array('uid'=>(int)$mapWhere['uid']));

                                switch ($op)
                                {
                                    case 'update':
                                        // 当支付状态为无效(paystatus=2)时，对应用户佣金还原
                                        if ($obj->getPaystatus() != 2)
                                            return ;

                                        $data[$mapParam]=$data[$mapParam]+$info->getYongjin();

                                        break;
                                    case 'insert':
                                        // 减去对应用户佣金数
                                        $data[$mapParam]=$info->getYongjin()-$data[$mapParam];
                                        break;
                                }
                                $this->get($bindPath)->dbalUpdate($data, array('id' => $info->getId()));
                            }
                        }
                        break;
                    //周边关联
                    case 8:
                        //映射字段
                        if (strstr($mapParam,'='))
                            $mapParam = self::getQueryParam($mapParam,$obj);

                        if($modelPath&&$bindPath&&$mapParam)
                        {
                            //获取数据
                            $result = $this->get($modelPath)->findOneBy($condtion);

                            $data = is_array($mapParam)?$mapParam:array();

                            if (is_object($result)&&method_exists($result, "get".$field))
                                $data[$mapParam] = $result->{"get".$field}();

                            if(empty($data))
                                return;

                            //获得地图坐标范围
                            $map = $result->getMap();
                            $maps = explode(',',$map);

                            if (count($maps)<3)
                                return ;

                            $around = $this->get('core.square')->returnSquarePoint($maps[0],$maps[1],10);

                            $wherefind = array();
                            $wherefind['_multi'] = false;
                            $wherefind['lat']['between']=array($around['right-bottom']['lat'],$around['left-top']['lat']);
                            $wherefind['lng']['between']=array($around['left-top']['lng'],$around['right-bottom']['lng']);

                            switch($field)
                            {
                                case 'aid':
                                    $resAll = $this->get('house.houses')->findBy($wherefind);
                                    break;
                                case 'formid':
                                    $resAll = $this->get('house.around')->findBy($wherefind);
                                    break;
                            }

                            //新增数据
                            if(isset($resAll['data'])&&$resAll['data']) {
                                foreach($resAll['data'] as $val) {
                                    $data[$field] = $val->getId();
                                    $this->get($bindPath)->dbalAdd($data);
                                }
                            }
                        }
                        break;
                }
            } catch (\Exception $e) {
                self::logicException($e);
            }
        }
    }
    
    /**
     * 根据字段类型格式化字段值
     * @param string $value
     * @param string $type
     */    
    public function normalizeFieldValue($value = '',$type = 'string')
    {

        $value = is_array($value) ? implode(',', $value) : $value;
        switch($type)
        {
            case 'integer':
            case 'boolean':
                $value = (int)$value;
                break;
            case 'float':
                $value = floatval($value);
                break;
            case 'date':
            case 'time':
            case 'datetime':
                if(!$value instanceof \DateTime) {
                    $value = new \DateTime($value ? trim($value) : "");
                }
                break;
            default :
                $value = trim($value);
                break;
        }
        return $value;
    }

    /**
     * 获取表单
     * @param object $modelForm
     * @param array  $InitInfo
     * @param bool   $preview
     * @param int    $copy
     * @param int    $flag
     */
    public function getForm($modelForm, array &$initInfo, $preview=false, $copy=0, $flag=0, $muli=false)
    {
        $info = array();

        $map = $map1 = array();

        if($modelForm)
        {
            //绑定字段
            $bindfield = empty($modelForm['bindfield'])?"mid":$modelForm['bindfield'];

            //初始化参数
            $initcondition = $modelForm['initcondition']?self::getQueryArray($modelForm['initcondition']):array();

            $models = (isset($initcondition['id'])&&end($initcondition['id'])==0)?array():$this->get('db.models')->getData((int)$modelForm['model_id']);

            if($models)
            {
                //服务名
                $serviceName = isset($models['service'])?$models['service']:'';

                $map['id'] = method_exists(self::getUser(), 'getId')?self::getUser()->getId():0;

                if($serviceName)
                {
                    if($flag==0)
                    {
                        $map['id'] = (int)$this->get('request')->get('_id', 0);

                        $map['initid'] = (int)$this->get('request')->get('initid', 0);
                    }

                    if(isset($initcondition['data-groupfield']))
                    {
                        //卸载初始化字段
                        unset($initcondition['data-groupfield']);

                        foreach(array_keys($initcondition) as $kinit)
                        {
                            if (preg_match('/data-formgroup/', $kinit))
                                unset($initcondition[$kinit]);
                        }
                    }

                    //判断是否有默认值
                    if($map['id']<=0 && $initcondition)
                    {
                        $map = $initcondition;
                        if($modelForm['initmodel'])
                            $serviceName = $modelForm['initmodel'];
                    }

                    if(isset($map['initid']) && $map['initid'] && $modelForm['initmodel']){
                        $serviceName = $modelForm['initmodel'];
                        $map['id'] = $map['initid'];
                        $map['_copy'] = 1;
                    }

                    if(isset($map['initid'])){
                        unset($map['initid']);
                    }

                    if($copy>0)
                        $map['_copy'] = 1;

                    if($bindfield&&$this->get('request')->get($bindfield,''))
                    {
                        $map = array();
                        $map[$bindfield] = $this->get('request')->get($bindfield,'');

                        $map1['id'] = 1;
                    }

                    $map['area'] = $this->get('core.area')->getArea();

                    //0横表，1纵表
                    switch((int)$modelForm['type'])
                    {
                        case 1:
                            $result = $this->get($serviceName)->findOneByVertical($map);
                            foreach($result as $k=>$v)
                            {
                                $info[$k] = $v['value'];
                            }
                            break;
                        default:
                            $map['findType'] = true;
                            $info = $this->get($serviceName)->findOneBy($map, array(), true);
                            break;
                    }
                }
            }

            //表单组装
            $form = self::getFormFieldAttr($modelForm['name'], array_merge($map, $map1), 'POST', $info, 'save', '', $preview);

            //生成表单对象
            $formView = $form->createView();

            //表单分组处理
            $children = $formView->children;

            foreach($children as $k=>&$item)
            {
            	//目前用于判断是否开启短信验证码，邮箱验证码
                $vars = $item->vars;
                if (isset($vars['errors']))
                {
                    $type = $vars['errors']->getForm()->getConfig()->getOption('type');
                    $checkTypes = array('telcode', 'mailcode');
                    
                    if (in_array($type, $checkTypes))
                    {
                        if (true==$vars['errors']->getForm()->getConfig()->getOption('isClose') //判断接口是否关闭
                            || !isset($vars['attr']['data-codetpl']) 
                            || !self::checkTplIsOpen($vars['attr']['data-codetpl'],$type) //判断模版是否关闭
                            )
                        {
                            unset($children[$k]);
                            unset($formView->children[$k]);
                            continue;
                        }
                    }
                }
                
                if(!isset($item->vars['compound'])||!$item->vars['compound'])
                    continue;

                if(!isset($item->vars['attr']['data-compound']))
                    continue;

                $mresult = explode(',', $item->vars['attr']['data-compound']);
                unset($item->vars['attr']['data-compound']);

                foreach($mresult as $mr)
                {
                    if(!isset($children[$mr]))
                        continue;

                    if(empty($children[$mr]->vars['label']))
                    {
                        $children[$mr]->vars['label'] = " ";
                        $children[$mr]->vars['label_attr'] = array('class'=>"");
                    }

                    $item->children[$mr] = $children[$mr];

                    unset($children[$mr]);
                    unset($formView->children[$mr]);
                }
            }

            if($muli)
                $initInfo['form'][$modelForm['name']] = $formView;
            else
                $initInfo['form'] = $formView;

        }

        return $initInfo;
    }

    public function getFormFieldAttr($name, array $map, $method, $info=null, $action='save', $prefix='', $preview=false)
    {
        return $this->get('core.form_bind')->getForm($name, $map, $method, $info, $action, $prefix, $preview);
    }

    /**
     * 固位排序功能
     * @param array $array  原始数据(字段分析用)
     * @param string $keys	要排序的字段
     * @return array
     */
    public function array_sortfixed(array $array, $keys)
    {
        $keysvalue = array();
        foreach($array as $key=>$vv)
        {
            if($vv[$keys]>0)
            {
                $keysvalue[] = $vv;
                unset($array[$key]);
            }
        }
        $keysvalue = self::array_sort($keysvalue, $keys, null, 'asc');

        if(count($keysvalue)>0)
        {
            foreach($keysvalue as $key=>$vv)
            {
                $start = $vv[$keys]-1;

                if(count($array)>$start)
                    array_splice($array, $start, 0, array($vv));
                else
                    $array[] = $vv;

                reset($array);
            }
        }

        return $array;
    }


    /**
     * 获取上n周的开始和结束，每周从周一开始，周日结束日期
     * @param int $ts 时间戳
     * @param int $n 你懂的(前多少周)
     * @param string $format 默认为'%Y-%m-%d',比如"2016-10-01"
     * @return array 第一个元素为开始日期，第二个元素为结束日期
     */
    public function lastNWeek($ts, $n, $format = '%Y-%m-%d')
    {
        $ts = intval($ts);
        $n  = abs(intval($n));

        // 周一到周日分别为1-7
        $dayOfWeek = date('w', $ts);

        if (0 == $dayOfWeek)
            $dayOfWeek = 7;

        $lastNMonday = 7 * $n + $dayOfWeek - 1;
        $lastNSunday = 7 * ($n - 1) + $dayOfWeek;

        return array(
            strftime($format, strtotime("-{$lastNMonday} day", $ts)),
            strftime($format, strtotime("-{$lastNSunday} day", $ts))
        );
    }

    /**
     * 数组排序
     * @param array $array
     * @param string $keys
     * @param string $keys1
     * @param string $type
     * @return multitype:|Ambigous <multitype:, unknown>
     */
    public function array_sort(array $array, $keys, $keys1=null, $type='asc')
    {
        $type = $type?$type:'asc';
        if(!isset($array) || !is_array($array) || empty($array))
            return array();

        if(!isset($keys) || trim($keys)=='')
            return array();

        if(!isset($type) || $type=='' || !in_array(strtolower($type),array('asc','desc')))
            return array();

        $keysvalue = array();
        $keysvalue1 = array();
        foreach($array as $key=>$val)
        {
            if($keys1&&isset($val[$keys1])&&$val[$keys1]>0)
            {
                $val[$keys1] = str_replace('-','',$val[$keys1]);
                $val[$keys1] = str_replace(' ','',$val[$keys1]);
                $val[$keys1] = str_replace(':','',$val[$keys1]);
                $keysvalue1[$key] = $val[$keys1];
            }else{
                $val[$keys] = str_replace('-','',$val[$keys]);
                $val[$keys] = str_replace(' ','',$val[$keys]);
                $val[$keys] = str_replace(':','',$val[$keys]);
                $keysvalue[$key] = $val[$keys];
            }
        }

        //key值排序
        asort($keysvalue);

        //指针重新指向数组第一个
        reset($keysvalue);

        //key值排序
        asort($keysvalue1);

        //指针重新指向数组第一个
        reset($keysvalue1);

        $keysort = array();
        $keysort1 = array();

        foreach($keysvalue as $key=>$vals)
        {
            unset($vals);
            $keysort[] = $key;
        }

        foreach($keysvalue1 as $key=>$vals)
        {
            unset($vals);
            $keysort1[] = $key;
        }

        $keysvalue = array();
        $count = count($keysort);

        $keysvalue1 = array();
        $count1 = count($keysort1);

        if(strtolower($type) != 'asc')
        {
            for($i=$count-1; $i>=0; $i--)
            {
                $keysvalue[] = $array[$keysort[$i]];
            }

            for($i=$count1-1; $i>=0; $i--)
            {
                $keysvalue1[] = $array[$keysort1[$i]];
            }
        }else{
            for($i=0; $i<$count; $i++)
            {
                $keysvalue[] = $array[$keysort[$i]];
            }

            for($i=0; $i<$count1; $i++)
            {
                $keysvalue1[] = $array[$keysort1[$i]];
            }
        }

        foreach($keysvalue1 as $vv)
        {
            $start = $vv[$keys1]-1;

            if(count($keysvalue)>$start)
                array_splice($keysvalue, $start, 0, array($vv));
            else
                $keysvalue[] = $vv;
        }

        unset($keysvalue1);

        return $keysvalue;
    }

    /**
     * 获取字段类型
     * @param array $fieldMappings
     * @param string $field
     * @return string
     */
    public function getFieldType(array $fieldMappings, $field)
    {
        if(isset($fieldMappings[$field]['type']))
            return $fieldMappings[$field]['type'];

        $_fieldMappings = array();

        foreach($fieldMappings as $key=>$val)
        {
            $_fieldMappings[lcfirst(self::ucWords($key))] = $val;
        }

        return isset($_fieldMappings[$field]['type'])?$_fieldMappings[$field]['type']:'string';
    }

    /**
     * 数据类型转换
     * @param array $data
     * @param array $fieldMappings
     * @param bool $flag
     */
    public function getDataType(array &$data, array $fieldMappings, $flag=false)
    {
        foreach($data as $kkkey=>&$vvval)
        {
            $type = self::getFieldType($fieldMappings, $kkkey);

            switch($type)
            {
                case 'smallint':
                case 'integer':
                case 'boolean':
                    $vvval = (int)trim($vvval);
                    break;
                case 'date':
                    if($flag){
                        if($vvval instanceof \DateTime)
                            continue;

                        if(is_array($vvval))
                            $vvval = isset($vvval['timestamp'])?new \DateTime($vvval['timestamp']):new \DateTime();
                        else
                            $vvval = new \DateTime($vvval);
                    }else{
                        if ($vvval instanceof \DateTime)
                            $vvval = $vvval->format('Y-m-d');
                        elseif(is_array($vvval))
                            $vvval = isset($vvval['timestamp'])?date('Y-m-d',$vvval['timestamp']):date('Y-m-d');
                    }
                    break;
                case 'time':
                    if($flag){
                        if($vvval instanceof \DateTime)
                            continue;

                        if(is_array($vvval))
                            $vvval = isset($vvval['timestamp'])?new \DateTime($vvval['timestamp']):new \DateTime();
                        else
                            $vvval = new \DateTime($vvval);
                    }else{
                        if ($vvval instanceof \DateTime)
                            $vvval = $vvval->format('H:i:s');
                        elseif(is_array($vvval))
                            $vvval = isset($vvval['timestamp'])?date('H:i:s',$vvval['timestamp']):date('H:i:s');
                    }
                    break;
                case 'datetime':
                    if($flag){
                        if($vvval instanceof \DateTime)
                            continue;

                        if(is_array($vvval))
                            $vvval = isset($vvval['timestamp'])?new \DateTime($vvval['timestamp']):new \DateTime();
                        else
                            $vvval = new \DateTime($vvval);
                    }else{
                        if ($vvval instanceof \DateTime)
                            $vvval = $vvval->format('Y-m-d H:i:s');
                        elseif(is_array($vvval))
                            $vvval = isset($vvval['timestamp'])?date('Y-m-d H:i:s',$vvval['timestamp']):date('Y-m-d H:i:s');
                    }
                    break;
                default:
                    $vvval = trim($vvval);
                    break;
            }
        }
    }

    public function htmlToCode($s=null)
    {
        if($s == null)
            return "";

        $s = str_replace("\r\n", "<br>&nbsp;&nbsp;", $s);
        $s = str_replace("\r", "<br>&nbsp;&nbsp;", $s);
        $s = str_replace("\n", "<br>&nbsp;&nbsp;", $s);
        $s = str_replace("\t", "&nbsp;&nbsp;&nbsp;&nbsp;", $s);

        return $s;
    }



    /**
     * 根据当前主题返回用户uid
     */
    public function getuid()
    {
        $user = self::getUser();
        if (!is_object($user))
            throw new \LogicException("请登入后再操作!", false);

        $map = array();
        $map['id'] = $user->getUserinfo()->getUsertype();
        $result = $this->get('db.mem_types')->findOneBy($map, array(), false);

        if (is_object($result) && 'MemberBundle' == $result->getUsertplid())
            return $user->getId();
        else
            return null;
    }

    /**
     * 获取随机码
     * @param int $len  生成位数
     * @return string
     */
    public function getRandStr($len)
    {
        $chars = array(
            "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
            "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
            "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
            "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
            "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
            "3", "4", "5", "6", "7", "8", "9"
        );
        $charsLen = count($chars) - 1;
        shuffle($chars);
        $output = "";
        for ($i=0; $i<$len; $i++)
        {
            $output .= $chars[mt_rand(0, $charsLen)];
        }

        return $output;
    }

    public function checkUsernameCensor($username)
    {
        $userevword = $this->get('db.mconfig')->findOneBy(array('name'=>'userevword', 'area'=>0));

        if(!is_object($userevword))
            return false;

        $censorusername = $userevword->getValue();

        if(empty($censorusername))
            return false;

        $censorexp = '/^('.str_replace(array('\\*', "\r\n", ' '), array('.*', '|', ''), preg_quote(($censorusername = trim($censorusername)), '/')).')$/i';

        //如果匹配，则返回true
        if(preg_match($censorexp, $username))
            return true;

        return false;
    }

    //$template格式：HouseBundle:Sale:list.html.twig
    public function getViewInit($template, $bundle=null, $controller=null, $action=null, $tplbundle=null)
    {
        //错误提示信息
        $error = '';

        $session = $this->get('request')->getSession();

        //判断是否有错误提示信息
        if ($this->get('request')->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $this->get('request')->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } elseif (null !== $session && $session->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        //获取错误信息
        if ($error)
            $error = $error->getMessage();

        $user = self::getUser();

        //控制器标识
        $filePath = self::getIdentPath($this->getBundleName());

        $bidentPath = $filePath."index.yml";
        $cidentPath = $filePath.($controller?$controller:ucfirst(self::getControllerName())).DIRECTORY_SEPARATOR."Index".DIRECTORY_SEPARATOR."index.yml";

        $this->get('core.ident')->createYmlFile($bidentPath);
        $this->get('core.ident')->createYmlFile($cidentPath);

        $map = array();//与分站无关的配置
        $map['ename'] = 'mwebset';
        $mconfig = $this->get('db.mconfig')->getData($map);

        $initInfo = array(
            'corepath'      => 'bundles/core',
            'last_username' => (null === $session) ? '' : $session->get(SecurityContext::LAST_USERNAME),
            'error'			=> $error?$this->get('translator')->trans($error):"",
            'csrf_token'	=> self::createCsrfToken(),
            'version'   	=> self::getParameter('_version'),
            'ismulticity'   => self::isMultiCity(),//是否多城市版
            'copyright'   	=> isset($mconfig['hostname']['copyright'])?$mconfig['hostname']['copyright']:'',
            'product'   	=> isset($mconfig['hostname']['value'])?$mconfig['hostname']['value']:'',
            'keyword'       => isset($mconfig['hostname']['cmskeyword'])?$mconfig['hostname']['cmskeyword']:'',
            'content'       => isset($mconfig['hostname']['cmsdescription'])?$mconfig['hostname']['cmsdescription']:'',
            'bundles'       => self::getBundles(),
            'bundlename'	=> $bundle?$bundle:self::getViewBundleName(),
            'bundlepath'	=> strtolower(substr(($bundle?$bundle:self::getViewBundleName()),0,-6)),
            'themespath'	=> $tplbundle?strtolower(substr($tplbundle,0,-6)):strtolower(substr(($bundle?$bundle:self::getViewBundleName(true)),0,-6)),
            'controller'	=> $controller?$controller:self::getControllerName(),
            'action'		=> $action?$action:self::getActionName(),
            'rolename'  	=> method_exists($user, 'getRoleName')?$user->getRoleName():"游客",
            'url'           => $this->get('request')->server->get('HTTP_REFERER'),
            'ident'         => $this->get('core.ident')->findTemplateYml($template),
            'bident'        => $this->get('core.ident')->findTemplateYml($template, $bidentPath),
            'cident'        => $this->get('core.ident')->findTemplateYml($template, $cidentPath),
        );

        //组装表单数据
        $useForm = array();
        $modelForm = "";
        $formid = $this->get('request')->get('_form_id', 0);
        $formflag = $this->get('request')->get('formflag', '');

        if($formflag||$formid>0)
        {
            $map = array();
            $map['name'] = $formflag;
            $map['id'] = (int)$formid;
            $modelForm = $this->get('db.model_form')->getDataOrX($map);

            unset($map);
        }else{
            $views = $this->get('db.views')->findOneBy(array('name'=>$template), array(), false);

            $useForm = (is_object($views)&&$views->getUseform())?explode(',', $views->getUseform()):array();

            unset($views);

            if(empty($useForm))
                return $initInfo;

            $modelForm = $this->get('db.model_form')->getData($useForm);
        }

        if(empty($modelForm))
            return $initInfo;

        foreach($modelForm as $formInfo)
        {
            self::getForm($formInfo, $initInfo, false, (int)$this->get('request')->get('_copy',0), 0, (count($useForm)>1)?true:false);
        }

        return $initInfo;
    }

    /**
     * 取得显示端所需要的bundle标记
     * @param $flag false(控制器所在的bundle)，true(主题bundle)
     */
    public function getViewBundleName($flag=false)
    {
        $usertplid = "";

        $bundle = self::getBundleName();

        if($flag)
        {
            $user = self::getUser();

            if(method_exists($user, 'getUsertplid')&&$bundle!="ManageBundle")                
                $usertplid = $user->getUsertplid();
            
            $locale =$this->get('request')->getLocale();
            
            $lang = $this->get('db.language')->getData($locale, 'ename');
            
            $usertplid = isset($lang['tplid'])&&$lang['tplid']?$lang['tplid']:$usertplid;

            $usertplid = $usertplid?$usertplid:$bundle;

            //判断手机浏览器
            if(self::isMobileClient())
            {
                //去掉bundle后缀
                if (preg_match('/Bundle$/', $usertplid))
                    $usertplid = substr($usertplid, 0, -6)."mobileBundle";
            }else{
                if($this->container->has('db.auth_theme'))
                {
                    $map = array();
                    $map['bundle'] = $this->getBundleName();
                    $map['controller'] = $this->getControllerName();
                    $map['action'] = $this->getActionName();
                    $count = $this->get('db.auth_theme')->getData($map);
                    if($count)
                        $usertplid = $this->getBundleName();
                }
            }

            return $usertplid;
        }

        return $bundle;
    }

    /**
     * 处理URL参数
     * @param string $url
     * @param string $vars
     * @param string $info
     */
    public function handleUrl($vars)
    {
        if(!is_array($vars))
            return $vars;

        foreach($vars as &$vv)
        {
            if(!is_array($vv))
                continue;

            $values = end($vv);

            switch(key($vv))
            {
                case 'eq':
                    $vv = is_array($values)?implode(',',$values):$values;
                    break;
                case 'andX':
                case 'orX':
                    if(!is_array($vv))
                        continue;

                    $expr = key($vv);

                    $arr = array();
                    if(is_array(end($vv)))
                    {

                        foreach(end($vv) as $its)
                        {
                            $arrs = array();
                            $arrs[] = key($its);
                            $its = end($its);
                            if(is_array($its))
                            {
                                $arrs[] = key($its);
                                $arrs[] = end($its);
                            }else{
                                $arrs[] = $its;
                            }
                            $arr[] = implode(',', $arrs);
                        }
                    }

                    $vv = $expr.'|'.implode('|',$arr);
                    break;
                default:
                    $vv = key($vv)."|".(is_array($values)?implode(',',$values):$values);
                    break;
            }
        }

        return $vars;
    }

    /**
     * 根据路由参数创建csrf
     * @return csrf
     */
    public function createCsrfToken()
    {
        $intention = self::getIntention();

        //返回csrf值
        return $this->get('form.csrf_provider')->generateCsrfToken($intention);
    }
    
    
    /**
     * 
     * @param array $data
     */
    public function createArray(array $data)
    {
        $str = "array(";

        foreach($data as $k=>$v)
        {
            if(empty($v))
                continue;
            
            if($k=='data'||$k=='auto_initialize')
                continue;

            if($k=='info'&&is_array($v))
            {
                unset($v['id']);
                unset($v['name']);
                unset($v['type']);
                unset($v['label']);
                unset($v['attr']);
                unset($v['auto_type']);
                unset($v['validate_time']);
                unset($v['checked']);
                unset($v['identifier']);
                unset($v['attributes']);
                unset($v['create_time']);
                unset($v['update_time']);
                unset($v['is_delete']);
                unset($v['issystem']);
                unset($v['sort']);
                unset($v['status']);
            }
            
            $str .= (is_numeric($k)?$k:"'".$k."'")."=>".(is_array($v)?(self::createArray($v)):(is_numeric($v)?$v:"'{$v}'")).",";
        }
        
        $str .= ")";
        
        return $str;
    }
    
    /**
     * 对象转数组
     * @param object $info
     * @return multitype:string
     */
    public function handleObject($info)
    {
        $data = array();
        $classMetadata = $this->get('doctrine.orm.entity_manager')->getClassMetadata(get_class($info));
        foreach($classMetadata->columnNames as $k=>$v)
        {
            $field = "get".$this->get('core.common')->ucWords($v);
            $data[$k] = method_exists($info, $field)?$info->$field():"";
        }
        return $data;
    }

    public function getIntention()
    {
        $user = self::getUser();

        //创建令环
        return method_exists($user, 'getId')?$user->getId():$this->get('core.common')->C('database_name');
    }
    
    /**
     * @param int $no_of_codes              定义一个int类型的参数 用来确定生成多少个优惠码
     * @param array $exclude_codes_array    定义一个exclude_codes_array类型的数组
     * @param int $code_length              定义一个code_length的参数来确定优惠码的长度
     * @return array                        返回数组
     */
    public function generatePromotionCode($no_of_codes,$exclude_codes_array='',$code_length =6)
    {
        $characters = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";

        //这个数组用来接收生成的优惠码
        $promotion_codes = array();
        for($j = 0 ; $j < $no_of_codes; $j++)
        {
            $code = "";
            for ($i = 0; $i < $code_length; $i++)
            {
                $code .= $characters[mt_rand(0, strlen($characters)-1)];
            }
            //如果生成的6位随机数不再我们定义的$promotion_codes函数里面
            if(!in_array($code,$promotion_codes))
            {
                if(is_array($exclude_codes_array))
                {
                    //排除已经使用的优惠码
                    if(!in_array($code,$exclude_codes_array))
                        //将生成的新优惠码赋值给promotion_codes数组
                        $promotion_codes[$j] = $code;
                    else
                        $j--;
                }else
                    $promotion_codes[$j] = $code;//将优惠码赋值给数组
            }else
                $j--;
        }

        return $promotion_codes;
    }
    
    /**
     * 判断来访是搜索引擎蜘蛛还是普通用户。搜索引擎返回true，普通用户返回false
     * @return boolean
     */
    public function isCrawler()
    { 
        $agent= strtolower($_SERVER['HTTP_USER_AGENT']); 
        
        if (!empty($agent))
        {
            $spiderSite= array(
                "TencentTraveler", 
                "Baiduspider+", 
                "BaiduGame", 
                "Googlebot", 
                "msnbot", 
                "Sosospider+", 
                "Sogou web spider", 
                "ia_archiver", 
                "Yahoo! Slurp", 
                "YoudaoBot", 
                "Yahoo Slurp", 
                "MSNBot", 
                "Java (Often spam bot)", 
                "BaiDuSpider", 
                "Voila", 
                "Yandex bot", 
                "BSpider", 
                "twiceler", 
                "Sogou Spider", 
                "Speedy Spider", 
                "Google AdSense", 
                "Heritrix", 
                "Python-urllib", 
                "Alexa (IA Archiver)", 
                "Ask", 
                "Exabot",  
                "OutfoxBot/YodaoBot", 
                "yacy", 
                "SurveyBot", 
                "legs", 
                "lwp-trivial", 
                "Nutch", 
                "StackRambler", 
                "The web archive (IA Archiver)", 
                "Perl tool", 
                "MJ12bot", 
                "Netcraft", 
                "MSIECrawler", 
                "WGet tools", 
                "larbin", 
                "Fish search", 
            );
            
            foreach($spiderSite as $val)
            { 
                $str = strtolower($val); 
                if (stripos($agent, $str) !== false)
                    return true; 
            } 
        } else { 
            return false; 
        } 
    }
    
    /**
     * 判断短信模版、邮箱模版是否开启
     * @param unknown $tpl
     * @param unknown $type
     */
    public function checkTplIsOpen($tpl,$type)
    {
        if ($type=='telcode')
            return $this->get('core.sms')->checkTplIsOpen($tpl, 'ename');
        elseif ($type=='mailcode')
        return $this->get('core.mail')->checkTplIsOpen($tpl);
    
        return false;
    }

    public function __clone(){}
}
