<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016-03-28
*/
namespace SphinxBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('sphinx');
    
        $rootNode
            ->children()
                ->arrayNode('searchd')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('host')->defaultValue('localhost')->end()
                        ->scalarNode('port')->defaultValue('9312')->end()
                        ->scalarNode('socket')->defaultNull()->end()
                    ->end()
                ->end()
            ->end();
    
        $rootNode
            ->children()
                ->arrayNode('sphinx_api')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('file')->defaultValue(__DIR__.'../../Sphinx/SphinxAPI.php')->end()
                    ->end()
                ->end()
            ->end();
    
        $rootNode
            ->children()
                ->arrayNode('indexes')
                    ->useAttributeAsKey('key')
                    ->prototype('scalar')->end()
                ->end()
            ->end();
    
        $rootNode
            ->children()
                ->arrayNode('doctrine')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('entity_manager')->defaultValue('default')->end()
                    ->end()
                ->end()
            ->end();
    
        return $treeBuilder;
    }
}
