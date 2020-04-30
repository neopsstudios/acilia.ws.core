<?php

namespace WS\Core\Library\DataCollector;

use Symfony\Component\DependencyInjection\Compiler\PriorityTaggedServiceTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class DataCollectorCompilerPass implements CompilerPassInterface
{
    use PriorityTaggedServiceTrait;

    public function process(ContainerBuilder $container)
    {
        if (!$container->has(BuildCollector::class)) {
            return;
        }

        $definition = $container->findDefinition(BuildCollector::class);

        $taggedServices = $this->findAndSortTaggedServices('ws.component', $container);
        foreach ($taggedServices as $taggedService) {
            $definition->addMethodCall('addComponent', [$taggedService]);
        }
    }
}
