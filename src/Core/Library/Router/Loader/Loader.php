<?php


namespace WS\Core\Library\Router\Loader;

use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

class Loader
{
    private $localizationStrategy;

    public function __construct(LocalizationStrategyInterface $localizationStrategy)
    {
        $this->localizationStrategy = $localizationStrategy;
    }

    public function getParameters(RequestContext $context)
    {
        return $this->localizationStrategy->getParameters($context);
    }

    public function load(RouteCollection $collection)
    {
        // Process routes and create new translated routes
        foreach ($collection->all() as $name => $route) {
            $routeOptions = $route->getOptions();

            if (isset($routeOptions['i18n'])) {
                $collection->remove($name);
                $i18nOptions = $routeOptions['i18n'];
                unset($routeOptions['i18n']);

                foreach ($this->localizationStrategy->getLocales() as $locale) {
                    $i18nRoute = clone $route;
                    $i18nRoute->setOptions($routeOptions);

                    if (isset($i18nOptions[$locale])) {
                        $i18nRoute->setPath($i18nOptions[$locale]);
                    }

                    $this->localizationStrategy->localize($locale, $i18nRoute);

                    $collection->add(sprintf('%s/%s', $name, $locale), $i18nRoute);
                }
            }
        }

        return $collection;
    }
}
