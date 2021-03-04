<?php

namespace WS\Core\Library\Setting;

use WS\Core\Library\Setting\Definition\Section;

interface SettingDefinitionInterface
{
    const SETTING_TEXT = 'text';
    const SETTING_BOOLEAN = 'boolean';
    const SETTING_TEXTAREA = 'textarea';
    const SETTING_MULTIPLE = 'multiple';

    /**
     * @return Section[]
     */
    public function getSettingsDefinition() : array;
}
