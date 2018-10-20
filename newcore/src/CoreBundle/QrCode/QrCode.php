<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年11月3日
*/
namespace CoreBundle\QrCode;

use CoreBundle\QrCode\Lib\QRencode;
use CoreBundle\Services\ServiceBase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class QrCode extends ServiceBase implements QrCodeInterface
{
    private $cacheable;
    private $cacheDir;
    private $logDir;
    private $findBestMask;
    private $findFromRandom;
    private $defaultMask;
    private $pngMaximumSize;
    
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->readConfiguration();
    }
    
    private function readConfiguration()
    {
        $filesystem = new Filesystem();
        
        $options = $this->container->getParameter('qrcode');

        $this->cacheable = $options['cacheable'];
        $this->cacheDir = $options['cache_dir'];
        $this->logDir = $options['cache_dir'];
    
        if (empty($this->cacheDir))
            $this->cacheDir = $this->container->getParameter("kernel.root_dir") . DIRECTORY_SEPARATOR . 'cache'. DIRECTORY_SEPARATOR . 'qrcodes' . DIRECTORY_SEPARATOR;

        //创建缓存目录
        if(!$filesystem->exists($this->cacheDir))
        {
            try {
                //创建文件夹
                $filesystem->mkdir($this->cacheDir);
            } catch (IOExceptionInterface $e) {
                throw new \LogicException("An error occurred while creating your directory at ".$e->getPath());
            }
        }

        if (empty($this->logDir))
            $this->logDir = $this->container->getParameter("kernel.logs_dir") . DIRECTORY_SEPARATOR . 'qrcodes' . DIRECTORY_SEPARATOR;

        //创建日志目录
        if(!$filesystem->exists($this->logDir))
        {
            try {
                //创建文件夹
                $filesystem->mkdir($this->logDir);
            } catch (IOExceptionInterface $e) {
                throw new \LogicException("An error occurred while creating your directory at ".$e->getPath());
            }
        }

        $this->findBestMask = $options['find_best_mask'];
        $this->findFromRandom = $options['find_from_random'];
        $this->defaultMask = $options['default_mask'];
        $this->pngMaximumSize = $options['png_maximum_size'];
    
        // Use cache - more disk reads but less CPU power, masks and format
        // templates are stored there
        define('QR_CACHEABLE', $this->cacheable);
        // Used when QR_CACHEABLE === true
        define('QR_CACHE_DIR', $this->cacheDir);
        // Default error logs dir
        define('QR_LOG_DIR', $this->logDir);
    
        // If true, estimates best mask (spec. default, but extremally slow;
        // Set to false to significant performance boost but (propably) worst
        // quality code
        define('QR_FIND_BEST_MASK', $this->findBestMask);
        // If false, checks all masks available, otherwise value tells count of
        // masks need to be checked, mask id are got randomly
        define('QR_FIND_FROM_RANDOM', $this->findFromRandom);
        // When QR_FIND_BEST_MASK === false
        define('QR_DEFAULT_MASK', $this->defaultMask);
    
        // Maximum allowed png image width (in pixels), tune to make sure GD and
        // PHP can handle such big images
        define('QR_PNG_MAXIMUM_SIZE', $this->pngMaximumSize);
        
        define('QR_MODE_NUL', -1);
        define('QR_MODE_NUM', 0);
        define('QR_MODE_AN', 1);
        define('QR_MODE_8', 2);
        define('QR_MODE_KANJI', 3);
        define('QR_MODE_STRUCTURE', 4);
        
        // Levels of error correction.
        
        define('QR_ECLEVEL_L', 0);
        define('QR_ECLEVEL_M', 1);
        define('QR_ECLEVEL_Q', 2);
        define('QR_ECLEVEL_H', 3);
        
        /**
         * Maximum QR Code version.
         */
        define('QRSPEC_VERSION_MAX', 40);
        
        // Supported output formats
        /**
         * Maximum matrix size for maximum version (version 40 is 177*177 matrix).
         */
        define('QRSPEC_WIDTH_MAX', 177);

        define('QR_FORMAT_TEXT', 0);
        define('QR_FORMAT_PNG',  1);
    }
    
    public function getQRCode($text, $size = 3, $format = 'png')
    {
        $result = array();
    
        $options = array(
            'size' => $size,
            'format' => $format,
        );
        $fileName = self::_createFileName($text, $options);
        $path = self::_getPath($text, $size, $format);
    
        $result['fileName'] = $fileName;
        $result['filePath'] = $path;
    
        return $result;
    }
    
    public function getQRCodeBase64($text, $size = 3, $format = 'png')
    {
        $content = "";
    
        $path = self::_getPath($text, $size, $format);
    
        try {
            $content = file_get_contents($path);
        } catch (\Exception $e) {
            throw new NotFoundHttpException();
        }
    
        return base64_encode($content);
    }
    
    private function _getPath($text, $size, $format)
    {
        $options = array(
            'size' => $size,
            'format' => $format
        );
        $path = $this->cacheDir . self::_createFileName($text, $options);

        if (!file_exists($path)){
            self::png($text, $path, QR_ECLEVEL_L, $size);
        }
    
        return $path;
    }
    
    private function _createFileName($text, $options)
    {
        $size = $options['size'];
        $format = $options['format'];
    
        return urlencode(md5($text) . "_$size.$format");
    }
    
    //----------------------------------------------------------------------
    public function png($text, $outfile = false, $level = QR_ECLEVEL_L, $size = 3, $margin = 4, $saveandprint=false)
    {
        $enc = QRencode::factory($level, $size, $margin);
        return $enc->encodePNG($text, $outfile, $saveandprint=false);
    }
    
    //----------------------------------------------------------------------
    public function text($text, $outfile = false, $level = QR_ECLEVEL_L, $size = 3, $margin = 4)
    {
        $enc = QRencode::factory($level, $size, $margin);
        return $enc->encode($text, $outfile);
    }
    
    //----------------------------------------------------------------------
    public function raw($text, $outfile = false, $level = QR_ECLEVEL_L, $size = 3, $margin = 4)
    {
        $enc = QRencode::factory($level, $size, $margin);
        return $enc->encodeRAW($text, $outfile);
    }
}