<?php

namespace WS\Core\Library\ActivityLog;

interface ActivityLogInterface
{
    const UPDATE = 'update';
    const CREATE = 'create';
    const DELETE = 'delete';

    public function getActivityLogSupported(): string;

    public function getActivityLogFields(): ?array;
}
