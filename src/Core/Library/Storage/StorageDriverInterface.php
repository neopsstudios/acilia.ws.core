<?php

namespace WS\Core\Library\Storage;

interface StorageDriverInterface
{
    public function save($resource, $context);

    public function get($resource, $context);
}
