<?php

namespace WS\Core\Library\Asset;

use WS\Core\Service\ImageService;

trait ImageConsumerTrait
{
    protected $imageService;

    public function setImageService(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function getImageService(): ImageService
    {
        return $this->imageService;
    }
}
