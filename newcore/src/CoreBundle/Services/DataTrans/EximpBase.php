<?php
/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2016年9月5日
 */
namespace CoreBundle\Services\DataTrans;

use CoreBundle\Services\ServiceBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

//core.eximpbase
class EximpBase extends ServiceBase
{

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        //parent::__construct($container);
    }

    // core.eximpbase:mapconv(经纬度交换)
    public function mapconv($val,$expm=''){
        $res = explode(',',$val); 
        $res = "$res[1],$res[0],12"; 
        return $res;
    }

    // core.eximpbase:str2time
    public function str2time($val,$expm=''){
    	$res = strtotime($val);
    	return $res;
    }

    // core.eximpbase:stampfmt:full|date|time
    public function stampfmt($val,$expm=''){
    	$cfg = array(
    		'full' => 'Y-m-d H:i:s',
    		'date' => 'Y-m-d',
    		'time' => 'H:i:s',
    	);
    	$fmt = isset($cfg[$expm]) ? $cfg[$expm] : 'Y-m-d';
    	$res = date(intval($val),$fmt);
    	return $res;
    }

    public function testFunc($val,$expm=''){
    	return "$val~$expm";
    }

    // 检查账号
    // $data['username'])<3||strlen($data['username'])>16
    public function checkuid($val,$expm=''){ 
        $urow = $this->get('db.users')->findOneBy(array('username'=>$val,'useCache'=>0,'findType'=>1)); 
        //echo $val; print_r($urow); die();
        if(empty($urow) && strlen($val)<=16){
            return $val;
        }else{
            $val = substr($val,0,6).'_'.md5(microtime(1)); 
            return $this->checkuid(substr($val,0,16));
        }
        //$val = empty($urow) ? $val : substr($val,0,6).'_'.md5(microtime(1)); 
        //$val = substr($val,0,16); 
        //return $val; 
    }

    // 随机密码(暂未用)
    // $data['password'])<6||strlen($data['password'])>18
    public function randupw($val,$expm=''){
        $val = substr($val,0,4).'_'.substr(md5($expm['mname']),0,12);
        $val = substr($val,0,18);
        return $val;
    }

    // 检查邮箱
    public function checkemail($val,$expm=''){
        $urow = $this->get('db.users')->findOneBy(array('email'=>$val,'findType'=>1)); 
        $arr = explode('@',$val);
        $val = empty($urow) ? $val : substr($arr[0],0,24).'_'.md5(microtime(1)).'@'.$arr[1]; 
        return $val;
    }

}


/* 
* 密码 
```
 - $data['salt'] = substr(sha1($str), 0, 10);

 - $data['password'] = $this->get('security.encoder')->encodePassword($data['password'], $data['salt']);
 - return md5( md5( $raw ) . $salt );

 - _08_Encryption::password($password)
 - return md5( md5( $password ) );
```

* 转化密码(旧系统)

* 
```
    //检测用户名是否已存在
    $count = $this->count(array('username'=>$data['username']));
    
    if($count>0)
        throw new \InvalidArgumentException('用户名'.$data['username'].'已存在');
    
    if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL))
        throw new \InvalidArgumentException('邮箱'.$data['email'].'格式不对');
    
    //检测邮箱是否已存在
    $count = $this->count(array('email'=>$data['email']));
    
    if($count>0)
        throw new \InvalidArgumentException('邮箱'.$data['email'].'已被人使用');
```
*/

