<?php

namespace WS\Core\Library\Publishing;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use WS\Core\Library\Form\DateTimePickerType;

trait PublishingFormTrait
{
    protected function addPublishingFields(FormBuilderInterface $builder)
    {
        $this->addPublishingFieldStatus($builder, true);
        $this->addPublishingFieldDates($builder);
    }

    protected function addPublishingFieldStatus(FormBuilderInterface $builder, bool $required)
    {
        $publishingOptions = [
            'publishing.publishStatus.draft.label' => PublishingEntityInterface::STATUS_DRAFT,
            'publishing.publishStatus.published.label' => PublishingEntityInterface::STATUS_PUBLISHED,
            'publishing.publishStatus.unpublished.label' => PublishingEntityInterface::STATUS_UNPUBLISHED
        ];

        $builder
            ->add('publishStatus', ChoiceType::class, [
                'translation_domain' => 'ws_cms',
                'label' => 'publishing.publishStatus.label',
                'choices' => $publishingOptions,
                'required' => $required,
                'attr' => [
                    'data-component' => 'ws_select'
                ],
            ])
        ;
    }

    protected function addPublishingFieldDates(FormBuilderInterface $builder)
    {
        $builder
            ->add('publishSince', DateTimePickerType::class, [
                'translation_domain' => 'ws_cms',
                'label' => 'publishing.publishSince.label',
                'attr' => [
                    'placeholder' => 'publishing.publishSince.placeholder'
                ]
            ])
            ->add('publishUntil', DateTimePickerType::class, [
                'translation_domain' => 'ws_cms',
                'label' => 'publishing.publishUntil.label',
                'attr' => [
                    'placeholder' => 'publishing.publishUntil.placeholder'
                ]
            ]);
    }
}
