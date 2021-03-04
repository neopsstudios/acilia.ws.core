<?php

namespace WS\Core\Library\Alert;

class AlertMessage
{
    protected $message;
    protected $iconClass;
    protected $routeName;
    protected $routeOptions;

    public function __construct($message, $iconClass = null, $routeName = null, $routeOptions = [])
    {
        $this->message = $message;
        $this->iconClass = $iconClass;
        $this->routeName = $routeName;
        $this->routeOptions = $routeOptions;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getIconClass()
    {
        return $this->iconClass;
    }

    public function getRouteName()
    {
        return $this->routeName;
    }

    public function getRouteOptions()
    {
        return $this->routeOptions;
    }
}
