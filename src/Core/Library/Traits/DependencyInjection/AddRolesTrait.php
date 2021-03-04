<?php

namespace WS\Core\Library\Traits\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;

trait AddRolesTrait
{
    public function addRoles(ContainerBuilder $container, array $roles)
    {
        $roles = array_merge_recursive(
            $container->getParameter('security.role_hierarchy.roles'),
            $roles
        );

        $container->setParameter('security.role_hierarchy.roles', $roles);
    }
}
