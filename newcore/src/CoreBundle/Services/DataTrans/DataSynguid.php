<?php
/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2016年7月27日
 */
namespace CoreBundle\Services\DataTrans;

use Symfony\Component\DependencyInjection\ContainerInterface;

/*
同步:专门用于同步核心的 identifier 字段
*/
class DataSynguid extends DataTrans
{

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }

    /**
     * 同步菜单 identifier
     */
    public function synOneMenu($mdata)
    {
        foreach ($mdata as $msubs) {
            //$npid = $pids[$ipid];
            foreach ($msubs as $row) { 
                $whrbase = array('bundle'=>$row['bundle'],'controller'=>$row['controller'],'action'=>$row['action']);
                $whr = $whrbase + array('name'=>$row['name'],'level'=>$row['level']);
                $this->synRowData($row,'db.menus',$whr);
            }
        }
    }
                
    /**
     * 同步一个模型,把导出的$mdata,导入到旧系统
     */
    public function synOneModel($mdata)
    {
        // mod info 
        $minfo = $mdata['minfo'];
        echo "<b>{$minfo['id']} : {$minfo['name']}</b><br>\n";
        $whr = array('name'=>$minfo['name']);
        $newmid = $this->synRowData($minfo,'db.models',$whr);
        //die();
        // mod attr
        foreach ($mdata['mattr'] as $row) {
            $whr = array('name'=>$row['name'],'model_id'=>$newmid);
            $this->synRowData($row,'db.model_attribute',$whr);
        } //die(); 
        // mod form
        $fmids = array();
        foreach ($mdata['forms'] as $row) {
            $whr = array('name'=>$row['name']); //,'model_id'=>$newmid
            $fmids[$row['id']] = $this->synRowData($row,'db.model_form',$whr); 
        } //die(); 
        // form attr
        foreach ($mdata['fmattr'] as $fmoid => $fmitems) {
            $pid = $fmids[$fmoid];
            if(empty($pid)) continue;
            foreach ($fmitems as $row) {
                $whr = array('name'=>$row['name'],'model_form_id'=>$pid); 
                $this->synRowData($row,'db.model_form_attr',$whr); 
            }
        }

    }

    /**
     * 同步一行数据
     */
    public function synRowData($row,$svid,$whr)
    {
        //$oldid = array();
        //$oldid = $row['id'];
        
        $whr = array();
        $whr['findType'] = 1;
        $item = $this->get($svid)->findOneBy($whr); 
        $newid = '';
        if(empty($item)){ // ins
            $newid = ''; //'ins.'.$oldid; 
            //dump("add-$svid:$oldid-$newid");
        }else{ // upd
            $newid = $item['id']; 
            $ugid = $row['identifier'];
            if(!empty($row['name'])) echo str_pad($row['name'],24,'-').' ';
            if($ugid==$item['identifier']){
                echo str_pad($newid,12,'-')." : $ugid (------)";
            }else{
                $this->get($svid)->update($newid, array('identifier'=>$ugid));
                echo str_pad($newid,12,'-')." : $ugid (update)";
            }
            echo " $svid <br>\n";
        } 
        return $newid;
    }

}


/* 

* 同步:[views/auth_rule]identifier

UPDATE cms_views hv 
INNER JOIN newcore.cms_views cv 
ON cv.name = hv.name 
SET hv.identifier = cv.identifier
WHERE hv.id>0;

UPDATE cms_auth_rule hr
INNER JOIN newcore.cms_auth_rule cr 
ON cr.bundle = hr.bundle AND cr.controller = hr.controller AND cr.action = hr.action
SET hr.identifier = cr.identifier
WHERE hr.id>0;

* output/input
 - http://192.168.1.11/newcore/web/app_dev.php/manage/modelsmanage/export?id=all
 - http://192.168.1.11/newcore/web/app_dev.php/manage/modelsmanage/synguid?id=model

demo 
        $syn = $this->get('core.datasynguid');

        $mall = $syn->cacGet(0,'import/menu_(all).08exp');
        #$syn->synOneMenu($mall,0);

        $mdata = $syn->cacGet(0,'import/mod_(1).08exp');
        #$syn->synOneModel($mdata);

        // syn-all-model
        $path = $syn->getPath(0).'/import'; 
        $finder = new Finder(); 
        $finder->files()->in($path);
        $files = array();
        foreach ($finder as $file) { 
            $filep = $file->getRelativePathname();
            if(strstr($filep,'mod_(')){
                //echo "$filep<br>\n";
                #$mdata = $syn->cacGet(0,"import/$filep");
                $syn->synOneModel($mdata);
            } 
        }
*/

