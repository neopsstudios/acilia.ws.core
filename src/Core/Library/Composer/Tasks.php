<?php

namespace WS\Core\Library\Composer;

class Tasks extends CommonTasks
{
    public static function getAssetsSource()
    {
        return realpath(__DIR__ . '/../../Resources/assets');
    }

    public static function getAssetsTarget()
    {
        return 'assets/ws/core';
    }
}