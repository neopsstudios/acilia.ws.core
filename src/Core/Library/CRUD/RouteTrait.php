<?php

namespace WS\Core\Library\CRUD;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

trait RouteTrait
{
    protected function getRouteNamePrefix() : string
    {
        $controllerClass = get_class($this);
        $classPath = explode('\\', $controllerClass);

        if ($classPath[0] === 'WS') {
            $controllerName = strtolower(str_replace('Controller', '', $classPath[4]));
            $prefix = sprintf('ws_cms_%s_%s', $classPath[1], $controllerName);
        } elseif ($classPath[0] === 'App') {
            $controllerName = str_replace('Controller', '', $classPath[count($classPath) - 1]);
            $controllerWords = preg_split('/(?=[A-Z])/', $controllerName, -1, PREG_SPLIT_NO_EMPTY);
            $controllerPrefix = implode('_', $controllerWords);

            $prefix = sprintf('cms_%s', $controllerPrefix);
        } else {
            throw new \Exception(sprintf('Unable to calculate Route Name Prefix for Controller: %s', $controllerClass));
        }

        return strtolower($prefix);
    }

    protected function generateUrl(string $route, array $parameters = [], int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH): string
    {
        $route = $this->container->get('router')->getRoute($route);

        $request = $this->container->get('request_stack')->getCurrentRequest();
        foreach ($request->attributes->get('_route_params') as $param => $value) {
            if (preg_match(sprintf('/{%s}/', $param), $route->getPath())) {
                if (!isset($parameters[$param])) {
                    $parameters[$param] = $value;
                }
            }
        }

        return $this->container->get('router')->generate($route, $parameters, $referenceType);
    }
}
