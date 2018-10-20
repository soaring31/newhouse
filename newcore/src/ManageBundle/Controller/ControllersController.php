<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年1月19日
*/
namespace ManageBundle\Controller;

use Symfony\Component\Finder\Finder;

/**
 * 控制器管理
 */
class ControllersController extends Controller
{
    /**
     * 控制器管理
     */
    public function controllersmanageAction()
    {
        $iterator = $iteratorData = array();
        //默认Bundle
        $defaultBundle = $this->get('core.common')->C('site_bundle');
        $defaultBundle = $defaultBundle?$defaultBundle:$this->get('core.common')->getDefaultBundle();
        $searchName = $this->get('request')->get('bundlename',$defaultBundle);
        $controllerName = trim($this->get('request')->get('name',''));

        //当前页
        $pageIndex = (int)$this->get('request')->get('pageIndex',1);

        //每页显示行数
        $pageSize = (int)$this->get('request')->get('pageSize',8);

        if($searchName)
        {
            $searchNamePath = $this->getBundlePath($searchName)."Controller";

            $pattern = "/({$controllerName})(.+|.?)(Controller.php$)/i";

            $finder = new Finder();
            $_iterator = $finder
            ->files()
            ->name($pattern)
            ->sortByName()
            ->in($searchNamePath);
            $i = 0;

            foreach ($_iterator as $file) {
                $iterator[$i]['cTime'] = $file->getCTime();
                $iterator[$i]['mTime'] = $file->getMTime();
                $iterator[$i]['realpath'] = $file->getRealpath();
                $iterator[$i]['basename'] = $file->getBasename();
                $i++;
            }
        }

        // page
        $parMap = array();
        $parMap['varPage'] = 'p';
        $parMap['totalRows'] = count($iterator);
        $parMap['pageSize'] = $pageSize;
        $parMap['pageIndex'] = $pageIndex;

        $this->get('core.page')->setParam($parMap);

        $iteratorData['pageIndex'] = $pageIndex;
        $iteratorData['pageSize'] = $pageSize;
        $iteratorData['data'] = $iterator?$this->get('core.page')->data($iterator):array();
        $iteratorData['pageCount'] = count($iterator);

        $this->parameters['iterator'] = $iteratorData;
        $this->parameters['searchName'] = $searchName;
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 模版管理
    * admin
    */
    public function controllerstemplateAction()
    {
        //当前页
        $pageIndex = $this->get('request')->get('pageIndex', 1);

        //每页显示行数
        $pageSize = $this->get('request')->get('pageSize', 8);

        $map = $order = array();
        $map['bundle'] = $this->get('request')->get('searchName','');
        $map['controller'] = $this->get('request')->get('name','');
        $this->parameters['info'] = $this->get('db.views')->findBy($map, $order, $pageSize, $pageIndex);
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 标识管理
    * admin
    */
    public function bundleidentAction()
    {
        $bundle = $this->get('request')->get('bundleName','');

        $filePath = $this->get('core.common')->getIdentPath($bundle);
        $filePath .= "index.yml";

        $this->get('core.ident')->createYmlFile($filePath);

        $this->parameters['info'] = $this->get('core.ymlParser')->ymlRead($filePath);

        $this->parameters['views'] = $bundle.":index.yml";

        $this->parameters['_bundleName'] = $bundle;
        $this->parameters['_controllerName'] = "";

        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 模版标识管理
    * admin
    */
    public function controllerstplidentAction()
    {
        $this->parameters['_bundleName'] = $this->get('request')->get('bundleName','');
        $controller = $this->get('request')->get('controllerName','');
        $tplname = trim($this->get('request')->get('tplname',''));
        $this->parameters['_controllerName'] = str_replace(array("Controller.php"), "", $controller);

        $map = array();
        $map['controller'] = $controller;
        if($tplname)
            $map['name'] = array('like' => "%$tplname%");
        $views = $this->get('db.views')->findBy($map);

        if(isset($views['data'])){
            foreach ($views['data'] as $value) {
                $info = $this->get('core.ident')->getYmlVal($value->getName());
                if(empty($info))
                    continue;
                foreach ($info as $name => $item) {
                    $result = array('id' => $value->getId(), 'name' => $name);
                    $this->parameters['info'][$value->getName()] = array_merge($item, $result);
                }
            }
        }

        return $this->render($this->getBundleName(), $this->parameters);
    }

    public function controllersidentAction()
    {
        $bundle = $this->get('request')->get('bundleName','');
        $controller = $this->get('request')->get('controllerName','');

        $controller = str_replace(array("Controller.php"),"",$controller);

        $filePath = $this->get('core.common')->getIdentPath($bundle);
        $filePath .= $controller.DIRECTORY_SEPARATOR."Index".DIRECTORY_SEPARATOR."index.yml";

        $this->get('core.ident')->createYmlFile($filePath);

        $this->parameters['info'] = $this->get('core.ident')->getYmlVal(null,$filePath);

        $this->parameters['views'] = $bundle.":".$controller."/Index/index.yml";

        $this->parameters['_bundleName'] = $bundle;
        $this->parameters['_controllerName'] = $controller;
        return $this->render($this->getBundleName(), $this->parameters);
    }

    public function actionidentAction()
    {
        $id = $this->get('request')->get('id', 0);

        $map = array();
        $map['id'] = $id;
        $views = $this->get('db.views')->findOneBy($map);
        $this->parameters['info'] = is_object($views)?$this->get('core.ident')->getYmlVal($views->getName()):array();
        $this->parameters['views'] = is_object($views)?$views->getName():'';

        $this->parameters['_bundleName'] = "";
        $this->parameters['_controllerName'] = "";
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 系统菜单
    * admin
    */
    public function controllersmenuAction()
    {
        //嵌入参数
        $this->parameters['info'] = $this->getMenus();
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 系统角色
    * admin
    */
    public function controllersroleAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 访问控制
    * admin
    */
    public function controllersaccessAction()
    {
        //获取程序开始执行的时间
        $start_time=microtime(true);
        $name = $this->get('request')->get('name','');

        //当前页
        $pageIndex = $this->get('request')->get('pageIndex', 1);

        //每页显示行数
        $pageSize = $this->get('request')->get('pageSize', 8);

        $this->get('sphinx.search')->SetLimits($pageIndex, $pageSize);
        $this->get('sphinx.search')->SetMatchMode ( SPH_MATCH_EXTENDED2 );

        //$sphinx->SetSortMode(SPH_SORT_EXTENDED, "create_time DESC");
        //$sphinx->setFilter('gearbox ', 2);name
        $res = $this->get('sphinx.search')->searchEx($name, array('cate_cars','cate_cars_delta'), false);

        //获取程序执行结束的时间
        $end_time=microtime(true);

        //计算差值
        $res['microtime']=$end_time-$start_time;

        $this->parameters['info'] = $res;

        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
     * 系统节点
     */
    public function controllersnodeAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }
    
    

    /**
    * 实现的notforceswitchtheme方法
    * house
    */
    public function notforceswitchthemeAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 实现的trash方法
    * admina
    */
    public function trashAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }
}