<?php

namespace WS\Core\Library\Router;

use Symfony\Bundle\FrameworkBundle\Routing\Router as BaseRouter;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use WS\Core\Library\Router\Loader\Loader;

class Router extends BaseRouter
{
    /** @var Loader */
    protected $loader;
    protected $defaultLocale;

    public function __construct()
    {
        call_user_func_array(array('Symfony\Bundle\FrameworkBundle\Routing\Router', '__construct'), func_get_args());
    }

    public function setLoader(Loader $loader)
    {
        $this->loader = $loader;
    }

    public function setDefaultLocale($locale)
    {
        $this->defaultLocale = $locale;
    }

    public function generate($name, $parameters = [], $referenceType = self::ABSOLUTE_PATH)
    {
        // determine the most suitable locale to use for route generation
        $locale = $this->getLocale($parameters);

        // define parameters based on loader needs
        $parameters = array_merge(
            $parameters,
            $this->loader->getParameters($this->context)
        );

        $generator = $this->getGenerator();

        try {
            // let symfony generate the route
            return $generator->generate($name, $parameters, $referenceType);
        } catch (RouteNotFoundException $e) {
        }

        // let ws generate the route
        return $generator->generate(sprintf('%s/%s', $name, $locale), $parameters, $referenceType);
    }

    public function getRouteCollection()
    {
        return $this->loader->load(parent::getRouteCollection());
    }

    protected function getLocale(array $parameters)
    {
        $currentLocale = $this->context->getParameter('_locale');
        if (isset($parameters['_locale'])) {
            $locale = $parameters['_locale'];
        } elseif ($currentLocale) {
            $locale = $currentLocale;
        } else {
            $locale = $this->defaultLocale;
        }

        return $locale;
    }

    public function getRoute(string $name)
    {
        return $this->getRouteCollection()->get($name);
    }
}
