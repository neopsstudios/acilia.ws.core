<?php

namespace WS\Core\Library\FactoryService;

interface FactoryServiceInterface
{
    public function getSupported() : array;

    public function getAvailableByIds(array $ids) : array;
}
