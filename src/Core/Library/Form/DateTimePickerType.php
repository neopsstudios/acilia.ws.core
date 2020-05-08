<?php

namespace WS\Core\Library\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class DateTimePickerType extends AbstractType
{
    const DATE_TIME_PICKER_ATTR = [
        'data-component' => 'ws_datepicker',
        'data-format' => 'date_hour'
    ];

    protected $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'attr' => self::DATE_TIME_PICKER_ATTR,
            'html5' => false,
            'widget' => 'single_text',
            'format' => $this->translator->trans('symfony_date_hour_format', [], 'ws_cms'),
        ]);
    }

    public function getParent()
    {
        return DateTimeType::class;
    }
}
