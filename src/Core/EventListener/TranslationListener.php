<?php

namespace WS\Core\EventListener;

use WS\Core\Service\ContextService;
use WS\Core\Service\TranslationService;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class TranslationListener
{
    protected $translator;
    protected $contextService;
    protected $translationService;

    public function __construct(TranslatorInterface $translator, ContextService $contextService, TranslationService $translationService)
    {
        $this->translator = $translator;
        $this->contextService = $contextService;
        $this->translationService = $translationService;
    }

    public function onRequest(RequestEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        if ($this->contextService->isCMS()) {
            return;
        }

        $catalogue = $this->translator->getCatalogue($this->contextService->getDomain()->getLocale());
        $this->translationService->fillCatalogue($catalogue);
    }
}
