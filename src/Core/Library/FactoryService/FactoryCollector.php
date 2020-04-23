<?php

namespace WS\Core\Library\FactoryService;

use WS\Core\Service\FactoryService;

class FactoryCollector
{
    protected $factoryService;
    protected $collect;

    public function __construct(FactoryService $factoryService)
    {
        $this->factoryService = $factoryService;
        $this->collect = [];
    }

    public function add(string $className, array $data) : void
    {
        if (! $this->factoryService->isSupported($className)) {
            throw new \Exception(sprintf('Service in Factory Service for class "%s" was not registered', $className));
        }

        foreach ($data as $objectId) {
            $this->collect[$className][$objectId] = $objectId;
        }
    }

    public function fetch()
    {
        return $this->factoryService->fetch($this->collect);
    }
}
