<?php

namespace WS\Core\Library\FactoryService;

use Symfony\Component\DependencyInjection\Compiler\PriorityTaggedServiceTrait;
use WS\Core\Service\FactoryService;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class FactoryServiceCompilerPass implements CompilerPassInterface
{
    use PriorityTaggedServiceTrait;

    const TAG = 'ws.factory_service';

    public function process(ContainerBuilder $container)
    {
        if (!$container->has(FactoryService::class)) {
            return;
        }

        $definition = $container->findDefinition(FactoryService::class);

        $taggedServices = $this->findAndSortTaggedServices(self::TAG, $container);
        foreach ($taggedServices as $taggedService) {
            $definition->addMethodCall('registerService', [$taggedService]);
        }
    }
}
