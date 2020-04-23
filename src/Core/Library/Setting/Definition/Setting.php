<?php

namespace WS\Core\Library\Setting\Definition;

class Setting
{
    protected $code;
    protected $name;
    protected $type;
    protected $value;
    protected $options;
    protected $group;

    public function __construct(string $code, string $name, string $type, array $options = [])
    {
        $this->code = $code;
        $this->name = $name;
        $this->type = $type;
        $this->value = null;

        $this->options = array_merge([
            'description' => '',
            'placeholder' => '',
            'translation_domain' => 'ws_cms_setting',
            'required' => false,
            'default' => null,
            'options' => []
        ], $options);
    }

    public function getCode() : string
    {
        return $this->code;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function isRequired(): bool
    {
        return $this->options['required'];
    }

    public function getTranslationDomain(): string
    {
        return $this->options['translation_domain'];
    }

    public function getDescription(): string
    {
        return $this->options['description'];
    }

    public function getValue()
    {
        if ($this->value === null) {
            return $this->options['default'];
        }

        return $this->value;
    }

    public function setValue($value)
    {
        return $this->value = $value;
    }

    public function getPlaceholder() : string
    {
        return $this->options['placeholder'];
    }

    public function getDefault()
    {
        return $this->options['default'];
    }

    public function getOptions(): array
    {
        return $this->options['options'];
    }
}
