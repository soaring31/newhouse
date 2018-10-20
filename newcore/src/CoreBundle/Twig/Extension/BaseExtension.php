<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年5月17日
*/
namespace CoreBundle\Twig\Extension;

class BaseExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {

    }

    /**
     * 组装查询条件
     * @param  array  $query [description]
     * @return [type]        [description]
     */
    public function getQueryParam(array $param)
    {
        $orderArr = isset($param['order'])?explode('|', $param['order']):array();
        unset($param['order']);

        $info = $this->get('core.common')->getQueryParam($param);
        $info['_multi'] = isset($param['_multi'])?$param['_multi']:false;

        $order = array();
        if($orderArr)
            $order[$orderArr[0]] = isset($orderArr[1])?$orderArr[1]:(isset($info['orderBy'])?$info['orderBy']:'asc');

        unset($info['order']);
        unset($info['orderBy']);

        $param = array();
        $param['order'] = $order;
        $param['criteria'] = $info;

        return $param;
    }

    /**
     * get方法
     * @param number $id
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
}