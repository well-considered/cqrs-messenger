<?php

declare(strict_types=1);

namespace WellConsidered\CqrsMessenger\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('cqrs');

        $treeBuilder->getRootNode()
            ->children()

                ->scalarNode('command_bus')
                    ->defaultValue('command.bus')
                ->end()

                ->scalarNode('query_bus')
                    ->defaultValue('query.bus')
                ->end()

                ->arrayNode('enforce')
                ->addDefaultsIfNotSet()
                ->children()
                    ->booleanNode('single_handler')->defaultTrue()->end()
                    ->booleanNode('command_returns_void')->defaultTrue()->end()
                    ->booleanNode('query_returns_value')->defaultTrue()->end()
                ->end()
            ->end()

            ->arrayNode('idempotency')
                ->addDefaultsIfNotSet()
                ->children()
                    ->booleanNode('enabled')->defaultFalse()->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
