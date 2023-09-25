<?php

namespace System25\T3sports\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use System25\T3sports\Service\StatsIndexerProvider;

class StatsIndexerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        // always first check if the primary service is defined
        if (!$container->has(StatsIndexerProvider::class)) {
            return;
        }

        $definition = $container->findDefinition(StatsIndexerProvider::class);

        // find all service IDs with the t3sports.stats.indexer tag
        $taggedServices = $container->findTaggedServiceIds('t3sports.stats.indexer');

        foreach ($taggedServices as $id => $tags) {
            // add the indexer to the IndexerProvider service
            $definition->addMethodCall('addStatsIndexer', [new Reference($id)]);
        }
    }
}
