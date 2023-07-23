<?php

namespace System25\T3sports;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use System25\T3sports\StatsIndexer\StatsInterface;

return function (ContainerConfigurator $container, ContainerBuilder $containerBuilder) {
    $containerBuilder->registerForAutoconfiguration(StatsInterface::class)->addTag('t3sports.stats.indexer');
    $containerBuilder->addCompilerPass(new DependencyInjection\StatsIndexerPass());
};
