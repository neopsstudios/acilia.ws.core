<?php

namespace WS\Core\Twig\Extension;

use WS\Core\Service\Entity\AdministratorService;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\TwigFilter;
use Twig\Extension\AbstractExtension;

class AdministratorExtension extends AbstractExtension
{
    protected $administratorService;
    protected $translator;

    public function __construct(AdministratorService $administratorService, TranslatorInterface $translator)
    {
        $this->administratorService = $administratorService;
        $this->translator = $translator;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('ws_cms_administrator_profile', [$this, 'getProfile']),
        ];
    }

    public function getProfile(?string $profile): string
    {
        return $this->translator->trans($this->administratorService->getProfileLabel($profile), [], 'cms');
    }
}
