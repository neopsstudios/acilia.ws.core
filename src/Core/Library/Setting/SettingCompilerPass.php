<?php

namespace WS\Core\Library\Setting;

use WS\Core\Service\SettingService;
use Symfony\Component\DependencyInjection\Compiler\PriorityTaggedServiceTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class SettingCompilerPass implements CompilerPassInterface
{
    use PriorityTaggedServiceTrait;

    const TAG = 'ws.setting_definition';

    public function process(ContainerBuilder $container)
    {
        if (!$container->has(SettingService::class)) {
            return;
        }

        $definition = $container->findDefinition(SettingService::class);

        $taggedServices = $this->findAndSortTaggedServices(self::TAG, $container);
        foreach ($taggedServices as $taggedService) {
            $definition->addMethodCall('registerSettingDefinition', [$taggedService]);
        }
    }
}
