<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年07月19日
*/
namespace HouseBundle\Controller;
        
/**
* 路由管理
*  house
*/
class BundlesrouteController extends Controller
{    
    

    /**
    * 路由编辑
    * house
    */
    public function showAction()
    {
        $info = array();
        $bundleName = $this->get('request')->get('bundle', '');
        $routeName = $this->get('request')->get('routename', '');
        $disabled = $routeName?"disabled":"";
        
        //获得所有的bundle名
        $bundles = $this->getBundles();
        
        $bundleName = $bundleName?$bundleName:current(array_keys($bundles));
        
        $fileName = "Resources".DIRECTORY_SEPARATOR."config".DIRECTORY_SEPARATOR."routing.yml";
        $filePath = $this->getBundlePath($bundleName).$fileName;        

        $routeInfo = $this->get('core.ymlParser')->ymlRead($filePath);

        if(isset($routeInfo[$routeName]))
        {
            $map = array();
            $param = array();
            $controller = isset($routeInfo[$routeName]['defaults']['_controller'])?$routeInfo[$routeName]['defaults']['_controller']:'';
            $params = isset($routeInfo[$routeName]['defaults'])?$routeInfo[$routeName]['defaults']:array();
            
            $limitArr = array('_controller');

            foreach($params as $key=>$val)
            {
                if(in_array($key, $limitArr))
                    continue;
                $param[] = $key.'='.$val;
            }

            unset($routeInfo[$routeName]['defaults']);

            $map = explode(":", $controller);
            $info['key']            = $routeName;            
            $info['param']          = $param;
            $info['defaults']       = $controller;
            $info['path']           = $routeInfo[$routeName]['path'];
            $info['bundleName']     = isset($map[0])?$map[0]:'';
            $info['controllerName'] = isset($map[1])?$map[1]:'';
            $info['actionName']     = isset($map[2])?$map[2]:'';            
            $info['host']           = isset($routeInfo[$routeName]['host'])?$routeInfo[$routeName]['host']:'';            
//             $info['requirements']   = isset($routeInfo[$routeName]['requirements'])?$routeInfo[$routeName]['requirements']:'';
            $info['remark']   = isset($routeInfo[$routeName]['options']['remark'])?$routeInfo[$routeName]['options']['remark']:'';
            $info['status']   = isset($routeInfo[$routeName]['options']['status'])?$routeInfo[$routeName]['options']['status']:'';
            if (isset($routeInfo[$routeName]['requirements']))
            {
                $temp = array();
                foreach ($routeInfo[$routeName]['requirements'] as $k=>$v)
                {
                    $temp[] = $k.'='.$v;
                }
                $info['requirements'] = $temp;
                unset($temp);
            }
        }
        
        $map = array();
        $map['defaults'] = $routeName;
        $map['bundleName'] = $bundleName;
    
        $form = $this->getFormFieldAttr('routeformshow', $map, 'POST', $info, 'save');

        $form->add('bundleName', 'choice' , array(
            'label'=>'Bundle名称'
            //, 'attr'=>array('style'=>'padding:0px 10px;')
            , 'disabled'=>'disabled'
            , 'choices'=>$this->getBundles()
        ));
        // dump($this->getBundles());die();
        /*if($form->has('key'))
        {
            $form->add('key', null , array(
                'label'=>'唯一标识'
                , 'attr'=>array('class'=>'w200')
                , 'disabled'=>$disabled
            ));
        }*/
        
        if($bundleName&&empty($routeName)&&$form->has('bundleName'))
        {
            $form->get('bundleName')->setData($bundleName);
        }

        $this->parameters['form'] = $form->createView();
        return $this->render($this->getBundleName(), $this->parameters);
    }
    
    /**
     * 保存
     */
    public function saveAction()
    {
        $id = $this->get('request')->get('id','');
        
        if($id)
            return self::bathsave($id);
        
        $defaults = $this->get('request')->get('defaults','');
        
        if($this->get('request')->getMethod() != "POST")
            return $this->error("提交参数错误");
    
        $bundleName = $this->get('request')->get('bundleName', '');
        $controllerName = $this->get('request')->get('controllerName', '');
        $actionName = $this->get('request')->get('actionName', '');
        $host = $this->get('request')->get('host', '');
        $params = $this->get('core.common')->getQueryArray($this->get('request')->get('param', ''));
        $remark = $this->get('request')->get('remark', '');
        $status = $this->get('request')->get('status', '');
        $requirements = $this->get('request')->get('requirements', '');
        
        $str = preg_replace("/(\r\n)|(\\r)|(\\n)/" ,'&' ,$requirements);
        $str = explode('&',$str);
        $s = array();
        foreach ($str as $v)
        {
            if ($v == '')
                continue;
            $temp = explode('=', $v);
            $s[$temp[0]] = $temp[1];
        }
        $requirements = $s;
        unset($s);
        unset($str);

        //根据bundle名查询该bundle下的路由配置文件,默认为查询Site配置目录下的文件
        if($bundleName)
        {
            $fileName = "Resources".DIRECTORY_SEPARATOR."config".DIRECTORY_SEPARATOR."routing.yml";
            $filePath = $this->getBundlePath($bundleName).$fileName;
        }else{
            $fileName = "Config".DIRECTORY_SEPARATOR."config_routing.yml";
            $filePath = $this->getSiteRoot().$fileName;
        }
    
        $routeInfo = $this->get('core.ymlParser')->ymlRead($filePath);
    
        $map = array();
        $key = $this->get('request')->get('key', $defaults);//isset($_POST['key'])?trim($_POST['key']):$defaults;
        $path = $this->get('request')->get('path','');
        $map['bundle'] = $bundleName;
        $map['controller'] = ucfirst(trim($controllerName));
        $map['action'] = strtolower(trim($actionName));

        if(empty($key)) return $this->error('唯一标识名称不能为空');
    
        if(isset($routeInfo[$key])&&empty($defaults)) return $this->error('唯一标识名称已存在');

        $defaults = implode(":",$map);

        if(empty($path))
            return $this->error('路由URL不能为空');

        if(empty($map['bundle']))
            return $this->error('请选择Bundle');
        
        if(empty($map['controller']))
            return $this->error('Controller不能为空');
        
        if(empty($map['action']))
            return $this->error('Action不能为空');
    
        $routeInfo[$key]['path'] = $path;
        $routeInfo[$key]['defaults'] = array();
        $routeInfo[$key]['defaults']['_controller'] = $defaults;
        
        foreach($params as $k=>$item)
        {
            $routeInfo[$key]['defaults'][$k] = end($item);
        }

        unset($routeInfo[$key]['host']);
        unset($routeInfo[$key]['requirements']);

        if($host)
            $routeInfo[$key]['host'] = $host;
        
        if($requirements)
            $routeInfo[$key]['requirements'] = $requirements;
        
        if($remark)
            $routeInfo[$key]['options']['remark'] = $remark;
        
        $routeInfo[$key]['options']['status'] = (bool)$status;
    
        //写入配置文件
        $this->get('core.ymlParser')->ymlWrite($routeInfo, $filePath);
    
        //清空缓存
        $this->cleanCace();
    
        return $this->success('操作成功');
    }
    
    public function bathsave($id)
    {
        $id = is_array($id)?$id:explode(',',$id);

        if($this->get('request')->getMethod() != "POST"||empty($id))
            return $this->error("提交参数错误");
        
        $status = (bool)$this->get('request')->get('status',false);
        
        $bundleName = $this->get('core.common')->getBundleName();
        
        //根据bundle名查询该bundle下的路由配置文件,默认为查询Site配置目录下的文件
        if($bundleName)
        {
            $fileName = "Resources".DIRECTORY_SEPARATOR."config".DIRECTORY_SEPARATOR."routing.yml";
            $filePath = $this->getBundlePath($bundleName).$fileName;
        }else{
            $fileName = "Config".DIRECTORY_SEPARATOR."config_routing.yml";
            $filePath = $this->getSiteRoot().$fileName;
        }
    
        $routeInfo = $this->get('core.ymlParser')->ymlRead($filePath);

        foreach($id as $key)
        {
            if(!isset($routeInfo[$key]))
                continue;
            
            $routeInfo[$key]['options']['status'] = $status;
        }

        //写入配置文件
        $this->get('core.ymlParser')->ymlWrite($routeInfo, $filePath);
    
        //清空缓存
        $this->cleanCace();
    
        return $this->success('操作成功');
    }
    
    /**
     * 删除
     */
    public function deleteAction()
    {
        $bundleName = $this->get('request')->get('bundle', $this->get('core.common')->getBundleName());
        
        $keys = $this->get('request')->get('id', '');

        $keys = explode(',',$keys);

        if(empty($bundleName)||empty($keys))
            return $this->error('提交参数错误');        

         
        //根据bundle名查询该bundle下的路由配置文件,默认为查询Site配置目录下的文件
        if($bundleName)
        {
            $fileName = "Resources".DIRECTORY_SEPARATOR."config".DIRECTORY_SEPARATOR."routing.yml";
            $filePath = $this->getBundlePath($bundleName).$fileName;
        }else{
            $fileName = "Config".DIRECTORY_SEPARATOR."config_routing.yml";
            $filePath = $this->getSiteRoot().$fileName;
        }
    
        $routeInfo = $this->get('core.ymlParser')->ymlRead($filePath);
    
        foreach($keys as $key)
        {
            unset($routeInfo[$key]);
        }

        //写入配置文件
        $this->get('core.ymlParser')->ymlWrite($routeInfo, $filePath);
    
        //清空缓存
        $this->cleanCace();
    
        return $this->success('删除成功');
    }
}