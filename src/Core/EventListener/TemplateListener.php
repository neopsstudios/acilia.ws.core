<?php

namespace WS\Core\EventListener;

use WS\Core\Service\ContextService;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Twig\Environment;

class TemplateListener
{
    protected $twigEnvironment;
    protected $parameterBagInterface;
    protected $contextService;

    public function __construct(Environment $twigEnvironment, ParameterBagInterface $parameterBagInterface, ContextService $contextService)
    {
        $this->twigEnvironment = $twigEnvironment;
        $this->parameterBagInterface = $parameterBagInterface;
        $this->contextService = $contextService;
    }

    public function setupTemplate(RequestEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        // Setup Twig paths for the current context
        $twigPaths = $this->twigEnvironment->getLoader()->getPaths();

        $newPath = sprintf(
            '%s/templates/%s',
            $this->parameterBagInterface->get('kernel.project_dir'),
            $this->contextService->getTemplatesBase()
        );
        array_unshift($twigPaths, $newPath);

        $this->twigEnvironment->getLoader()->setPaths($twigPaths);
    }
}
