<?php

namespace WS\Core\Service;

use WS\Core\Library\FactoryService\FactoryCollector;
use WS\Core\Library\FactoryService\FactoryServiceInterface;

class FactoryService
{
    protected $services = [];
    protected $supportedEntites = [];
    protected $collect = [];
    protected $objects = [];

    public function registerService(FactoryServiceInterface $service)
    {
        $this->services[] = $service;

        foreach ($service->getSupported() as $entityName) {
            $this->supportedEntites[] = $entityName;
        }
    }

    public function isSupported(string $className) : bool
    {
        return in_array($className, $this->supportedEntites);
    }

    public function getCollector()
    {
        return new FactoryCollector($this);
    }

    public function fetch(array $collection)
    {
        $objects = [];
        foreach ($collection as $className => $data) {
            $toCollect = [];
            foreach ($data as $objectId) {
                if (! isset($this->objects[$className][$objectId])) {
                    $toCollect[] = $objectId;
                } else {
                    $objects[$className][$objectId] = $this->objects[$className][$objectId];
                }
            }

            foreach ($this->services as $service) {
                if (in_array($className, $service->getSupported())) {
                    $collectedObjects = $service->getAvailableByIds($toCollect);
                    foreach ($collectedObjects as $objectId => $object) {
                        $this->objects[$className][$objectId] = $object;
                        $objects[$className][$objectId] = $object;
                    }

                    break;
                }
            }
        }

        return $objects;
    }
}
