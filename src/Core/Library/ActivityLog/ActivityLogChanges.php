<?php

namespace WS\Core\Library\ActivityLog;

class ActivityLogChanges 
{
    protected $field;
    protected $before;
    protected $after;

    public function __construct(string $field, $before, $after)
    {
        $this->field = $field;
        $this->before = $before;
        $this->after = $after;   
    }

    public function getField()
    {
        return $this->field;
    }

    public function getBefore()
    {
        return $this->before;
    }

    public function getAfter()
    {
        return $this->after;
    }
}
