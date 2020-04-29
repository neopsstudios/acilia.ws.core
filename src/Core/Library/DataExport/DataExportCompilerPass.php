<?php

namespace WS\Core\Library\DataExport;

use Symfony\Component\DependencyInjection\Compiler\PriorityTaggedServiceTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use WS\Core\Service\DataExportService;

class DataExportCompilerPass implements CompilerPassInterface
{
    use PriorityTaggedServiceTrait;

    const TAG = 'ws.data_export';

    public function process(ContainerBuilder $container)
    {
        if (!$container->has(DataExportService::class)) {
            return;
        }

        $definition = $container->findDefinition(DataExportService::class);

        $taggedServices = $this->findAndSortTaggedServices(self::TAG, $container);
        foreach ($taggedServices as $taggedService) {
            $definition->addMethodCall('addDataExporter', [$taggedService]);
        }
    }
}
