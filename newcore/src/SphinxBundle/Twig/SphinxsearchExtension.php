<?php
/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2016-03-28
 */
namespace SphinxBundle\Twig;

use SphinxBundle\Search\Sphinxsearch;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * Twig extension for Sphinxsearch bundle
 */
class SphinxsearchExtension extends \Twig_Extension
{
    /**
     * @var Sphinxsearch
     */
    protected $searchd;
    protected $container;

    /**
     * Constructor
     * @param Sphinxsearch $searchd
     */
    public function __construct(Sphinxsearch $searchd, ContainerInterface $container)
    {
        $this->searchd = $searchd;
        $this->container = $container;
    }
    
    /**
     * Filters list
     * @return array
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('sphinx_highlight', array($this, 'sphinx_highlight'), array('is_safe' => array('html')))
        );
    }
    
    /**
     * Implement getName() method
     * @return string
     */
    public function getName()
    {
        return 'sphinx_extension_0';
    }

    /**
     * Highlight $text for the $query using $index
     * @param string|array|object $text Text content
     * @param string $index Sphinx index name
     * @param string $query Query to search
     * @param array[optional] $options Options to pass to SphinxAPI
     *
     * @return string
     */
    public function sphinx_highlight($text, $index, $query, $options = array())
    {
        if(is_object($text))
            $text = $this->get('serializer')->normalize($text);

        if(!is_array($text))
            $docs = array((string)$text);
        else
            $docs = array_values($text);
        
        $result = $this->searchd->BuildExcerpts($docs, $index, $query, $options);

        if (!empty($result[0]))
            return $result[0];
        else
            return $text;
    }
    
    /**
     * get方法
     * @param number $id
     * @throws \InvalidArgumentException
     */
    protected function get($id)
    {
        if (!$this->container->has($id))
            throw new \InvalidArgumentException("[".$id."]服务未注册。");
    
        return $this->container->get($id);
    }
}
