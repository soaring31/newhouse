<?php
/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2016-03-28
 */
namespace SphinxBundle\Doctrine;

use Doctrine\ORM\EntityManager;
use CoreBundle\Services\ServiceBase;
use Symfony\Component\DependencyInjection\Container;



/**
 * Bridge to find entities for search results
 */
class Bridge extends ServiceBase implements BridgeInterface
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * EntityManager name
     * @var string
     */
    protected $emName;

    /**
     * Indexes list
     * Key is index name
     * Value is entity name
     *
     * @var array
     */
    protected $indexes = array();

    /**
     * Constructor
     *
     * @param Container $container Symfony2 DI-container
     * @param string[optional] $emName EntityManager name
     * @param array[optional] $indexes List of search indexes with entity names
     */
    public function __construct(Container $container, $emName = 'core', $indexes = array())
    {
        $this->container = $container;
        
        $this->emName = $this->get('core.common')->getUserDb();
        $this->setIndexes($indexes);
    }

    /**
     * Get an EntityManager
     * @return EntityManager
     */
    public function getEntityManager()
    {
        if ($this->em === null) {
            $this->setEntityManager($this->get('doctrine')->getManager($this->emName));
        }

        return $this->em;
    }

    /**
     * Set an EntityManager
     *
     * @param EntityManager $em
     *
     * @throws LogicException If entity manager already set
     */
    public function setEntityManager(EntityManager $em)
    {
        if ($this->em !== null) {
            throw new \LogicException('Entity manager can only be set before any results are fetched');
        }

        $this->em = $em;
    }

    /**
     * Set indexes list
     *
     * @param array $indexes
     */
    public function setIndexes(array $indexes)
    {
        $this->indexes = $indexes;
    }

    /**
     * Add entity list to sphinx search results
     * @param  array  $results Sphinx search results
     * @param  string|array $index   Index name(s)
     *
     * @return array
     *
     * @throws LogicException If results come with error
     * @throws InvalidArgumentException If index name is not valid
     */
    public function parseResults(array $results, $service)
    {
        if (!empty($results['error']))
            throw new \LogicException('Search completed with errors');
        
        if (empty($results['matches']))
            return $results;

        foreach (array_keys($results['matches']) as $id)
        {
            $entities = $this->get($service)->findOneBy(array('id' => $id));

            if(!is_object($entities))
                continue;
  
            $results['data'][$entities->getId()] = $entities;
        }
        
        return $results;
    }
    public function parseResultsback(array $results, $index)
    {
        if (!empty($results['error'])) {
            throw new \LogicException('Search completed with errors');
        }

        if (!is_array($index))
        {
            $index = array($index);
        }
        
        foreach ($index as $idx) {
            if (!isset($this->indexes[$idx])) {
                 $this->indexes[$idx] = $this->get('core.common')->prefixEntityName($idx);               
            }
        }

        if (empty($results['matches'])) {
            return $results;
        }

        $dbQueries = array_reverse(array_keys($this->indexes));

        foreach ($results['matches'] as $id => &$match) {
            $match['entity'] = false;

            if (is_string($index)) {
                $dbQueries[$index][] = $id;
            } elseif (is_array($index)) {
                if(isset($match['attrs']['index_name']) && isset($this->indexes[$match['attrs']['index_name']]))
                    $dbQueries[$match['attrs']['index_name']][] = $id;
                else{
                    foreach ($index as $idx) {
                        $dbQueries[$idx][] = $id;
                    }
                }
            }
        }

        
        foreach ($dbQueries as $index => $ids) {
            if (!isset($this->indexes[$index])) {
                continue;
            }

            $entities = $this->getEntityManager()->getRepository($this->indexes[$index])->findBy(array('id' => $ids,'is_delete'=>0));
            foreach($entities as $item)
            {
                $id = $item->getId();
                //$results['matches'][$id]['entity'] = $item;
                $results['data'][$id] = $item;
            }
        }

        return $results;
    }
}
