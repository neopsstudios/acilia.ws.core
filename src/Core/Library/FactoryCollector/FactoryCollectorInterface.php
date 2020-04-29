<?php

namespace WS\Core\Library\FactoryCollector;

interface FactoryCollectorInterface
{
    public function getFactoryCollectorSupported(): array;

    public function getAvailableByIds(array $ids): array;
}
