<?php

namespace WS\Core\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Routing\Route;
use WS\Core\Library\Navigation\NavigationProviderInterface;
use WS\Core\Library\Navigation\ResolvedPath;

class NavigationService
{
    protected $parameterBag;
    protected $providers = [];
    protected $navigations = [];
    protected $routes = [];

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
        $cacheFile = $this->parameterBag->get('kernel.cache_dir') . '/ws_navigation_routes.php';
        if (file_exists($cacheFile) && is_readable($cacheFile)) {
            $this->routes = include($cacheFile);
        }
    }

    public function addProvider(NavigationProviderInterface $provider): void
    {
        $this->providers[] = $provider;
    }

    public function addNavigation($name, $route): void
    {
        $this->navigations[$name] = $route;
    }

    public function compileNavigations(): void
    {
        $routes = [];

        /** @var Route $route  */
        foreach ($this->navigations as $navigationName => $route) {
            $routes[$navigationName] = [
                'controller' => $route->getDefault('_controller'),
                'path' => $route->getPath()
            ];
        }

        $cacheFile = $this->parameterBag->get('kernel.cache_dir') . '/ws_navigation_routes.php';
        file_put_contents($cacheFile, '<?php return ' . var_export($routes, true) . ';');
    }

    public function hasRoute($name): bool
    {
        return array_key_exists($name, $this->routes);
    }

    public function generateRoute($name, $parameters): ?string
    {
        $path = null;

        if (isset($this->routes[$name])) {
            $path = $this->routes[$name]['path'];
            foreach ($parameters as $key => $value) {
                $placeholder = sprintf('{%s}', $key);
                $value = rawurlencode($value);

                if (strpos($path, $placeholder) !== false) {
                    $path = str_replace($placeholder, $value, $path);
                    unset($parameters[$key]);
                }
            }

            if (count($parameters) > 0) {
                $path = $path . '?' . http_build_query($parameters);
            }
        }

        return $path;
    }

    public function getRouteController($name)
    {
        $controller = null;

        if (isset($this->routes[$name])) {
            $controller = $this->routes[$name]['controller'];
        }

        return $controller;
    }

    public function resolvePath($path): ?array
    {
        $attributes = null;
        $resolvedPath = null;

        /** @var NavigationProviderInterface $provider */
        foreach ($this->providers as $provider) {
            $resolvedPath = $provider->resolveNavigationPath($path);
            if ($resolvedPath instanceof  ResolvedPath) {
                break;
            }
        }

        if ($resolvedPath instanceof  ResolvedPath && $this->hasRoute($resolvedPath->getName())) {
            $attributes = $resolvedPath->getAttributes();
            $attributes['_controller'] = $this->getRouteController($resolvedPath->getName());
        }

        return $attributes;
    }

}
