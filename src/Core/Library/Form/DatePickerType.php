<?php

namespace WS\Core\Library\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class DatePickerType extends AbstractType
{
    protected $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'attr' => [
                'data-component' => 'ws_datepicker',
                'data-format' => 'date'
            ],
            'html5' => false,
            'widget' => 'single_text',
            'format' => $this->translator->trans('symfony_date_format', [], 'ws_cms'),
        ]);
    }

    public function getParent()
    {
        return DateTimeType::class;
    }
}
