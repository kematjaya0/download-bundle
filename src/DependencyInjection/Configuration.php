<?php

namespace Kematjaya\DownloadBundle\DependencyInjection;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
class Configuration implements ConfigurationInterface 
{
    public function getConfigTreeBuilder(): TreeBuilder 
    {
        $treeBuilder = new TreeBuilder('kmj_download');
        $treeBuilder->getRootNode()
                ->children()
                    ->arrayNode('encrypt')
                        ->children()
                            ->scalarNode('key')->end()
                        ->end()
                    ->end()
                ->end();
        
        return $treeBuilder;
    }

}
