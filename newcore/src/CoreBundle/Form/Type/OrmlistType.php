<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年5月20日
*/
namespace CoreBundle\Form\Type;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OrmlistType extends BaseType
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $data = array();
        $info = $this->container->getParameter('doctrine.entity_managers');
        
        foreach(array_keys($info) as $item)
        {
            $key = ucfirst($item);
            $key .= "Bundle";
            $data[$key] = $item;
        }

        $resolver->setDefaults(array(
            'info' => array()
            ,'choices' => $data
            ,'compound' => false
        ));
    }
    
    public function getParent()
    {
        return 'choice';
    }
    
    public function getName()
    {
        return 'ormlist';
    }
}