<?php
/**
 * @copyright Copyright (c) 2012 – 2020 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2017年9月1日
 */

namespace CoreBundle\Functions;

use CoreBundle\Services\ServiceBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * 地域、分站及其域名的处理
 *
 */
class Area extends ServiceBase
{
    protected $container;
    
    /**
     * 当前请求的上下文信息
     */
    protected $attributes = array();
    
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    /**
     * 根据所在分站及权限限定查询条件
     */
    public function dealQueryCriteria(array $criteria = array())
    {
        return $this->get('core.common.base')->dealQueryCriteria($criteria,$this);
    }

    /**
     * 分站管理员对指定数据对象的修改权限
     * 目前只能处理固定的分站字段(area)
     */    
    public function dealModifyPermission($info = null)
    {
        if(!is_object($info) || !method_exists($info, 'getArea')) {
            return true;
        }
        
        if($this->noAuthorizedRule()) {
            throw new \LogicException('您没有该分站内容的操作权限!');
        }
        
        return true;
    }

    /**
     * 强制地区管理员进入授权分站(仅作用于管理后台)
     */
    public function forceAuthorizedArea()
    {
        self::_reset();
        if($this->isManage()) {
            if(!($rulesArea = $this->getRules())) {
                throw new \LogicException('未授权的分站管理!');
            }
            
            if(!in_array($this->getArea(),$rulesArea)) {
                $areaId = $this->_doSaveArea(end($rulesArea));
                
                if(!in_array($areaId,$rulesArea)){
                    throw new \LogicException('未授权的分站管理!');
                }
            }
        }
    }
    /**
     * 没有当前分站权限的分站管理员
     */
    public function noAuthorizedRule()
    {
        if(!$this->isManage()) {
            return false;
        }
        
        return !in_array($this->getArea(), $this->getRules());
    }
        
    /**
     * 是否分站管理员
     */
    public function isManage()
    {
        if(!isset($this->attributes['isManage'])) {
            $tokenAttr = $this->get('core.common')->getTokenAttr();
            $this->attributes['isManage'] = empty($tokenAttr['isAreaManage']) ? false : true;
            $this->attributes['rules'] = empty($tokenAttr['rulesArea']) ? array() : $tokenAttr['rulesArea'];
        }
        return $this->attributes['isManage'];
    } 
    
    /**
     * 管理员被授权管理的分站
     */
    public function getRules()
    {
        return $this->isManage() ? $this->attributes['rules'] : array();
    }
       
    /** 
     * 当前请求的分站地域属性
     */ 
    public function getArea()
    {
        if(!isset($this->attributes['area'])) {
            $this->attributes['area'] = $this->get('core.common.base')->doFetchArea('area');
        }
        return $this->attributes['area'];
    }

    /**
     * 当前请求的分站地域的所有下级地域
     */
    public function getAreaSub()
    {
        if(!isset($this->attributes['areasub'])) {
            $this->attributes['areasub'] = $this->get('core.common.base')->doFetchArea('areasub');
        }
        return $this->attributes['areasub'];
    }
    
    /**
     * 上次请求的域名
     */
    public function getAreaHost()
    {
        if(!isset($this->attributes['areahost'])) {
            $this->attributes['areahost'] = $this->get('core.common.base')->doFetchArea('areahost');
        }
        return $this->attributes['areahost'];
    }   
    private function _reset()
    {
        $this->attributes = array();
    }


    public function getIpArea($ip='')
    {
        $addressConfig = array('area' => 0);
        
        $address = $this->get('oauth.baiduip')->getAddress($ip);
        if($address){
            $content = $address->content;
            $addressConfig['address'] = $content->address;
            $addressConfig['province'] = $content->address_detail->province;
            $addressConfig['city'] = $content->address_detail->city;
            $addressConfig['city_code'] = $content->address_detail->city_code;
            $addressConfig['point']['x'] = $content->point->x;
            $addressConfig['point']['y'] = $content->point->y;

            $city = $this->get('db.area')->getDataByName($addressConfig['city']);
            // 当$city不存在时，则需要给一个不存在的区域，比如99999，但是经测试目前不支持-1
            $addressConfig['area'] = $city ? (int)$city : 99999;
        }
        
        return $addressConfig;
    }
    /**
     * 当前请求的分站地域属性
     */
    public function setArea()
    {    
        $areaId = $this->get('request')->get('_area', '');
                
        if(!is_numeric($areaId)) {
            $this->_autoArea();
        } else {
            $this->_customArea($areaId);
        }
    }
    
    /**
     * 自动切换分站(规避主站0)
     */       
    private function _autoArea()
    {
        $areahost = $this->getAreaHost();
        if($areahost == $this->get('request')->getHost()) {
            return;
        }

        $areaId = self::_getAreaIdByHost();
        
        // 非分站域名，或非有效地域id，则通过ip定位
        if(!$areaId || !self::_isValidAreaID($areaId)) {
            $areaId = self::_getAreaIdByIP();
        }
        
        self::_doSaveArea($areaId);
    }
    
    /**
     * 手动切换分站(允许主站0)
     */    
    private function _customArea($areaId)
    {
        //注：要允许0，为主站，但错误的输入不能转到主站
        $areaId = (int)$areaId;
        if($this->getArea() == $areaId)
            return;
    
        //分站区域ID优先
        $hostAreaID = self::_getAreaIdByHost();
        $areaId = $hostAreaID ?: $areaId;

        if($areaId>0 && !self::_isValidAreaID($areaId)) {
            $areaId = self::_getAreaIdByIP();
        }
    
        self::_doSaveArea($areaId);
    }
    
    /**
     * 通过IP识别来得到区域ID，有可能得到0
     */
    private function _getAreaIdByIP()
    {
        $address = self::getIpArea();
        return $address['area'];
    }

    /**
     * 通过分站域名得到区域ID
     */
    private function _getAreaIdByHost()
    {
        $map = array();
        $map['type'] = 1;
        $map['domains'] = $this->get('request')->getHost();
        $domain = $this->get('db.domains')->findOneBy($map);

        return is_object($domain) ? (int)$domain->getAreas() : 0;
    }
    
    /**
     * 是否有效的区域ID(与是否分站区域无关),主站不算一个有效ID
     */
    private function _isValidAreaID($areaId)
    {
        $areaId = (int)$areaId;
        if($areaId <= 0) {
            return false;
        }
        
        $city = $this->get('db.area')->getData(array('id' => $areaId));
        return $city ? true : false;
    }
    
    private function _doSaveArea($areaId)
    {
        $areaId = $this->get('core.common.base')->doSaveArea($areaId);
        self::_reset();
        return $areaId;
    }    

}