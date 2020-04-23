<?php

namespace WS\Core\Library\Publishing;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use WS\Core\Library\Form\DateTimePickerType;

trait PublishingFormTrait
{
    protected function addPublishingFields(FormBuilderInterface $builder)
    {
        $publishingOptions = [
            'publishing.publishStatus.published.label' => PublishingEntityInterface::STATUS_PUBLISHED,
            'publishing.publishStatus.unpublished.label' => PublishingEntityInterface::STATUS_UNPUBLISHED,
            'publishing.publishStatus.draft.label' => PublishingEntityInterface::STATUS_DRAFT
        ];

        $builder
            ->add('publishStatus', ChoiceType::class, [
                'translation_domain' => 'ws_cms',
                'label' => 'publishing.publishStatus.label',
                'choices' => $publishingOptions,
                'attr' => [
                    'data-component' => 'ws_select'
                ],
            ])
            ->add('publishSince', DateTimePickerType::class, [
                'translation_domain' => 'ws_cms',
                'label' => 'publishing.publishSince.label'
            ])
            ->add('publishUntil', DateTimePickerType::class, [
                'translation_domain' => 'ws_cms',
                'label' => 'publishing.publishUntil.label'
            ]);
    }
}
