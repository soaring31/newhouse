<?php
/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2016年7月21日
 */
namespace CoreBundle\Services\DataTrans;

use Symfony\Component\DependencyInjection\ContainerInterface;

class DataExport extends DataTrans
{

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }

    /**
     * 导出所有菜单
     */
    public function expAllMenu()
    {
        $mroots = $this->getExpArray('db.menus',0,'pid');
        $menus = array($mroots);
        foreach ($mroots as $item) { 
            $menus1 = $this->getMenuinfo($item['id'],0);
            unset($menus1[0]);
            $menus += $menus1;
        }
        return $menus;
    }

    /**
     * 得到一个完整的菜单配置数据, 包含本身和所有子类
     */
    public function getMenuinfo($id=0,$root=1)
    {
        $minfo = $this->getExpArray('db.menus',$id,'id');
        if(empty($minfo[0])) throw new \LogicException("[$id]错误id"); 
        $menus = $root ? array($minfo) : array(); // 注意传指针
        $this->_subMenus($id,$menus);
        return $menus;
    }

    /**
     * 得到pid=$id下菜单配置数据, 包含所有子类
     */
    private function _subMenus($id=0, &$menus=array())
    {
        $mdata = $this->getExpArray('db.menus',$id,'pid');
        if(!empty($mdata)) $menus[$id] = $mdata;
        if(!empty($mdata)){
            foreach ($mdata as $row) {
                $this->_subMenus($row['id'], $menus);
            }
        }
    }

    /**
     * 得到一个完整的模型配置数据
     */
    public function getModinfo($id=0,$re='arr')
    {
        //$ord = array('id'=>'asc');
        // mod info
        $minfo = array();
        foreach ($this->minfos as $item) {
            if($item['id']==$id){
                $minfo = $item;
                break;
            }
        } 
        // 重置缓存
        try {
            $mod1 = $this->get('db.models')->findOneBy(array('id'=>$minfo['id']));
            //dump($mod1); die();
            $this->get($mod1->getServiceName())->retCache();
        } catch (\Exception $e) { }
        // mod attr
        $mattr = $this->getExpArray('db.model_attribute',$minfo['id']); 
        // forms 
        $forms = $this->getExpArray('db.model_form',$minfo['id']); 
        // from attr
        $fmattr = array();
        foreach ($forms as $fm) {
            $attr1 = $this->getExpArray('db.model_form_attr',$fm['id'],'model_form_id');
            if(!empty($attr1)){
                $fmattr[$fm['id']] = $attr1;
            }
        }
        // from attr
        $views = array();
        foreach ($forms as $fm) {
            $view = $this->getExpArray('db.views',$fm['id'],'useform');
            if(!empty($view)){
                $views[$fm['id']] = $view;
            }
        }
        $res = array(
            'minfo' => $minfo,
            'mattr' => $mattr,
            'forms' => $forms,
            'fmattr' => $fmattr,
            'views' => $views,
        );
        return $re=='arr' ? $res : json_encode($res);
    }
    /**
     * 导出所有模型
     */
    public function expAllModel()
    {
        $files = array();
        foreach ($this->minfos as $item) {
            $mdata = $this->get('core.dataexp')->getModinfo($item['id']);
            $files[] = $this->get('core.dataexp')->expSave($mdata,"mod_({$item['id']})");
        }
        return $files;
    }

    /**
     * 获取数组
     * @param $kval: id=0,1,2 或 空字符串''即不需要条件
     */
    public function getExpArray($service,$kval,$field='model_id',$order=array())
    {
        if(empty($order)) $order = array('id'=>'asc');
        $whr = strlen($kval)==0 ? array() : array($field=>$kval);
        $whr['findType'] = 1;
        //$whr['update_time'] = array('gt'=>459928879);
        //dump($whr);
        $data = $this->get($service)->findBy($whr, $order);
        //dump($data); die();
        if(empty($data['data'])){
            return array();
        }else{
            $re = array();
            foreach ($data['data'] as $item) {
                $re[] = $this->toArray($item);
            }
        }
        return $re;
    }

    /**
     * 保存模型数据
     */
    public function expSave($data,$file='')
    {
        $this->getPath(1,'export'); 
        $file = "export/$file.08exp";
        $this->cacSave($data,$file);
        return $file;
    }

    /**
     * 下载本地文件
     */
    public function downFile($file, $showname='', $ftype='application/octet-stream', $expire=1800) 
    {
        //basEnv::obClean();
        $filename = $this->getPath()."/$file";
        if(!is_file($filename)){
            throw new \LogicException("[$file] 文件不存在。"); 
        }
        $showname = empty($showname) ? $file : $showname;
        if(preg_match('/^http:\/\//',$filename)){
            ini_set('allow_url_fopen', 'On');
        }
        if(file_exists($filename)) {
            $length = filesize($filename);
        }else{
            die("File NOT Found!");
        }
        //发送Http Header信息 开始下载
        header("Pragma: public");
        header("Cache-control: max-age=".$expire);
        //header('Cache-Control: no-store, no-cache, must-revalidate');
        header("Expires: " . gmdate("D, d M Y H:i:s",time()+$expire) . "GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s",time()) . "GMT");
        header("Content-Disposition: attachment; filename=".$showname);
        header("Content-Length: ".$length);
        header("Content-type: ".$ftype);
        header('Content-Encoding: none');
        header("Content-Transfer-Encoding: binary" );
        readfile($filename);
        return true;
    }

}


/* 

*/

