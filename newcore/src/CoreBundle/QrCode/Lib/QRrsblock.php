<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年11月4日
*/
namespace CoreBundle\QrCode\Lib;

class QRrsblock
{
    public $dataLength;
    public $data = array();
    public $eccLength;
    public $ecc = array();
    
    public function __construct($dl, $data, $el, &$ecc, QRrsItem $rs)
    {
        $rs->encode_rs_char($data, $ecc);
    
        $this->dataLength = $dl;
        $this->data = $data;
        $this->eccLength = $el;
        $this->ecc = $ecc;
    }
};