<?php

namespace WS\Core\Service;

use WS\Core\Library\Alert\GatherAlertsEvent;
use WS\Core\Library\Alert\AlertGathererInterface;

class AlertService
{
    protected $gatherers = [];
    protected $alerts = null;

    public function addGatherer(AlertGathererInterface $gatherer)
    {
        $this->gatherers[] = $gatherer;
    }

    public function getAlerts()
    {
        if ($this->alerts === null) {
            $event = new GatherAlertsEvent();
            /** @var AlertGathererInterface $gatherer */
            foreach ($this->gatherers as $gatherer) {
                $gatherer->gatherAlerts($event);
            }

            $this->alerts = $event->getAlerts();
        }

        return (array) $this->alerts;
    }
}
