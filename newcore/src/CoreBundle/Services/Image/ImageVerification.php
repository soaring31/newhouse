<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年12月7日
*/
namespace CoreBundle\Services\Image;

use CoreBundle\Services\ServiceBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ImageVerification extends ServiceBase
{
    public $maxfontwith = 16;

    protected $data;
    protected $Keys;
    protected $imagePath;
    protected $dataArray;
    protected $ImageSize;    
    protected $NumStringArray;
    
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->imagePath = "d:/captcha.png";
//         $keysfiles = new files;
//         $this->Keys = $keysfiles->funserialize();
//         if($this->Keys == false)
//             $this->Keys = array();
//         unset($keysfiles);
    }
    
    public function init()
    {
//         $file = "d:/captcha.png";
//         $img = imagecreatefrompng($file);
//         $info = imagecolorat($img, 1, 10);
//         dump($img,$info);die();

        self::bmp2jpeg($this->imagePath);
    }
    
    public function setImage($Image)
    {
        $this->imagePath = $Image;
    }
    public function getData()
    {
        return $this->data;
    }
    
    public function study($info)
    {
        // 做成字符串
        $data = array();
        $i = 0;
        foreach($this->data as $key => $value)
        {
            $data[$i] = "";
            foreach($value as $svalue)
            {
                $data[$i] .= implode("",$svalue);
            }
            if(strlen($data[$i]) > $this->maxfontwith)
                ++$i;
        }
    
        if(count($data) != count($info))
        {
            //			echo count($data)."\n";
            //			print_r($data);
            //			echo count($info)."\n";
            echo "设置数据库数据出错";
            print_r($data);
            return false;
        }
    
        // 设置N级匹配模式
        foreach($info as $key => $value)
        {
            if(isset($this->Keys[0][$value])){
                //				print_r($value);
                $percent=0.0;
                similar_text($this->Keys[0][$value], $data[$key],$percent);
                //				print_r($percent);
                //				print_r(" \n");
                if(intval($percent) < 96)
                {
                    $i=1;
                    $OK = false;
                    while(isset($this->Keys[$i][$value]))
                    {
                        $percent=0.0;
                        //						print_r($value);
                        similar_text($this->Keys[$i][$value], $data[$key],$percent);
                        //						print_r($percent);
                        //						print_r(" \n");
                        if(intval($percent) > 96){
                            $OK = true;
                            break;
                        }
                        ++$i;
                    }
                    if(!$OK){
                        //						while(!isset($this->Keys[$i++][$value])){
                        //							print_r($i);
                        //						print_r($i);
                        //						print_r(" \n");
                        $this->Keys[$i][$value] = $data[$key];
                        //							$i++;
                        //						}
                    }
                }
    
            }else{
                $this->Keys[0][$value] = $data[$key];
            }
        }
    
        return true;
    
    }
    
    public function getResult()
    {
        return $this->dataArray;
    }
    public function getHec()
    {
        $res = imagecreatefromjpeg($this->imagePath);
        $size = getimagesize($this->imagePath);
        $data = array();
        for($i=0; $i < $size[1]; ++$i)
        {
            //			echo "$i  R  G  B\n";
            for($j=0; $j < $size[0]; ++$j)
            {
                $rgb = imagecolorat($res,$j,$i);
                $rgbarray = imagecolorsforindex($res, $rgb);
                //				echo "  ".$rgbarray['red']." ";
                //				echo $rgbarray['green']." ";
                //				echo $rgbarray['blue']." \n";
                //				/*
                if($rgbarray['red'] > 120 &&( $rgbarray['green']<80
                    || $rgbarray['blue'] < 80))
                {
                    $data[$i][$j]=1;
                }else{
                    $data[$i][$j]=0;
                }
                //				*/
            }
        }
    
        // 如果1的周围数字不为1，修改为了0
        for($i=0; $i < $size[1]; ++$i)
        {
            for($j=0; $j < $size[0]; ++$j)
            {
                $num = 0;
                if($data[$i][$j] == 1)
                {
                    // 上
                    if(isset($data[$i-1][$j])){
                        $num = $num + $data[$i-1][$j];
                    }
                    // 下
                    if(isset($data[$i+1][$j])){
                        $num = $num + $data[$i+1][$j];
                    }
                    // 左
                    if(isset($data[$i][$j-1])){
                        $num = $num + $data[$i][$j-1];
                    }
                    // 右
                    if(isset($data[$i][$j+1])){
                        $num = $num + $data[$i][$j+1];
                    }
                    // 上左
                    if(isset($data[$i-1][$j-1])){
                        $num = $num + $data[$i-1][$j-1];
                    }
                    // 上右
                    if(isset($data[$i-1][$j+1])){
                        $num = $num + $data[$i-1][$j+1];
                    }
                    // 下左
                    if(isset($data[$i+1][$j-1])){
                        $num = $num + $data[$i+1][$j-1];
                    }
                    // 下右
                    if(isset($data[$i+1][$j+1])){
                        $num = $num + $data[$i+1][$j+1];
                    }
                }
                if($num == 0){
                    $data[$i][$j] = 0;
                }
            }
        }
    
    
        $this->dataArray = $data;
        $this->ImageSize = $size;
    }
    
    public function run()
    {
        $result="";
    
        // 做成字符串
        // 做成字符串
        $data = array();
        $i = 0;
        foreach($this->data as $value)
        {
            $data[$i] = "";
            foreach($value as $skey => $svalue)
            {
                $data[$i] .= implode("",$svalue);
            }
            if(strlen($data[$i]) > $this->maxfontwith)
                ++$i;
        }
    
        // 进行关键字匹配
        foreach($data as $numString)
        {
            //			print_r($numString);
            $max=0.0;
            $num = 0;
            foreach($this->Keys as $value)
            {
                $FindOk = false;
                //				print_r($value);
                foreach($value as $skey => $svalue)
                {
                    //					print_r($svalue);
                    $percent=0.0;
                    similar_text($svalue, $numString,$percent);
                    //					print_r($percent);
                    //					echo " ";
                    if(intval($percent) > $max)
                    {
                        $max = $percent;
                        $num = $skey;
                        if(intval($percent) > 96){
                            $FindOk = true;
                            break;
                        }
                    }
                }
                if($FindOk)
                    break;
            }
            //			echo "\n max=";
            //			echo $max;
            //			echo "\n";
            //			echo $num."\n";
            $result.=$num;
        }
    
        // 查找最佳匹配数字
        return $result;
    }
    public function bmp2jpeg($file){
        $res = $this->imagecreatefrombmp($file);
        imagejpeg($res,$file.".jpeg");
    }
    
    public function filterInfo()
    {
        $data=array();
        $num = 0;
        $b = false;
        $Continue = 0;
        $XStart = 0;
        // X 坐标
        for($i=0; $i<$this->ImageSize[0]; ++$i)
        {
            // Y 坐标
            for($j=0; $j<$this->ImageSize[1]; ++$j)
            {
                if($this->dataArray[$j][$i] == "1")
                {
                    $b = true;
                    ++$Continue;
                    break;
                }else{
                    $b = false;
                }
            }
            if($b == true)
            {
                for($jj = 0; $jj < $this->ImageSize[1]; ++$jj)
                {
                    $data[$num][$jj][$XStart] = $this->dataArray[$jj][$i];
                }
                ++$XStart;
    
            }else{
                if($Continue > 0){
                    $XStart = 0;
                    $Continue = 0;
                    ++$num;
                }
            }
        }
    
        // 粘连字符分割
        $inum = 0;
        for($num =0; $num < count($data); ++$num)
        {
            $itemp = 5;
            $str = implode("",$data[$num][$itemp]);
            // 超过标准长度
            if(strlen($str) > $this->maxfontwith)
            {
                $len = (strlen($str)+1)/2;
                $flen = strlen($str);
                $ih = 0;
                //				$iih = 0;
                foreach($data[$num] as $value)
                {
                    $ix = 0;
                    $ixx = 0;
                    foreach($value as $skey=>$svalue)
                    {
                        if($skey < $len)
                        {
                            $this->data[$inum][$ih][$ix] = $svalue;
                            ++$ix;
                        }
                        if($skey > ($flen-$len-1))
                        {
                            $this->data[$inum+1][$ih][$ixx] = $svalue;
                            ++$ixx;
                        }
                    }
                    ++$ih;
                }
                ++$inum;
            }else{
                $i = 0;
                foreach($data[$num] as $value){
                    $this->data[$inum][$i] = $value;
                    ++$i;
                }
    
            }
            ++$inum;
        }
    
        // 去掉0数据
        for($num = 0; $num < count($this->data); ++$num)
        {
            if(count($this->data[$num]) != $this->ImageSize[1])
            {
                echo "分割字符错误";
                die();
            }
    
            for($i=0; $i < $this->ImageSize[1]; ++$i)
            {
                $str = implode("",$this->data[$num][$i]);
                $pos = strpos($str, "1");
                if($pos === false)
                {
                    unset($this->data[$num][$i]);
                }
            }
        }
    }
    
    public function Draw()
    {
        for($i=0; $i<$this->ImageSize[1]; ++$i)
        {
            echo implode("",$this->dataArray[$i]);
            echo "\n";
        }
    }
    public function imagecreatefrombmp($file)
    {

    }
    
    public function freadbyte($f)
    {
        return ord(fread($f,1));
    }
    
    public function freadword($f)
    {
        $b1=$this->freadbyte($f);
        $b2=$this->freadbyte($f);
        return $b2*256+$b1;
    }
    
    public function freaddword($f)
    {
        $b1=$this->freadword($f);
        $b2=$this->freadword($f);
        return $b2*65536+$b1;
    }

    public function __destruct()
    {
        //$keysfiles = new files;
        //$keysfiles->fserialize($this->Keys);
        //		print_r($this->Keys);
    }
}