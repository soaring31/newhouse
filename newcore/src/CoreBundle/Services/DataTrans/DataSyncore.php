<?php
/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2016年7月27日
 */
namespace CoreBundle\Services\DataTrans;

use Symfony\Component\DependencyInjection\ContainerInterface;

class DataSyncore extends DataTrans
{

    private $bundleFull = '';
    private $bundle = '';

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container); 
    }

    /**
     * 同步一个菜单,把导出的$mdata,导入到旧系统$pid下
     */
    public function synOneMenu($mdata,$pid=0)
    {
        $pids = array('0'=>$pid);
        foreach ($mdata as $ipid => $msubs) {
            $npid = $pids[$ipid];
            foreach ($msubs as $row) {
                $exKeys = array('pid'=>$npid,'_binaryFlag'=>1); // _binaryFlag:忽略二叉树结构计算
                $pids[$row['id']] = $this->synRowData($row,'db.menus',$exKeys,array('pid'));
            }
        }
    }
                
    /**
     * 同步一个模型,把导出的$mdata,导入到旧系统
     */
    public function synOneModel($mdata)
    {
        // mod info 
        $newmid = $this->synRowData($mdata['minfo'],'db.models',array(),array());
        // mod attr
        foreach ($mdata['mattr'] as $row) {
            $this->synRowData($row,'db.model_attribute',array('model_id'=>$newmid),array('model_id'));
        } //dump($newmid); //die(); 
        //* 生成元数据/重置缓存
        if(is_numeric($newmid)){
            try {
                // - 生成元数据 app_dev.php/manage/modelsfield/create?id=19
                $this->get('db.model_attribute')->createDbTable($newmid);
                // - 重置缓存 app_dev.php/manage/modelsmanage/cache?id=19
                $models = $this->get('db.models')->findOneBy(array('id'=>$newmid));
                $this->get($models->getServiceName())->retCache();
                //*/
            } catch (\Exception $e) { }

        }
        // mod form
        $fmids = array();
        foreach ($mdata['forms'] as $row) {
            $fmids[$row['id']] = empty($row['status']) ? 0 : $this->synRowData($row,'db.model_form',array('model_id'=>$newmid),array('model_id')); 
        } //die(); 
        // form attr
        foreach ($mdata['fmattr'] as $fmoid => $fmitems) {
            $pid = $fmids[$fmoid];
            if(empty($pid)) continue;
            foreach ($fmitems as $row) {
                $this->synRowData($row,'db.model_form_attr',array('model_form_id'=>$pid),array('model_form_id')); 
            }
        }
        // form views
        foreach ($mdata['views'] as $fmoid => $view) {
            $pid = $fmids[$fmoid];
            if(empty($pid)) continue;
            foreach ($view as $row) {
                try { // 控制器不存在错误,可能还未提交
                    $this->synRowData($row,'db.views',array('useform'=>$pid),array('useform')); 
                } catch (\Exception $e) { }
            }
        }
    }

    /**
     * 同步一行数据
     */
    public function synRowData($row,$svid,$appkeys=array(),$unsetkeys=array())
    {
        //$oldid = $row['id']; 
        unset($row['id']);
        $whr = array('identifier'=>$row['identifier'],'findType'=>1); 
        // 'useCache'=>false //缓存, false:不要缓存
        if(!empty($appkeys)) $row = array_merge($row,$appkeys); 
        $unsetkeys[] = 'id'; 
        #dump($svid); dump($whr); die();
        $item = $this->get($svid)->findOneBy($whr); //dump($item);
        if(empty($item)){ // ins
            $item = $this->get($svid)->add($row, null, false);//??
            //$item = $this->get($svid)->addImport($row);
            $newid = $item->getId(); //dump($item);
            //dump("add-$svid:$oldid-$newid:\n".implode(',',$row)); 
        }else{ // upd
            $newid = $item['id'];
            $comp = $this->cpArray($item,$row,$unsetkeys); 
            if(!$comp['cres']){
                $this->get($svid)->update($newid, $row, false);//??
                //dump("upd-$svid:$oldid-$newid:\n".$comp['item']."\n".$comp['row']); 
            }else{
                //dump("nul-$svid:$oldid-$newid");
            }
        }
        return $newid;
    }

    // setBundle
    public function _setBundle($bundleFull)
    {
        $this->bundleFull = $bundleFull;
        $bundle = strtolower(str_replace('Bundle', '', $bundleFull));
        $this->bundle = $bundle;
        $cfg = array('models','model_attribute','model_form','model_form_attr','views');
        foreach ($cfg as $key) {
            $this->get("db.$key")->setBundle($bundleFull); 
            // 用3:Call to a member function getField()
        }
    }

    // getDb
    public function _getDb($svid)
    {
        if(empty($this->bundle)){
            $odb = $this->get($svid);
        }else{
            $model = str_replace('db.','',$svid);
            $entity = $this->get('core.common')->prefixEntityName($model, $this->bundleFull);
            $odb = $this->get('doctrine')->getRepository($entity, $this->bundle);
        }
        return $odb;
    }

}


/* 

*/

