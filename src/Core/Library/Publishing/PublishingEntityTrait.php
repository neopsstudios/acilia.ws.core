<?php

namespace WS\Core\Library\Publishing;

trait PublishingEntityTrait
{
    public function getPublishStatus(): ?string
    {
        return $this->publishStatus;
    }

    public function setPublishStatus(?string $publishStatus): PublishingEntityInterface
    {
        $this->publishStatus = $publishStatus;

        return $this;
    }

    public function getPublishSince(): ?\DateTimeInterface
    {
        return $this->publishSince;
    }

    public function setPublishSince(?\DateTimeInterface $publishSince): PublishingEntityInterface
    {
        $this->publishSince = $publishSince;

        return $this;
    }

    public function getPublishUntil(): ?\DateTimeInterface
    {
        return $this->publishUntil;
    }

    public function setPublishUntil(?\DateTimeInterface $publishUntil): PublishingEntityInterface
    {
        $this->publishUntil = $publishUntil;

        return $this;
    }
}
