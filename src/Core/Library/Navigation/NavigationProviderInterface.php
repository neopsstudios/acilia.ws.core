<?php

namespace WS\Core\Library\Navigation;

interface NavigationProviderInterface
{
    public function resolveNavigationPath($path): ?ResolvedPath;
}
