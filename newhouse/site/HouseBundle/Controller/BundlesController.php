<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年07月19日
*/
namespace HouseBundle\Controller;

/**
* 路由配置
* @author house
*/
class BundlesController extends Controller
{

/**
    * 路由配置
    * house
    */
    public function bundlesrouteAction()
    {
        $routeData = array();
        $routeData['pageCount'] = 0;
        $routeData['data'] = array();
        //筛选条件
        $status = $this->get('request')->get('status');
        $name = $this->get('request')->get('name');
         
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

        //根据status进行筛选
        if ($status==1)
        {
            $temp = array();
            foreach ($routeInfo as $k=>$v)
            {
                if ($v['options']['status'] == true)
                    $temp[$k] = $v;
            }
            $routeInfo = $temp;
            unset($temp);
        } elseif ($status==0 && $status!==null) {
            $temp = array();
            foreach ($routeInfo as $k=>$v)
            {
                if ($v['options']['status'] == false)
                    $temp[$k] = $v;
            }
            $routeInfo = $temp;
            unset($temp);
        }

        //根据 名称、标识  进行筛选
        if ($name !== null)
        {
            $temp = array();
            foreach ($routeInfo as $k=>$v)
            {
                if (false !== strpos($k, $name) || false !== strpos($v['options']['remark'], $name))
                    $temp[$k] = $v;
            }
            $routeInfo = $temp;
            unset($temp);
        }
        
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

}