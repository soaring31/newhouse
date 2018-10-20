<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年2月3日
*/
namespace ManageBundle\Controller;

class ControllersidentController extends Controller
{
    /**
    * 实现的show方法
    * admin
    */
    public function showAction()
    {
        $filePath = null;
        $id = $this->get('request')->get('id', '');
        $pid = $this->get('request')->get('pid', 0);
        $bundle = $this->get('request')->get('bundleName','');
        $controller = $this->get('request')->get('controllerName','');
        
        if($bundle)
        {
            $filePath = $this->get('core.common')->getIdentPath($bundle);
        
            if($controller)
                $filePath .= $controller.DIRECTORY_SEPARATOR."Index".DIRECTORY_SEPARATOR."index.yml";
            else
                $filePath .= "index.yml";
            
            $info = $this->get('core.ident')->getYmlVal('', $filePath);

            $this->parameters['info'] = isset($info[$id])?$info[$id]:array();
        }else{        
            $map = array();
            $map['id'] = $pid;
            $views = $this->get('db.views')->findOneBy($map);
            $info = is_object($views)?$this->get('core.ident')->getYmlVal($views->getName()):array();
            $this->parameters['info'] = isset($info[$id])?$info[$id]:array();
        }

        if(isset($info[$id]))
        {
            //支持三元运算符(A?B)
//             $info[$id]['tableName'] = $this->get('core.common')->getParams($info[$id]['tableName']);
//             $info[$id]['subTableName'] = isset($info[$id]['subTableName'])&&$info[$id]['subTableName']?$this->get('core.common')->getParams($info[$id]['subTableName']):'';
            $this->parameters['fieldInfo'] = $this->_dataManage($info[$id]['tableName']);
            $this->parameters['fieldInfo1'] = ($info[$id]['dataModel']==2||$info[$id]['dataModel']==4)&&$info[$id]['subTableName']?$this->_dataManage($info[$id]['subTableName']):array();
        }

        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
     * 表关联模版
     * admin
     */
    public function show1Action()
    {
        $id = $this->get('request')->get('id', '');
        $pid = $this->get('request')->get('pid', 0);
        $bundle = $this->get('request')->get('bundleName','');
        $controller = $this->get('request')->get('controllerName','');
        
        if($bundle)
        {
            $filePath = $this->get('core.common')->getIdentPath($bundle);
        
            if($controller)
                $filePath .= $controller.DIRECTORY_SEPARATOR."Index".DIRECTORY_SEPARATOR."index.yml";
            else
                $filePath .= "index.yml";
        
            $info = $this->get('core.ident')->getYmlVal('', $filePath);
        }else{
            $map = array();
            $map['id'] = $pid;
            $views = $this->get('db.views')->findOneBy($map);
            $info = is_object($views)?$this->get('core.ident')->getYmlVal($views->getName()):array();
        }
        
        $this->parameters['info'] = array();
        $this->parameters['fieldInfo'] = array();
        $this->parameters['fieldInfo1'] = array();

        if(isset($info[$id]))
        {
            //支持三元运算符(A?B)
            //$info[$id]['tableName'] = $this->get('core.common')->getParams($info[$id]['tableName']);
            //$info[$id]['subTableName'] = isset($info[$id]['subTableName'])&&$info[$id]['subTableName']?$this->get('core.common')->getParams($info[$id]['subTableName']):'';

            $this->parameters['info'] = $info[$id];
            
            if(isset($this->parameters['info']['join'])&&$this->parameters['info']['join'])
            {
                foreach( $this->parameters['info']['join'] as $key=>$val)
                {
                    if(empty($key))
                        continue;
                    
                    $val['fieldInfo'] = $this->_dataManage($key);
                    
                    $this->parameters['info']['join'][$key] = $val;
                }
            }
        }

        return $this->render($this->getBundleName(), $this->parameters);
    }
    
    /**
     * 保存
     */
    public function saveAction()
    {
        if($this->get('request')->getMethod() == "POST")
        {
            $filePath = null;
            $pid = $this->get('request')->get('pid', 0);
            $bundle = $this->get('request')->get('bundleName','');
            $controller = $this->get('request')->get('controllerName','');
            
            if($bundle)
            {
                $filePath = $this->get('core.common')->getIdentPath($bundle);
                
                if($controller)
                    $filePath .= $controller.DIRECTORY_SEPARATOR."Index".DIRECTORY_SEPARATOR."index.yml";
                else
                    $filePath .= "index.yml";
            }

            //生成标识数据
            $this->get('core.ident')->createIdent($pid, $_POST, null, $filePath);

            return $this->success('操作成功');
        }

        return $this->error('操作失败');
    }
    
    /**
     * 保存
     */
    public function save1Action()
    {
        if($this->get('request')->getMethod() == "POST")
        {
            $pid = $this->get('request')->get('pid', 0);

            //生成标识数据
            $this->get('core.ident')->createJoin($pid, $_POST);

            return $this->success('操作成功');
        }

        return $this->error('操作失败');
    }
    
    /**
     * 删除
     */
    public function deleteAction()
    {
        $filePath = null;
        $id = $this->get('request')->get('id', '');
        $pid = (int)$this->get('request')->get('pid', 0);
        $bundle = $this->get('request')->get('bundleName','');
        $controller = $this->get('request')->get('controllerName','');
        
        if($bundle)
        {
            $filePath = $this->get('core.common')->getIdentPath($bundle);
        
            if($controller)
                $filePath .= $controller.DIRECTORY_SEPARATOR."Index".DIRECTORY_SEPARATOR."index.yml";
            else
                $filePath .= "index.yml";
        }else{
            if(empty($id))
                return $this->error('无效的id参数');
    
            if($pid<=0)
                return $this->error('无效的pid参数');
        }

        //删除标识数据
        $this->get('core.ident')->deleteIdent($pid, $id, $_POST, $filePath);

        return $this->success('操作成功');
    }

    public function delete1Action()
    {
        $pid = (int)$this->get('request')->get('pid', 0);
        $identName = $this->get('request')->get('identName', '');
        $tableName = $this->get('request')->get('tableName', '');

        if($pid<=0||empty($identName))
            return $this->error('无效的参数');

        //删除关联数据
        if($tableName)
            $this->get('core.ident')->deleteJoin($pid, $identName, $tableName);

        return $this->success('操作成功');
    }

    /**
     * 获取json数据(表字段)
     */
    public function getjsonAction()
    {
        $tableName = $this->get('request')->get('tablename', '');
        
        if(empty($tableName))
            return $this->error('请设置正确的表名称');

        $data = $this->_dataManage($tableName);

        $this->showMessage('提取成功', true, $data);
    }

    private function _dataManage($tableName)
    {
        //三目
        $tableName = $this->get('core.common')->getParams($tableName);

        //默认bundle
        $defaultBundle = $this->get('core.common')->getDefaultBundle();
        $tableClass = $defaultBundle.":".$this->get('core.common')->ucWords($tableName);

        //取表Metadata
        $classMetadata = $this->get('doctrine.orm.entity_manager')->getClassMetadata($tableClass);

        //操作符号
        $symbol = $this->get('core.common')->operationSymbol();
 
        unset($classMetadata->columnNames['is_delete']);
        unset($classMetadata->columnNames['password']);
        unset($classMetadata->columnNames['salt']);

        return array('data'=>$classMetadata->columnNames, 'symbol'=>$symbol);
    }
}