<?php

namespace WS\Core\EventListener;

use Symfony\Component\HttpKernel\Event\ResponseEvent;
use WS\Core\Service\ContextService;

class ResponseListener
{
    protected $contextService;

    public function __construct(ContextService $contextService)
    {
        $this->contextService = $contextService;
    }

    public function onResponse(ResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        // CMS customs
        if ($this->contextService->isCMS()) {
            $event->getResponse()->setCache([
                'private' => true
            ]);
            $event->getResponse()->headers->addCacheControlDirective('no-store');
        }

        // Generic tweaks
        $event->getResponse()->headers->set('X-Powered-By', 'WideStand by Acilia');
    }
}
