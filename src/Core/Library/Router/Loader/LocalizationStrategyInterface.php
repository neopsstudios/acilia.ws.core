<?php

namespace WS\Core\Library\Router\Loader;

use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;

interface LocalizationStrategyInterface
{
    public function getLocales() : array;

    public function localize($locale, Route $route);

    public function getParameters(RequestContext $context): array;
}
