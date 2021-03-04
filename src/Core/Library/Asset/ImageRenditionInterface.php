<?php

namespace WS\Core\Library\Asset;

interface ImageRenditionInterface
{
    /**
     * @return RenditionDefinition[]
     */
    public function getRenditionDefinitions(): array;
}
