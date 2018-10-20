<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年1月15日
*/
namespace ManageBundle\Controller;

use Symfony\Component\Finder\Finder;

//use CoreBundle\Services\DataTrans\DataExport;

/**
 * 模型管理
 */
class ModelsmanageController extends Controller
{
    public function showAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }
    
    /**
     * 保存、修改
     */
    public function saveAction()
    {
        if($this->get('request')->getMethod() == "POST")
        {
            $id = (int)$this->get('request')->get('id');
            
            $data = $this->get('request')->request->all();
            
            unset($data['csrf_token']);

            if($id>0)
                $this->get('db.models')->update($id, $data);
            else
                $this->get('db.models')->add($data);
        
            //清除缓存
            $this->cleanCace();
            return $this->success('操作成功');
        }
        
        return $this->error('操作失败');
    }
    
    public function deleteAction()
    {        
        $id = (int)$this->get('request')->get('id');
        $this->get('db.models')->delete($id);
        return $this->success('操作成功');
    }
    
    /**
     * 字段管理
     */
    public function fieldAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    public function synguidAction()
    {
        $syn = $this->get('core.datasynguid');
        $id = $this->get('request')->get('id');

        if($id=='menu'){
            $mall = $syn->cacGet(0,'import/menu_(all).08exp');
            $syn->synOneMenu($mall,0);
            die();
        }

        #$mdata = $syn->cacGet(0,'import/mod_(1).08exp');
        #$syn->synOneModel($mdata);

        if($id=='model'){
            // syn-all-model
            $path = $syn->getPath(0).'/import'; 
            $finder = new Finder(); 
            $finder->files()->in($path);

            foreach ($finder as $file) { 
                $filep = $file->getRelativePathname();
                if(strstr($filep,'mod_(')){
                    //echo "$filep<br>\n";
                    $mdata = $syn->cacGet(0,"import/$filep");
                    $syn->synOneModel($mdata);
                } 
            }
            die('xxx');
        }
        //dump($mall);
        
    }

    public function syncoreAction()
    {
        $syn = $this->get('core.datasyncore');
        $id = $this->get('request')->get('id');
        $type = $this->get('request')->get('type');
        #die();

        if($type=='menu'){ 
            $mall = $syn->cacGet(0,"import/menu_($id).08exp");
            $pid = $this->get('request')->get('pid');
            $pid = empty($pid) ? 0 : $pid; 
            $syn->synOneMenu($mall,$pid);
        }elseif($id=='model'){
            //*/ syn-all-model
            $path = $syn->getPath(0).'/import'; 
            $finder = new Finder(); 
            $finder->files()->in($path);

            foreach ($finder as $file) { 
                $filep = $file->getRelativePathname();
                if(strstr($filep,'mod_(')){
                    //echo "$filep<br>\n";
                    $mdata = $syn->cacGet(0,"import/$filep");
                    $syn->synOneModel($mdata);
                } 
            }
            //*/
        }elseif(is_numeric($id)){
            $mdata = $syn->cacGet(0,"import/mod_($id).08exp");
            $syn->synOneModel($mdata);
            die();
        }

        //dump($mall);
        die('xxx');
    }
    public function importAction()
    {
        //$upd = $this->get('core.dataupd');

        /*
        $res = $this->get('db.badkeywords')->add(array('kwbad'=>'a','kwrep'=>'b'));
        $nid = $res->getId();
        dump($nid);
        */

        /*
            * 1-数据库结构：
             - 数据库差异比较，更新
             - （？？？）使用[2]是否不用做此部分？
            * 2-架构：(model, menu)
             - 导出架构数据：
             - 从核心导入房产：
            * 3-数据：
             - 演示数据导入 
             - 后续：客户数据
            * 后续：升级程序：
             - 1+2
        */
        die('xxx');
    }

    /**
     * 导出安装文件
     */
    public function exmenuAction()
    {
        $id = $this->get('request')->get('id');
        $file = $this->get('request')->get('file');
        $fmt = $this->get('request')->get('fmt');
        $this->parameters['stamp'] = time();
        $exp = $this->get('core.dataexp');
        // 下载
        if(!empty($file)){ 
            $exp->downFile($file);
            die();
        // 导出all
        }elseif($id=='all'){
            $menus = $exp->expAllMenu();
            $file = $exp->expSave($menus,"menu_(all)");
            $this->parameters['file'] = $file;
            dump($menus); die();
        // 导出id
        }elseif(!empty($id)){
            $menus = $exp->getMenuinfo($id);
            $data = json_encode($menus);
            if($fmt=='json'){
                die($data);
            }elseif($fmt=='dump'){
                $dext = json_decode($data,1);
                dump($dext);
                die();  
            }else{
                $file = $exp->expSave($menus,"menu_($id)");
                $this->parameters['file'] = $file; 
                die(); 
            }
        }else{
            return $this->error('请指定id');   
        }
        // 输出twig
        return $this->render($this->getBundleName(), $this->parameters);
    }
    
    /**
     * 导出安装文件
     */
    public function exportAction()
    {
        $id = $this->get('request')->get('id');
        $file = $this->get('request')->get('file');
        $fmt = $this->get('request')->get('fmt');
        $this->parameters['stamp'] = time();
        $exp = $this->get('core.dataexp');
        // 下载
        if(!empty($file)){ 
            $res = $exp->downFile($file);
            if(is_array($res) && !empty($res['error'])){
                 return $this->error($res['error']);   
            }
            die();
        // 导出all
        }elseif($id=='all'){
            $files = $exp->expAllModel();
            $this->parameters['files'] = $files;
            $this->parameters['count'] = count($files);
        // 导出id
        }elseif(!empty($id)){
            $mdata = $exp->getModinfo($id);
            $data = json_encode($mdata);
            if($fmt=='json'){
                die($data);
            }elseif($fmt=='dump'){
                $dext = json_decode($data,1);
                dump($dext);
                die();  
            }else{
                $file = $exp->expSave($mdata,"mod_($id)");
                $this->parameters['file'] = $file;     
            }
        }else{
            return $this->error('请指定id');   
        }
        // 输出twig
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 生成缓存
    * admin
    */
    public function cacheAction()
    {
        $id = (int)$this->get('request')->get('_id', 0);
        $type = $this->get('request')->get('type', '');
        
        if($type=='all')
        {
            $models = $this->get('db.models')->findBy(array('autoup'=>1));
            if(isset($models['data'])&&$models['data'])
            {
                foreach($models['data'] as $v)
                {
                    $serviceName = $v->getServiceName();
                    
                    if($v->getMode()==1)
                        continue;
                    
                    if(empty($serviceName))
                        continue;

                    try {
                        $this->get($serviceName)->retCache();
                    } catch (\Exception $e) {
                    
                    }                    
                }
            }
            $this->parameters['filePath'] = '所有';
        }else{
            $models = $this->get('db.models')->findOneBy(array('id'=>$id));
            
            if($models->getMode()==1)
                $this->parameters['filePath'] = $models->getName();
            else
                $this->parameters['filePath'] = $this->get($models->getServiceName())->retCache();
        }
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 会员模型
    * admin
    */
    public function userframemodelsAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

}