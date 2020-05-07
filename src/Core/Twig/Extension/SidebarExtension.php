<?php

namespace WS\Core\Twig\Extension;

use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;
use WS\Core\Service\SidebarService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class SidebarExtension extends AbstractExtension
{
    private $sidebarService;
    private $securityChecker;

    public function __construct(SidebarService $sidebarService, AuthorizationCheckerInterface $securityChecker = null)
    {
        $this->sidebarService = $sidebarService;
        $this->securityChecker = $securityChecker;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('ws_sidebar_get', [$this, 'getSidebar']),
            new TwigFunction('ws_sidebar_is_granted', [$this, 'isGranted']),
        ];
    }

    public function getSidebar() : array
    {
        return $this->sidebarService->getSidebar();
    }

    public function isGranted(array $roles): bool
    {
        if (null === $this->securityChecker) {
            return false;
        }

        try {
            array_walk($roles, function(&$value) {
                $value = sprintf('is_granted(\'%s\')', $value);
            });

            return $this->securityChecker->isGranted(new Expression(implode(' or ', $roles)));

        } catch (AuthenticationCredentialsNotFoundException $e) {
            return false;
        }
    }
}
