<?php
/**
 * 新版客服功能
 *
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2016年6月29日
 */

namespace OAuthBundle\Services\WeiXin;

use Symfony\Component\DependencyInjection\SimpleXMLElement;

class WeiXinXml extends SimpleXMLElement{
    public function addCData($string){
        $dom = dom_import_simplexml($this);
        $cdata = $dom->ownerDocument->createCDATASection($string);
        $dom->appendChild($cdata);
    }
}
