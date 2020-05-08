<?php

namespace WS\Core\Twig\Extension;

use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\TwigFilter;
use Twig\Extension\AbstractExtension;
use WS\Core\Library\Publishing\PublishingEntityInterface;

class PublishingExtension extends AbstractExtension
{
    protected $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function getFilters()
    {
        return [
            new TwigFilter(PublishingEntityInterface::FILTER_STATUS, [$this, 'getStatus']),
        ];
    }

    public function getStatus(?string $status): string
    {
        if ($status) {
            return $this->translator->trans(sprintf('publishing.publishStatus.%s.label', $status), [], 'ws_cms');
        }

        return '-';
    }
}
