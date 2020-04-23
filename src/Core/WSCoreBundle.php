<?php

namespace WS\Core;

use WS\Core\Library\ActivityLog\ActivityLogCompilerPass;
use WS\Core\Library\Alert\AlertCompilerPass;
use WS\Core\Library\Asset\ImageCompilerPass;
use WS\Core\Library\Dashboard\DashboardWidgetCompilerPass;
use WS\Core\Library\FactoryService\FactoryServiceCompilerPass;
use WS\Core\Library\Router\RouterCompilerPass;
use WS\Core\Library\Setting\SettingCompilerPass;
use WS\Core\Library\Sidebar\SidebarCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class WSCoreBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new SettingCompilerPass());
        $container->addCompilerPass(new ImageCompilerPass());
        $container->addCompilerPass(new AlertCompilerPass());
        $container->addCompilerPass(new SidebarCompilerPass());
        $container->addCompilerPass(new RouterCompilerPass());
        $container->addCompilerPass(new FactoryServiceCompilerPass());
        $container->addCompilerPass(new ActivityLogCompilerPass());
        $container->addCompilerPass(new DashboardWidgetCompilerPass());
    }
}
