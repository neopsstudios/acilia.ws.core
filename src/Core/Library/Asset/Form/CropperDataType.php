<?php

namespace WS\Core\Library\Asset\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class CropperDataType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (!is_array($options['ws-ratios'])) {
            throw new \Exception('The options "ws-ratios" is required.');
        }

        foreach ($options['ws-ratios'] as $ratio => $minimum) {
            $builder->add($ratio, HiddenType::class, [
                'attr' => [
                    'data-ratio' => str_replace(':', 'x', $ratio)
                ]
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'compound' => true,
            'mapped' => false,
            'ws-ratios' => []
        ]);
    }
}
