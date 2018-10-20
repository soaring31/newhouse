<?php
/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package Tripod
 * create date 2015-7-13
 */

namespace CoreBundle\Services\Image;

use CoreBundle\Services\Ossfiles\OssWatermark;

/**
 * 图片管理
 */
class Image implements ImageInterface
{
    public $filename;    //文件地址
    public $ext;        //后缀名
    public $width;        //文件宽度
    public $height;        //文件高度
    public $type;        //文件类型
    public $_type;        //文件类型

    protected $_thumb;
    protected $_source = null;
    protected $_exts = array('jpg', 'gif', 'jpeg', 'png', 'bmp');

    public function __construct($filename)
    {
        $this->filename = $filename;
        $this->parse();
    }

    /**
     * 分析图片
     *
     * return void
     */
    public function parse()
    {
        list($this->width, $this->height, $this->type) = @getimagesize($this->filename);
        $typeMap = array(
            1 => 'gif',
            2 => 'jpeg',
            3 => 'png',
            6 => 'bmp'
        );
        $this->_type = @image_type_to_mime_type($this->type);
        $this->type = isset($typeMap[$this->type]) ? $typeMap[$this->type] : '';
    }

    /**
     * 判断是否为正常的图像
     *
     * return bool
     */
    public function isImage()
    {
        return empty($this->type) ? false : true;
    }

    /**
     * 获取该图像的标识符
     *
     * return resource
     */
    public function getSource()
    {

        if ($this->_source === null) {
            if (!$this->type || !function_exists('imagecreatefrom' . $this->type)) {
                $this->_source = false;
            } else {
                try {
                    $imagecreatefromtype = 'imagecreatefrom' . $this->type;
                    $this->_source = @$imagecreatefromtype($this->filename);
                } catch (\Exception $e) {

                    return null;
                }
            
            }
        }

        return $this->_source;
    }

    /**
     * 获取文件后缀
     *
     * @param string $fileName 文件名
     * return string
     */
    public function getExt($fileName = '')
    {
        $fileInfo = pathinfo($fileName ?: $this->filename);
        $this->ext = strtolower($fileInfo['extension']);

        return $this->ext;
    }

    /**
     * 重新绘制图片(防止非法图片造成攻击)
     */
    public function repaint()
    {
        if (!$source = $this->getSource()) return false;
        $imagefun = 'image' . $this->type;
        if (!function_exists($imagefun)) return false;
        if ($this->type == 'jpeg') {
            return call_user_func($imagefun, $source, $this->filename, 100);
        } else {
            return call_user_func($imagefun, $source, $this->filename);
        }
    }

    /**
     * 生成缩略图
     *
     * @param string $thumbUrl 缩略图地址
     * @param int $thumbWidth 宽度
     * @param int $thumbHeight 高度
     * @param int $quality 图片质量
     * @param int $thumbType 缩略图生成方式 <1.等比缩略 2.居中截取 3.等比填充>
     * @param int $forceMode 强制生成 <0.当文件尺寸小于缩略要求时，不生成 1.都生成>
     * return mixed
     */
    public function makeThumb($thumbUrl, $thumbWidth = 0, $thumbHeight = 0, $quality = 0, $thumbType = 0, $forceMode = 1)
    {
        $this->_thumb = new ImageThumb($this);
        $this->_thumb->setDstFile($thumbUrl);
        $this->_thumb->setWidth($thumbWidth > 0 ? $thumbWidth : $this->width);
        $this->_thumb->setHeight($thumbHeight > 0 ? $thumbHeight : $this->height);
        $this->_thumb->setQuality($quality);
        $this->_thumb->setType($thumbType);
        $this->_thumb->setForceMode($forceMode);
        return $this->_thumb->execute();
    }

    /**
     * 生成缩略图,带水印
     *
     * @param string $thumbUrl 缩略图地址
     * @param int $type 1为图片水印，2为文字水印
     * @param int $position 水印位置,9宫格
     * @param int $transparency 水印透明度
     * @param int $text 水印文字
     * @param int $file 水印图片
     * @param int $fontcolor 水印文字颜色，6位格式，如:#666666
     * @param int $quality 图片质量
     * @param int $quality 水印字体大小
     * return mixed
     */
    public function makeThumbByWater($thumbUrl, $type = 1, $position = 0
        , $transparency = 0, $text = null, $file = null, $fontcolor = "#666", $quality = 0, $fontsize = 12)
    {
        $this->_thumb = new ImageWatermark($this);
        $this->_thumb->setDstFile($thumbUrl);
        $this->_thumb->setPosition($position);
        $this->_thumb->setTransparency($transparency);
        $this->_thumb->setQuality($quality);
        $this->_thumb->setType($type);
        $this->_thumb->setText($text);
        $this->_thumb->setFile($file);
        $this->_thumb->setFontcolor($fontcolor);
        $this->_thumb->setFontsize($fontsize);
        return $this->_thumb->execute();
    }

    /**
     * 生成缩略图,切割方式
     *
     * @param string $dstUrl 缩略图地址
     * @param int $dstWidth 缩略图宽度
     * @param int $dstHeight 缩略图高度
     * @param int $dstX 缩略图X坐标(上面)
     * @param int $dstY 缩略图Y坐标(左面)
     * @param int $dstrotate 缩略图旋转位置
     * return mixed
     */
    public function makeThumbByCut($dstUrl, $dstWidth = 0, $dstHeight = 0, $dstX = 0, $dstY = 0, $dstRotate = 0)
    {
        $this->_thumb = new ImageCut($this);
        $this->_thumb->setDstFile($dstUrl);
        $this->_thumb->setDstWidth($dstWidth > 0 ? $dstWidth : $this->width);
        $this->_thumb->setDstHeight($dstHeight > 0 ? $dstHeight : $this->height);
        $this->_thumb->setDstX(0);
        $this->_thumb->setDstY(0);
        $this->_thumb->setDstRotate($dstRotate);
        return $this->_thumb->execute();
    }

    public function getThumb()
    {
        return $this->_thumb;
    }


    /**
     * 阿里云图片处理
     *
     * @param int $type 1为图片水印，2为文字水印
     * @param int $position 水印位置,9宫格
     * @param int $transparency 水印透明度
     * @param int $text 水印文字
     * @param int $file 水印图片
     * @param int $fontcolor 水印文字颜色，6位格式，如:#666666
     * @param int $quality 图片质量
     * @param int $quality 水印字体大小
     * return mixed
     */
    public function makeOssByWater($type = 1, $position = 0
        , $transparency = 0, $text = null, $file = null, $fontcolor = "#666", $quality = 0, $fontsize = 12)
    {
        $this->_thumb = new OssWatermark($this);
        $this->_thumb->setPosition($position);
        $this->_thumb->setTransparency($transparency);
        $this->_thumb->setQuality($quality);
        $this->_thumb->setType($type);
        $this->_thumb->setText($text);
        $this->_thumb->setFile($file);
        $this->_thumb->setFontcolor($fontcolor);
        $this->_thumb->setFontsize($fontsize);
        return $this->_thumb->initWaterWay();
    }

}
