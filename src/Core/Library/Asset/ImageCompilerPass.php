<?php

namespace WS\Core\Library\Asset;

use WS\Core\Service\ImageService;
use Symfony\Component\DependencyInjection\Compiler\PriorityTaggedServiceTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class ImageCompilerPass implements CompilerPassInterface
{
    use PriorityTaggedServiceTrait;

    const TAG = 'ws.image_renditions';

    public function process(ContainerBuilder $container)
    {
        if (!$container->has(ImageService::class)) {
            return;
        }

        $definition = $container->findDefinition(ImageService::class);

        $taggedServices = $this->findAndSortTaggedServices(self::TAG, $container);
        foreach ($taggedServices as $taggedService) {
            $definition->addMethodCall('registerRenditions', [$taggedService]);
        }
    }
}
