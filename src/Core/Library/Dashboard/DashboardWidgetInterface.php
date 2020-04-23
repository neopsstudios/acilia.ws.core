<?php

namespace WS\Core\Library\Dashboard;

interface DashboardWidgetInterface
{
    public function getId(): string;

    public function getOrder(): int;

    public function getTemplate(): string;

    public function getData(): array;
}
