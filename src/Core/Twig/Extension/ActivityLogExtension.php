<?php

namespace WS\Core\Twig\Extension;

use WS\Core\Service\ActivityLogService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twig\TwigFilter;
use Twig\TwigTest;

class ActivityLogExtension extends AbstractExtension
{
    private $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('ws_activity_log_enabled', [$this, 'isEnabled']),
        ];
    }

    public function getFilters()
    {
        return [
            new TwigFilter('ws_activity_log_model', [$this, 'printModel']),
            new TwigFilter('ws_activity_log_action', [$this, 'printActionClass'])
        ];
    }

    public function getTests()
    {
        return [
            new TwigTest('ws_activity_log_selected', [$this, 'selected']),
        ];
    }

    public function isEnabled()
    {
        return $this->activityLogService->isEnabled();
    }

    public function printModel(string $modelName)
    {
        $classPath = explode('\\', $modelName);

        return $classPath[count($classPath) -1];
    }

    public function printActionClass(string $action)
    {
        switch ($action) {
            case 'create':
                return 'success';
            case 'update':
                return 'info';
            default:
                return 'danger';
        }
    }

    public function selected($value, $filter, $key)
    {
        if (isset($filter[$key]) && $filter[$key] == $value) {
            return true;
        }

        return false;
    }
}
