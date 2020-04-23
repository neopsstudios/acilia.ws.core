<?php

namespace WS\Core\EventListener;

use WS\Core\Service\ContextService;
use DeviceDetector\DeviceDetector;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class DeviceListener
{
    protected $enabled;
    protected $contextService;

    public function __construct($enabled, ContextService $contextService)
    {
        $this->enabled = $enabled;
        $this->contextService = $contextService;
    }

    public function setupDevice(RequestEvent $event): void
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        if ($this->contextService->isCMS()) {
            return;
        }

        if (!$this->enabled) {
            return;
        }

        $request = $event->getRequest();
        $userAgent = $request->headers->get('User-Agent', '');
        if ($userAgent === null) {
            return;
        }

        if (is_array($userAgent)) {
            $userAgent = $userAgent[0];
        }

        $detector = new DeviceDetector($userAgent);
        $detector->parse();
        $device = $detector->getDeviceName();

        $this->contextService->setDevice($device);
    }
}
