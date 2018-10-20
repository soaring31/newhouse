<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年01月22日
*/
namespace ManageBundle\Controller;

/**
* Bundles管理
* @author admin
*/
class BundlesController extends Controller
{

    /**
    * 实现的bundlesmanage方法
    */
    public function bundlesmanageAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }


    /**
    * 路由管理
    * admin
    */
    public function bundlesrouteAction()
    {
        $routeData = array();
        $routeData['pageCount'] = 0;
        $routeData['data'] = array();
         
        //当前页
        $routeData['pageIndex'] = $this->get('request')->get('pageIndex', 1);
         
        //每页显示行数
        $routeData['pageSize'] = $this->get('request')->get('pageSize', 10);
        
        //默认Bundle
        $defaultBundle = $this->get('core.common')->getDefaultBundle();
        $bundle = $this->get('request')->get('bundlename', $defaultBundle);

        //根据bundle名查询该bundle下的路由配置文件,默认为查询Site配置目录下的文件
        $fileName = "Resources".DIRECTORY_SEPARATOR."config".DIRECTORY_SEPARATOR."routing.yml";
        $filePath = $this->getBundlePath($bundle).$fileName;
         
        $routeInfo = $this->get('core.ymlParser')->ymlRead($filePath);
         
        // page
        $parMap = array();
        $parMap['varPage'] = 'p';
        $parMap['totalRows'] = count($routeInfo);
        $parMap['pageSize'] = $routeData['pageSize'];
        $parMap['pageIndex'] = $routeData['pageIndex'];
        
        $Page = $this->get('core.page');
        $Page->setParam($parMap);
        $routeData['data'] = $routeInfo?$Page->data($routeInfo):array();
        $routeData['pageCount'] = count($routeInfo);

        //嵌入数据
        $this->parameters['routebundle'] = $bundle;
        $this->parameters['routeData']	= $routeData;
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 主题管理
    * admin
    */
    public function thememanageAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 同步Models
    * admina
    */
    public function synmodsAction()
    {
        $id = $this->get('request')->get('id', '');
        $mid = $this->get('request')->get('mid', '');
        $tobid = $this->get('request')->get('tobid', ''); 
        //$mod1 = $this->get('db.models')->findOneBy(array('id'=>23,'findType'=>0));
        //dump($mod1);
        // syn one model
        if($mid && $tobid){
            # 导出:export:array
            $mdata = $this->get('core.dataexp')->getModinfo($mid);
            # 切换:change:bundle
            $this->get('core.datasyncore')->_setBundle($tobid);
            # 同步:syncore:bundle
            $this->get('core.datasyncore')->synOneModel($mdata);
            //*/
            return $this->success('操作成功:'."$mid -> $tobid");
            #die();
        }
        // display model list
        $minfos = $this->get('db.models')->findBy(array('findType'=>1,'bundle'=>"$id"));
        $minfos = empty($minfos['data']) ? array() : $minfos['data']; //dump($minfos);
        $this->parameters['minfos'] = $minfos;
        $this->parameters['nowbundle'] = $this->get('core.common')->getUserBundle();
        return $this->render($this->getBundleName(), $this->parameters);  
    }



    /**
    * 缓存方案
    * house
    */
    public function cachemanageAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }
}