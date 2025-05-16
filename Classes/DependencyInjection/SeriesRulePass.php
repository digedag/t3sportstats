<?php

namespace System25\T3sports\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use System25\T3sports\Series\SeriesRuleInterface;
use System25\T3sports\Series\SeriesRuleProvider;

class SeriesRulePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        // always first check if the primary service is defined
        if (!$container->has(SeriesRuleProvider::class)) {
            return;
        }

        $definition = $container->findDefinition(SeriesRuleProvider::class);

        // find all service IDs with the t3sports.stats.indexer tag
        $taggedServices = $container->findTaggedServiceIds(SeriesRuleInterface::TAG);

        foreach ($taggedServices as $id => $tags) {
            // add the indexer to the IndexerProvider service
            $definition->addMethodCall('addSeriesRule', [new Reference($id)]);
        }
    }
}
