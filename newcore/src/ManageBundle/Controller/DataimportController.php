<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年08月15日
*/
namespace ManageBundle\Controller;
        
/**
* 
* @author admina
*/
class DataimportController extends Controller
{
    /**
    * 实现的dataimportmanage方法
    * admina
    */
    public function dataimportmanageAction()
    {
        //dump('xxx');
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 实现的dataimportfields方法
    * admina
    */
    public function dataimportfieldsAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 实现的dataimportdeel方法
    * admina
    */
    public function dataimportdeelAction()
    {
        $id = (int)$this->get('request')->get('id');

        $res = $this->get("core.dataimp")->imp($id); 
        $this->parameters['res'] = $res; //dump($res);
        // rec=a, ok=n, ng=m, (如果rec=0停止)
        
        #dump($id);
        /*保存、修改
        // $_formId = (int)$this->get('request')->get('_form_id',0);
        if($this->get('request')->getMethod() == "POST")
        {
            $id = (int)$this->get('request')->get('id');
            if($id>0)
                $this->get('db.badkeywords')->update($id, $_POST);
            else
                $this->get('db.badkeywords')->add($_POST);
            //清除缓存
            $this->cleanCace();
            return $this->success('操作成功');
        }
        return $this->error('操作失败');
        */
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 架构模型升级
    * house
    */
    public function structureupdateAction()
    {
        
        //$id = $this->get('request')->get('id', '');
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
        $minfos = $this->get('db.models')->findBy(array('findType'=>1,'bundle'=>"houseBundle"));
        $minfos = empty($minfos['data']) ? array() : $minfos['data']; //dump($minfos);
        $this->parameters['minfos'] = $minfos;
        $this->parameters['nowbundle'] = $this->get('core.common')->getUserBundle();
        return $this->render($this->getBundleName(), $this->parameters);  

        #return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 实现的dataimportclear方法
    * admina
    */
    public function dataimportclearAction()
    {
        $id = (int)$this->get('request')->get('id');
        $res = $this->get('core.dataimp')->clear($id);
        $method = empty($res[0]) ? 'error' : 'success';
        return $this->$method($res[1]);
        die();
        $plan = $this->get("db.data_import")->findOneBy(array('findType'=>1,'id'=>$id)); 
        if(empty($plan)){
            return $this->error('导入方案错误:'.$id);
        }

        $whr = array('findType'=>1,'omod'=>$plan['omod'],'nsvid'=>$plan['insvid']);
        $logs = $this->get("db.data_implog")->findBy($whr); 
        if(empty($logs['data'])){
            return $this->error('暂无导入记录');
        }else{
            dump($logs['data']);
            //return $this->success('ok');
        }
        die();
        /* omod
            //die();
            $this->error('操作失败');
            'useCache'=>1,
        */
    }


    /**
    * 数据更新
    * house
    */
    public function dataupdateAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }
    
    //生成版本数据
    public function createAction()
    {
        $this->get('db.version')->createVersion();
        return parent::success('生成成功');
    }

    /**
    * 重置数据
    * house
    */
    public function resetAction()
    {
        $this->get('db.version')->resetVersion((int)$this->get('request')->get('_id',0));
        return parent::success('重置成功');
    }
}