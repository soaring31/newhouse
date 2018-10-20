<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年06月17日
*/
namespace ManageBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
        
/**
* 微信菜单项
* 
*/
class Wxmenus extends AbstractServiceManager
{
    protected $table = 'Wxmenus';
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
    }


    /**
     * 获取菜单配置
     * @param string $appid 
     * @param string $defid 微信用户id
     * @param int $fmt35    是否按微信3个大菜单,5个子菜单格式输出
    */
    public function getMenuCfgs($appid,$defid='',$fmt35=1)
    {
        // appid对应的配置
        $mdata = $this->get('db.wxmenus')->findBy(array('appid'=>array('eq'=>$appid))); 
        $mcfgs = $this->fmtObjData($mdata,array('id','title','type','keyword','pid'),'kno'); 
        // (为空)获取默认配置
        if(empty($mdata['data']) && !empty($defid)){ 
            $mdata = $this->get('db.wxmenus')->findBy(array('appid'=>array('eq'=>$defid)));
            $mcfgs = $this->fmtObjData($mdata,array('id=0','title','type','keyword','pid'),'kno'); 
        }
        if(empty($fmt35)) return $mcfgs;
        // 微信3-5菜单格式转化
        $data = array();
        for($i=1;$i<=3;$i++){
            for($j=0;$j<=5;$j++){
                $data["$i$j"] = isset($mcfgs["$i$j"]) ? $mcfgs["$i$j"] : array('id'=>"0", 'title'=>'', 'keyword'=>'', 'type'=>'', 'keyword'=>'', 'pid'=>'', );
            }
        }
        return $data;
    }
    /**
     * 获取type=click的菜单
     * @param string $appid 
     */
    public function getClickmenu($appid)
    {
        $mdata = $this->findBy(array('appid'=>$appid, 'type'=>'click'));
        if(empty($mdata)) return false;
        $result = array();
        foreach($mdata['data'] as $item){
            if($item->getKeyword()){
                $result[] = $item->getKeyword();
            }
        }
        return $result;
    }

	/**
	 * 格式化数组(从对象)
     * @param object $object $this->get('db.srvname') 获取的数据对象
	*/
	public function fmtObjData($object=null,$items=array(),$kid='id')
	{
        $arr = array();
        if(empty($object['data'])) return $arr;
        $data = $object['data'];
        if(!empty($data)){
            foreach ($data as $val) {
                $mkid = "get".$this->get('core.common')->ucWords($kid); 
                $item = array(); 
                foreach ($items as $ikey)
                {
                	if(strpos($ikey,'=')){
                        $t = explode('=',$ikey);
                        $item[$t[0]] = $t[1];  
                    }else{
                        $mikey = "get".$this->get('core.common')->ucWords($ikey);
                        $item[$ikey] = $val->{$mikey}();  
                    }
                }
                $arr[$val->{$mkid}()] = $item; 
            }
        }
        return $arr;
	}

    // 保存处理多条菜单项
    public function mulSave($fmdata=array(),$appid='')
    {
        $ppid = 0;
        foreach(array(1,2,3) as $i)
        {
            foreach(array(0,1,2,3,4,5) as $j)
            {
                $row = $fmdata["$i$j"];
                if(empty($j))
                {
                    $row['pid'] = 0;
                    $ppid = $row['id'];
                }else
                    $row['pid'] = $ppid;
                
                if(empty($row['title']))
                { //菜单名称为空(即无效),如果有id表示已有这条数据就删除
                    parent::delete($row['id']); 
                    
                }elseif($row['id']){ //更新:有id表示已有这条数据
                    
                    parent::dbalUpdate($row, array('id' => $row['id']));

                }else{ //添加:无id需要添加
                    unset($row['id']);
                    $row['appid'] = $appid; 
                    $row['kno'] = "$i$j";                   
                    $rowt = parent::dbalAdd($row);
                    
                    if(empty($j)){ 
                        $ppid = $rowt->getId(); //dump($ppid);
                    }
                    //if(empty($j)) $ppid = $nowid;
                }
            }
        }
    }

}