<?php

namespace WS\Core\Library\Alert;

class AlertMessage
{
    protected $message;
    protected $iconClass;
    protected $routeName;

    public function __construct($message, $iconClass = null, $routeName = null)
    {
        $this->message = $message;
        $this->iconClass = $iconClass;
        $this->routeName = $routeName;
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
}
