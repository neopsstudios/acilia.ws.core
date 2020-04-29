<?php

namespace WS\Core\Library\Publishing;

interface PublishingEntityInterface
{
    const STATUS_PUBLISHED = 'published';
    const STATUS_UNPUBLISHED = 'unpublished';
    const STATUS_DRAFT = 'draft';

    const FILTER_STATUS = 'ws_cms_publishing_status';

    public function getPublishStatus(): ?string;

    public function setPublishStatus(?string $publishStatus): self;

    public function getPublishSince(): ?\DateTimeInterface;

    public function setPublishSince(?\DateTimeInterface $publishSince): self;

    public function getPublishUntil(): ?\DateTimeInterface;

    public function setPublishUntil(?\DateTimeInterface $publishUntil): self;
}
