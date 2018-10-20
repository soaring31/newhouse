<?php
/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2016-03-28
 */
namespace SphinxBundle\Pagerfanta\Adapter;

/**
 * Pagerfanta pagination adapter for SphinxSearch
 *
 * @package IAkumaI\SphinxsearchBundle\Pagerfanta\Adapter
 */
class SphinxAdapter implements AdapterInterface
{
    /**
     * @var \SphinxBundle\Search\Sphinxsearch
     */
    private $sphinx;
    private $query;
    private $results;

    private $options = array(
        'max_results' => 1200,
        'entity' => array(),
    );

    /**
     * @param \IAkumaI\SphinxsearchBundle\Search\Sphinxsearch $sphinx
     * @param string $query query string
     * @param string|array $entityIndex Index index_name attribute from config.yml
     * @param array $options misc options
     */
    public function __construct(
        \SphinxBundle\Search\Sphinxsearch $sphinx,
        $query,
        $entityIndex,
        $options = array()
    ) {
        if ($sphinx->getBridge() === null) {
            throw new \RuntimeException('Entity bridge required for Sphinxsearch. Please, use setBridge() method on Sphinxsearch object.');
        }
        $this->query = $query;
        $this->sphinx = $sphinx;
        $this->options = array_merge($this->options, $options);

        $this->options['entity'] = $entityIndex;
    }

    /**
     * Returns an slice of the results.
     *
     * @param integer $offset The offset.
     * @param integer $length The length.
     *
     * @return array|\Traversable The slice.
     */
    function getSlice($offset, $length)
    {
        $this->sphinx->SetLimits($offset, $length, $this->options['max_results']);
        $this->results = $this->sphinx->searchEx($this->query, $this->options['entity']);
        if ($this->results['total_found'] == 0) {
            return array();
        }
        $results = array_map(
            function ($entity) {
                return $entity['entity'];
            },
            $this->results['matches']
        );

        return $results;
    }

    /**
     * Returns the number of results.
     *
     * @return integer The number of results.
     */
    function getNbResults()
    {
        $this->sphinx->SetLimits(1, 1, $this->options['max_results']);
        $results = $this->sphinx->searchEx($this->query, $this->options['entity']);

        return $results['total_found'];
    }

    /**
     * Returns raw result from sphinx. SHOULD BE called after getSlice() method
     *
     * @return array
     */
    public function getSphinxResult()
    {
        return $this->results;
    }

}
