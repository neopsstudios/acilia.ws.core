<?php

namespace WS\Core\Twig\Extension;

use WS\Core\Service\SidebarService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class SidebarExtension extends AbstractExtension
{
    protected $sidebarService;

    public function __construct(SidebarService $sidebarService)
    {
        $this->sidebarService = $sidebarService;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('get_sidebar', [$this, 'getSidebar']),
        ];
    }

    public function getSidebar() : array
    {
        return $this->sidebarService->getSidebar();
    }
}
