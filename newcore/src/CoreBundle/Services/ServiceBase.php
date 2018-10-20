<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年4月15日
*/
namespace CoreBundle\Services;

class ServiceBase
{
    protected $container;
    /**
     * 获得服务
     * @param string $id 容器中的服务id
     * @throws \InvalidArgumentException
     */
    protected function get($id)
    {
        /**
         * 兼容3.0之前的版本request服务
         */
        if($id=='request')
            return $this->container->get('request_stack')->getCurrentRequest();

        if (!$this->container->has($id))
            throw new \InvalidArgumentException("[".$id."]服务未注册。");
    
        return $this->container->get($id);
    }
    
    /**
     * Returns true if the service id is defined.
     *
     * @param string $id The service id
     *
     * @return bool true if the service id is defined, false otherwise
     */
    protected function has($id)
    {
        return $this->container->has($id);
    }    
    
}