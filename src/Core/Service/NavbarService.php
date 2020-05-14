<?php

namespace WS\Core\Service;

use WS\Core\Library\Navbar\NavbarDefinition;
use WS\Core\Library\Navbar\NavbarDefinitionInterface;

class NavbarService
{
    protected $services = [];
    protected $navbar = null;

    public function registerNavbarDefinition(NavbarDefinitionInterface $service)
    {
        $this->services[] = $service;
    }

    public function getNavbar(): array
    {
        if ($this->navbar === null) {
            $this->navbar = [];

            foreach ($this->services as $service) {
                foreach ($service->getNavbarDefinition() as $definition) {
                    if ($definition instanceof NavbarDefinition) {
                        $this->navbar[$definition->getCode()] = $definition;
                    }
                }
            }

            usort($this->navbar, function (NavbarDefinition $menu1, NavbarDefinition $menu2) {
                return strcmp((string) $menu1->getOrder(), (string) $menu2->getOrder());
            });
        }

        return $this->navbar;
    }
}
