<?php
/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2016年7月20日
 * Demo 和 init的$ormstr配置:
 * - app\config\config.yml 添加配置：两组配置：（doctrine : dbal|orm ）
 * - site\Config\parameters.yml 添加配置：（database_xxx）
 * - $db3 = $this->get("core.datatrans")->init('localcore'); 
 * - $res = $db3->getData($sql);
 */
namespace CoreBundle\Services\DataTrans;

//use Doctrine\ORM\EntityManager;

use CoreBundle\Services\ServiceBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DataTrans extends ServiceBase implements DataTransInterface
{
    public $minfos = array();
    public $outdb = NULL;
    protected $ormstr = '08cms_nv50db';

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        self::init();
    }

    /**
     * 初始化
     * @return $this
     */
    public function init($ormstr='')
    {
        if(empty($ormstr)){
            $minfos = $this->get('db.models')->findBy(array('findType'=>1));
            //dump($minfos); die();
            //$this->minfos = $minfos['data'];
            foreach ($minfos['data'] as $item) {
                $this->minfos[] = $this->toArray($item);
            }
            //dump($this->minfos); die();
        }else{
            $this->outdb = $this->get('doctrine')->getManager($ormstr);
            //dump($this->outdb); die();
        } 
        //dump($this->minfos); die();
        return $this;
    }

    /**
     * 执行sql语句（del,upd...）
     */
    public function exeSql($sql)
    {
        $stmt = $this->outdb->getConnection()->prepare($sql);
        $stmt->execute();
        return $stmt;
    }

    /**
     * 获取array数组
     **/
    public function getData($sql){
        $stmt = $this->exeSql($sql);
        return $stmt->fetchAll();
    }

    /**
     * 插入sql语句
     */
    public function insSql($sql)
    {
        $this->exeSql($sql);
        return $this->getLastId();
    }

    /**
     * 获取LAST_INSERT_ID
     */
    public function getLastId()
    {
        $stmt = $this->exeSql("SELECT LAST_INSERT_ID()");
        return $stmt->fetchColumn();
    }

    // 从Object转化为array
    function toArray($item,$skip='def') {
        $arr = array();
        if($skip=='def'){ 
            $skips = array('sort','create_time','update_time','is_delete','left_node','right_node'); 
        }else{
            $skips = array();
        }
        foreach ($item as $key=>$val) {
            if(in_array($key,$skips)){
                continue;
            }
            $arr[$key] = $val;
        }
        return $arr;
    }

    // 比较compArray
    function cpArray($item,$row,$unsetkeys=array()) {
        // row,旧数据,少了id
        $item2 = $row2 = array();
        foreach ($row as $key => $val) {
            if(in_array($key,$unsetkeys)){
                continue;
            }
            if(isset($item[$key])){
                $row2[$key] = $val; 
                $item2[$key] = $item[$key];
            }
        }
        $comp = array(); 
        $comp['item'] = str_replace("\r\n","\n",implode(',',$item2));
        $comp['row'] = str_replace("\r\n","\n",implode(',',$row2));
        /*
        if(in_array($item['id'],array(385,767))){
            dump($unsetkeys);
            dump($item2);
            dump($row2);
            dump($item);
            dump($row);
        }*/
        $comp['cres'] = $comp['item']==$comp['row'];
        return $comp;
    }

    // 转换字符集 支持数组转换
    public function autoCSet($str,$from='gbk',$to='utf-8'){
        if(empty($str) || empty($from) || empty($to)) return $str;
        $from = strtoupper($from)=='UTF8'? 'utf-8':$from;
        $to = strtoupper($to)=='UTF8'? 'utf-8':$to;
        if( strtoupper($from) === strtoupper($to) || (is_scalar($str) && !is_string($str)) ){
            return $str; //如果编码相同或者非字符串标量则不转换
        }
        if(is_string($str) ) {
            if(function_exists('iconv')){
                return iconv($from,$to."//IGNORE",$str); 
            }elseif(function_exists('mb_convert_encoding')){
                return mb_convert_encoding ($str, $to, $from);
            }else{
                return $str;
            }
        }elseif(is_array($str)){
            foreach ( $str as $key => $val ) {
                $str[$key] = self::autoCSet($val,$from,$to);
            }
            return $str;
        }else{
            return $str;
        }
    }

    function cacSave($arr,$file){
        $arr = is_array($arr) ? $arr : self::listDir($arr); 
        $data = var_export($arr,1);
        //$data = str_replace("\r\n", "\n", $data);
        $data = "<?php\nreturn $data; \n"; 
        $file = strpos($file,'.') ? $file : "$file.php";
        return $this->putFile($this->getPath()."/$file",$data);
    }
    function cacGet($iscache=1,$file){ 
        $file = strpos($file,'.') ? $file : "$file.php";
        $file = $this->getPath($iscache)."/$file"; //dump($file);
        return file_exists($file) ? include($file) : array();
    }
    function getPath($iscache=1,$checksub=0){ 
        $appdir = $this->get('core.common')->getParameter('kernel.root_dir'); // app
        $res = $appdir.($iscache ? '/cache' : '/Resources');
        if($checksub){
            if(!is_dir("$res/$checksub")){
                return mkdir("$res/$checksub", 0777, true); 
            }
            return true;
        }
        return $res;
    }

    //重定义file_put_contents来兼容不支持此函数的PHP
    function putFile($fname, $data){
        //return file_put_contents($fname, $data);
        $fp = @fopen($fname, "w");
        if(!$fp){
            $re = FALSE;
        }elseif(flock($fp,LOCK_EX)){ // 排它性的锁定
            fwrite($fp, $data);
            flock($fp,LOCK_UN); // release lock
            fclose($fp);
            $re = TRUE;
        }else{
            $re = FALSE;
        }
        return $re;
    }

}


/* 

*/

