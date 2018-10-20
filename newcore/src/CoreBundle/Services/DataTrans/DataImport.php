<?php
/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2016年9月1日
 */
namespace CoreBundle\Services\DataTrans;

use Symfony\Component\DependencyInjection\ContainerInterface;

class DataImport extends DataTrans
{

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }

    /**
     * imp
     */
    public function imp($id)
    {
        //
        $data = $this->oldData($id); // 获取旧数据
        //dump($data);
        $res = array('max'=>$this->proj->getOplimit(),'rec'=>count($data),'ok'=>array(),'ng'=>array()); 
        $okey = $this->proj->getDbkey(); //dump($rei);
        //return $res;
        foreach ($data as $row) {
            $new = $this->_trans($row); // 转换一行数据
            //dump($new); die();
            // add(array $data, \stdClass $info=null, $isValid=true, $_formId=0)
            $obj = $this->get($this->proj->getInsvid());
            $method = method_exists($obj, 'add_imp08cv50') ? 'add_imp08cv50' : 'addImport';
            $rei = $obj->$method($new,null,true,$this->proj->getInform()); 
            if($rei){
                $res['ok'][] = $row[$okey];
                $nid = $rei->getId();
                $nupd = 1;
            }else{
                $res['ng'][] = $row[$okey];
                $nid = 0;
                $nupd = 0;
            }
            $this->logSave($okey,$nid,$nupd,$row);
        }
        return $res;
    }

    /**
     * logSave
     */
    public function logSave($okey,$nid,$nupd,$row)
    {
        // cofgs
        $cfg1 = array('chid','caid','createdate','initdate','customurl','nowurl');
        $cfg2 = array('mchid','dirname','mspacepath','mname','msrefreshdate');
        $cfgs = array_merge($cfg1,$cfg2);
        $scfg = '';
        foreach ($cfgs as $key) {
            if(!empty($row[$key])) $scfg .= "$key={$row[$key]};";
        }
        $logs = array(
            'nid' => $nid,
            'nsvid' => $this->proj->getInsvid(),
            'nupd' => $nupd,
            'oid' => $row[$okey],
            'omod' => $this->proj->getOmod(),
            'ocfgs' => $scfg,
        ); 
        $this->get('db.data_implog')->add($logs,null,false);
    }

    /**
     * 获取旧数据
     */
    public function oldData($id=0)
    {
        // reset
        $proj = $this->get('db.data_import')->findOneBy(array('id'=>$id)); 
        $fcfg = $this->get('db.data_impset')->findBy(array('pid'=>$id,'findType'=>1)); 
        $fcfg = empty($fcfg['data']) ? array() : $fcfg['data']; 
        $this->proj = $proj;
        $this->fcfg = $fcfg; 
        // getMaxID:获取本地导入的一个最大值
        $byarr = array('nsvid'=>$this->proj->getInsvid(), 'omod'=>$this->proj->getOmod());
        $fsql = $proj->getFromsql();
        if(!empty($fsql)){ // 目前只考虑此种格式:`usertype=4`
            $tarr = explode('=',$fsql);
            $byarr = array($tarr[0]=>$tarr[1]);
        }
        $byarr = array_merge($byarr,array('useCache'=>0,'findType'=>1));
        //dump($byarr); die();
        $mrow = $this->get('db.data_implog')->findOneBy($byarr,array('oid'=>'DESC',));
        //dump($mrow); die($proj->getInsvid());
        $mfmid = empty($mrow['oid']) ? 0 : $mrow['oid']; 
        // getOldData:
        $key = $proj->getDbkey(); //dump($mfmid);
        $whr = strpos($proj->getDbsql(),'WHERE ')>0 ? ' AND ' : ' WHERE ';
        $dbsql = $proj->getDbsql()." $whr m.$key>'$mfmid' ORDER BY m.$key ASC LIMIT ".$proj->getOplimit(); 
        //die($dbsql);
        $this->dbout = $dbout = $this->get("core.datatrans")->init($proj->getDbcfg()); 
        $res = $dbout->getData($dbsql); 
        // 字符转化，看是否要转化
        $cset = $proj->getCharset(); 
        if(!empty($cset)) $res = $dbout->autoCSet($res,$cset); 
        return $res;
    }

    /**
     * 获取旧pid数据 // aalbums:1:pid
     * 合辑表,多对多,不适合这样算...以下,只处理一行
     * 多对多:另外处理...... ???????? 
     */
    public function oldPid($pcfg='',$row=array())
    {
        $arr = explode(':',$pcfg);
        $aid = isset($row['aid']) ? $row['aid'] : 0;
        $sql = "SELECT * FROM $arr[0] WHERE arid='$arr[1]' AND inid='$aid' LIMIT 2 ";
        $res = $this->dbout->getData($sql); 
        if(count($res)>0){
            $res = current($res); 
        } #dump($res); 
        $res = isset($res[$arr[2]]) ? $res[$arr[2]] : 0;
        #die($res);
        return $res;
    }

    /**
     * 转换一行数据
     */
    public function _trans($row)
    {
        $new = array(); 
        foreach ($this->fcfg as $fc) {
            $forg = $fc['forg']; // aalbums:1:pid
            $org = strpos($forg,':')>0 ? $this->oldPid($forg,$row) : (isset($row[$forg]) ? $row[$forg] : 0);
            // 处理...
            $method = "_deel".$this->get('core.common')->ucWords($fc['ftype']);
            if(method_exists($this, $method)){
                $org = $this->$method($org,$fc['pararm'],$row);
            }
            $new[$fc['fname']] = $org;
        } 
        // 通用处理字段:checked,status,sort,create_time,update_time,is_delete
        // checked
        if(!isset($new['checked'])){
            $new['checked'] = 1;
        }
        // initdate createdate  updatedate  refreshdate enddate
        // regdate  lastvisit   lastactive
        /*/ srcid_
        if(!isset($new['srcid_'])){
            $new['srcid_'] = $row[$this->proj->getDbkey()];
        }*/
        #print_r($new); die(); #dump($new); die();
        // print_r($new); print_r($this->proj); print_r($this->fcfg); die();
        return $new;
    }

    /**
     * _deelInput
     */
    private function _deelInput($val,$cfg='')
    {
        if(empty($cfg)) return $val;
        $val = $this->_ctab($val,$cfg); 
        return $val;
    }

    /**
     * _deelSelect
     */
    private function _deelSelect($val,$cfg='')
    {
        if(empty($cfg)) return $val;
        // 处理数组(多选)
        if(strstr($val,"\t")){ //08多选字段
            $val = explode("\t",$val);
        }elseif(strstr($val,",")){ //用,分开的多选
            $val = explode(",",$val);
        }
        $val = $this->_ctab($val,$cfg);
        $val = is_array($val) ? implode(',',$val) : $val;
        return $val;
    }

    /**
     * _deelPid
     */
    private function _deelPid($val,$cfg='')
    {
        if(empty($cfg)) return $val;
        $fcfg = $this->_cfmt($val,$cfg);
        $cfgs = explode(":",$fcfg); //house.newstest:id
        $kid = empty($cfgs[1]) ? 'id' : $cfgs[1];
        //die();
        $prow = $this->get($cfgs[0])->findOneBy(array('_fromid'=>$val,'findType'=>1)); 
        $pid = empty($prow[$kid]) ? 0 : $prow[$kid]; 
        return $pid;
    }

    /**
     * _deelMulti/: serialize -=> json
     */
    private function _deelMulti($val,$cfg='')
    {
        if(empty($cfg) || empty($val)) return $val;
        $val = $this->_ctab($val,$cfg); //先替换
        # serialize -=> json
        $arr = unserialize($val);
        $res = array();
        foreach ($arr as $item) {
            if(empty($item['remote'])) continue;
            $res[] = array(
                'href' => '', //'...ee98_100_100.jpg',
                'desc' => empty($item['title']) ? '' : $item['title'],
                'link' => '',
                'source' => $item['remote'],
            );
        }
        $val = json_encode($res);
        return $val;
    }

    /**
     * _deelExfunc
     */
    private function _deelExfunc($val,$cfg='',$row=array())
    {
        if(empty($cfg)) return $val;
        $fcfg = $this->_cfmt($val,$cfg); 
        $cfgs = explode(":",$fcfg); //core.eximphouse:testFunc:exp1
        if(empty($cfgs[0]) || empty($cfgs[1])) return $val;
        $exObj = $this->get($cfgs[0]);
        if($exObj){
            if(method_exists($exObj,$cfgs[1])){
                if($cfgs[2]=='(row)'){ $cfgs[2]=$row; }
                $val = $exObj->{$cfgs[1]}($val,$cfgs[2]);
                //echo("-+$val+\n");
            }
        }
        return $val;
    }

    /**
     * _deelFixval
     */
    private function _deelFixval($val,$cfg='')
    {
        
        if(strpos($cfg,'(')<=0) return $cfg; 
        $cfgs = explode("(",$cfg); 
        $params = str_replace(')','',$cfgs[1]);
        if(empty($params)){ //按设置返回
            return $cfg;
        }elseif($cfgs[0]=='stamp'){
            return time();
        }elseif($cfgs[0]=='rand'){
            $pa = explode(",",$params);
            if(is_int($pa[0]) && is_int($pa[1])){
                return mt_rand($pa[0], $pa[1]);
            }elseif(count($pa)>2){
                return $pa[mt_rand(0,count($pa)-1)];
            }
        }elseif($cfgs[0]=='stfmt'){
            return date($params);
        }else{ //按设置返回
            return $cfg;
        }
    }

    /**
     * 格式化参数
     * 1. 取出第一行参数返回，作为下一步操作；
     * 2. 排除第一行外参数，替换初始值（传引用不返回）
     */
    private function _cfmt(&$val,$cfg='')
    {
        if(strstr($cfg,"\n") || strstr($cfg,"\r")){
            $parts = array();
            preg_match("/[\\w\\:\\.\\(\\)]{8,96}/i", $cfg, $parts);
            $res = empty($parts[0]) ? str_replace(array("\n","\r"),'',$cfg) : $parts[0];
            // 替换初始值
            $cfg = str_replace(array("{$parts[0]}\r\n","{$parts[0]}\n","{$parts[0]}\r"),'',$cfg);
            $val = $this->_ctab($val,str_replace($cfg,'',$val));
        }else{ 
            $res = $cfg;
        }
        return $res;
    }

    /**
     * 处理转换表参数
     */
    private function _ctab($val,$cfg='')
    {
        $tab = array(); 
        $cfg = trim($cfg);
        $vbk = $val; //备份
        if(strlen($cfg)<3) return $val; // 1=a, 至少三个字符
        $cfg = preg_replace("/(\r\n)|(\\n)/" ,"\r" ,$cfg); // |(\\r)
        // parse_str($cfg, $tab); //dump($cfg); dump($tab);
        // parse_str : <!cmsurl /> 转义-=> <!cmsurl_/>
        $tab = explode("\r",$cfg);
        $norel = '';
        foreach ($tab as $row) {
            $tmp = explode("=",$row);
            if(!strlen($tmp[0]) || !isset($tmp[1]) || !strlen($tmp[1])) continue;
            $val = str_replace($tmp[0],$tmp[1],$val);
            if($tmp[0]=='norel') $norel = $tmp[1];
        } //if($val==577) dump($tab); 
        if($vbk==$val && !empty($norel)){
            $val = $norel; //dump($tab); dump($val); 
        }
        return $val;
    }

    /**
     * 处理转换表参数
     */
    public function clear($pid=0)
    {
        $plan = $this->get("db.data_import")->findOneBy(array('findType'=>1,'id'=>$pid)); 
        if(empty($plan)){
            return array(0, "导入方案错误:{$pid}！"); 
        }

        $whr = array('findType'=>1,'omod'=>$plan['omod'],'nsvid'=>$plan['insvid']);
        $logs = $this->get("db.data_implog")->findBy($whr); 
        if(empty($logs['data'])){
            return array(0, '暂无导入记录!'); 
        }else{
            $data = $logs['data']; //dump($data); die();
            foreach ($data as $r) {
                #try{
                    $this->get($r['nsvid'])->delete($r['nid']);
                    $this->get('db.data_implog')->delete($r['id']);
                #}catch(Exception $ex){  }
            }
            #dump($data);
            //return $this->container->success('ok');
            return array(1, "成功清理".count($data)."条记录!"); 
        }
    }

}

/* 

house.newstest:id
core.eximphouse:testFunc:exp1
admin=master2

### 问题：
* 嵌入创建时间
* checked / valid
* pic-url：系统本身-图片路径处理？
 - userfiles/ -=> uploads/old/
 - href

<!cmsurl />
<!ftpurl />

charset,
m(规定为主表别名)
fromid(规定为外表id)
 (int)，如果无int字段，先添加？？？

$s1 = 'a:5:{i:0;a:5:{s:6:"remote";s:51:"userfiles/image/20110609/091448344920901e4f6323.jpg";s:4:"size";s:6:"179115";s:5:"title";s:0:"";s:5:"width";i:500;s:6:"height";i:608;}i:1;a:5:{s:6:"remote";s:51:"userfiles/image/20110609/09144835f23a58617b7293.jpg";s:4:"size";s:6:"147383";s:5:"title";s:0:"";s:5:"width";i:382;s:6:"height";i:570;}i:2;a:5:{s:6:"remote";s:51:"userfiles/image/20110609/091448354775805eb10909.jpg";s:4:"size";s:5:"86094";s:5:"title";s:0:"";s:5:"width";i:500;s:6:"height";i:335;}i:3;a:5:{s:6:"remote";s:51:"userfiles/image/20110609/091448352f3bdebc197937.jpg";s:4:"size";s:6:"101009";s:5:"title";s:0:"";s:5:"width";i:500;s:6:"height";i:335;}i:4;a:5:{s:6:"remote";s:51:"userfiles/image/20110609/0914483515d42b42a37675.jpg";s:4:"size";s:6:"104433";s:5:"title";s:0:"";s:5:"width";i:500;s:6:"height";i:335;}}';
$s2 = '[{"href":"uploads\/picture\/000\/00\/00\/1\/000\/000\/622116d52562772dad6013a3ddf701d291b6ee98_100_100.jpg","desc":"","link":"","source":"uploads\/picture\/000\/00\/00\/1\/000\/000\/622116d52562772dad6013a3ddf701d291b6ee98.jpg"},{"href":"uploads\/picture\/000\/00\/00\/1\/000\/000\/0ab77d27377039a4e2d1b2f8eabfe157b8b7c399_100_100.gif","desc":"","link":"","source":"uploads\/picture\/000\/00\/00\/1\/000\/000\/0ab77d27377039a4e2d1b2f8eabfe157b8b7c399.gif"}]';

$a1 = unserialize($s1);
$a2 = json_decode($s2,1);

dump($a1);
dump($a2);

$c1 = json_encode($a1);
dump($c1);
$xx = '[{"remote":"userfiles\\/image\\/20110609\\/091448344920901e4f6323.jpg","size":"179115","title":"","width":500,"height":608},{"remote":"userfiles\\/image\\/20110609\\/09144835f23a58617b7293.jpg","size":"147383","title":"","width":382,"height":570},{"remote":"userfiles\\/image\\/20110609\\/091448354775805eb10909.jpg","size":"86094","title":"","width":500,"height":335},{"remote":"userfiles\\/image\\/20110609\\/091448352f3bdebc197937.jpg","size":"101009","title":"","width":500,"height":335},{"remote":"userfiles\\/image\\/20110609\\/0914483515d42b42a37675.jpg","size":"104433","title":"","width":500,"height":335}]';

    'remote' => 'userfiles/image/20110609/0914483515d42b42a37675.jpg',
    'size' => '104433',
    'title' => '',
    'width' => 500,
    'height' => 335,
  0 => 
    'href' => 'uploads/picture/000/00/00/1/000/000/622116d52562772dad6013a3ddf701d291b6ee98_100_100.jpg',
    'desc' => '',
    'link' => '',
    'source' => 'uploads/picture/000/00/00/1/000/000/622116d52562772dad6013a3ddf701d291b6ee98.jpg',

*/

