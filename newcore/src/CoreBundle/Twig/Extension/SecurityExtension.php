<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年4月13日
*/
namespace CoreBundle\Twig\Extension;

use Symfony\Component\Security\Acl\Voter\FieldVote;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SecurityExtension extends \Twig_Extension
{
    private $rbac;
    private $context;
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->rbac = $this->get('core.rbac');
    }

    public function isGranted($url = null, $object = null, $field = null)
    {
        $bundle = null;
        $controller = null;
        $action = null;

        if (null !== $field) {
            $object = new FieldVote($object, $field);
        }

        // 解析URL
        $info   =  parse_url($url);
        if(!empty($info['path']))
        {
            $pathArr = explode("/",$info['path']);
            $action = $pathArr?array_pop($pathArr):null;
            $controller = $pathArr?array_pop($pathArr):null;
            $bundle = $pathArr?array_pop($pathArr):null;
        }

        return $this->rbac->isGranted($action, $controller, $bundle);
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('isGranted', array($this, 'isGranted')),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'securitys';
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