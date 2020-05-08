<?php

namespace WS\Core\Twig\Extension;

use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;
use WS\Core\Service\SidebarService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class SidebarExtension extends AbstractExtension
{
    private $requestStack;
    private $sidebarService;
    private $securityChecker;

    public function __construct(RequestStack $requestStack, SidebarService $sidebarService, AuthorizationCheckerInterface $securityChecker = null)
    {
        $this->requestStack = $requestStack;
        $this->sidebarService = $sidebarService;
        $this->securityChecker = $securityChecker;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('ws_cms_sidebar_get', [$this, 'getSidebar']),
            new TwigFunction('ws_cms_sidebar_is_granted', [$this, 'isGranted']),
            new TwigFunction('ws_cms_in_route', [$this, 'checkIfInRoute'], ['is_safe' => ['html']]),
        ];
    }

    public function getSidebar(): array
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

    public function checkIfInRoute($routePrefix, $class = 'active', $condition = null, $routeParameters = [])
    {
        if (! is_array($routePrefix)) {
            $routePrefix = [$routePrefix];
        }

        if ($this->requestStack->getMasterRequest() instanceof Request) {
            foreach ($routePrefix as $route) {
                if (strpos($this->requestStack->getMasterRequest()->get('_route'), $route) === 0) {
                    if ($condition === false) {
                        return '';
                    }

                    if ($routeParameters) {
                        $routeParams = $this->requestStack->getMasterRequest()->get('_route_params');

                        foreach ($routeParameters as $k => $v) {
                            if (!isset($routeParams[$k]) || $routeParams[$k] != $v) {
                                return '';
                            }
                        }
                    }

                    return $class;
                }
            }
        }

        return '';
    }
}
