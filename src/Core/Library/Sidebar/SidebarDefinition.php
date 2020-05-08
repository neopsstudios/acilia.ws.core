<?php

namespace WS\Core\Library\Sidebar;

class SidebarDefinition
{
    const SIDEBAR_CONTAINER = true;
    const SIDEBAR_CONTENT = false;

    protected $code;
    protected $container;
    protected $label;
    protected $route;
    protected $options;
    protected $children;

    public function __construct(string $code, string $label, array $route = null, array $options = [])
    {
        $this->code = $code;
        $this->label = $label;
        $this->route = $route;
        $this->children = [];

        $this->options = array_merge([
            'container' => self::SIDEBAR_CONTENT,
            'translation_domain' => 'cms',
            'icon' => 'fa-angle-right',
            'order' => 999,
            'roles' => [],
            'collapsed_routes' => [],
            'divider' => false

        ], $options);
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function isContainer(): bool
    {
        return $this->options['container'];
    }

    public function getRouteName(): ?string
    {
        if (isset($this->route['route_name'])) {
            return $this->route['route_name'];
        }

        return '#';
    }

    public function getRouteOptions(): array
    {
        if (isset($this->route['route_options'])) {
            return $this->route['route_options'];
        }

        return [];
    }

    public function getRoles(): array
    {
        return $this->options['roles'];
    }

    public function setRoles(array $roles): SidebarDefinition
    {
        $this->options['roles'] = $roles;

        return $this;
    }

    public function addRole(string $role): SidebarDefinition
    {
        $this->options['roles'][] = $role;

        return $this;
    }

    public function getCollapsedRoutes(): array
    {
        return $this->options['collapsed_routes'];
    }

    public function setCollapsedRoutes(array $collapsedRoutes)
    {
        $this->options['collapsed_routes'] = $collapsedRoutes;

        return $this;
    }

    public function addCollapsedRoute(string $collapsedRoute): SidebarDefinition
    {
        $this->options['collapsed_routes'][] = $collapsedRoute;

        return $this;
    }

    public function getIcon(): string
    {
        return $this->options['icon'];
    }

    public function addChild(SidebarDefinition $child)
    {
        $this->children[] = $child;
    }

    public function removeChild(SidebarDefinition $child)
    {
        foreach ($this->children as $childKey => $childValue) {
            if ($child->getCode() === $childValue->getCode()) {
                unset($this->children[$childKey]);
                break;
            }
        }
    }

    /**
     * @return SidebarDefinition[]
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    public function setChildren(array $children): SidebarDefinition
    {
        $this->children = $children;

        return $this;
    }

    public function setOrder(int $order): SidebarDefinition
    {
        $this->options['order'] = $order;

        return $this;
    }

    public function getOrder(): ?int
    {
        return $this->options['order'];
    }

    public function getTranslationDomain(): string
    {
        return $this->options['translation_domain'];
    }
    
    public function hasDivider(): bool 
    {
        return $this->options['divider'];
    }
}
