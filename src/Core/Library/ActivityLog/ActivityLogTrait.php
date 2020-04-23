<?php

namespace WS\Core\Library\ActivityLog;

use WS\Core\Library\CRUD\AbstractService;

trait ActivityLogTrait
{
    public function getActivityLogSupported(): string
    {
        if ($this instanceof AbstractService) {
            return $this->getEntityClass();
        }

        throw new \Exception(sprintf(
            'Your service "%s" must implement the method "getActivityLogSupported" or imlements the abstract service "WS\Core\Library\CRUD\AbstractService()"',
            get_called_class()
        ));
    }

    public function getActivityLogFields(): ?array
    {
        return null;
    }
}
