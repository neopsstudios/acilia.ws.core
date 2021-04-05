<?php

namespace WS\Core\Library\Asset\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Exception\InvalidConfigurationException;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use WS\Core\Entity\AssetFile;

class AssetFileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['ws']['entity'] === null) {
            throw new InvalidConfigurationException('The options "ws[entity]" is required.');
        }

        $builder
            ->add('asset', FileType::class, [
                'constraints' => $options['ws']['constraints'] ?? null,
                'mapped' => $options['mapped'] ?? false,
                'required' => $options['required'] ?? false,
            ])
            ->add('asset_remove', HiddenType::class)
        ;

        $assetFileOptions = [
            'class' => AssetFile::class
        ];

        try {
            // get asset file from entity
            $fieldGetter = sprintf('get%s', ucfirst($builder->getName()));
            if (method_exists($options['ws']['entity'], $fieldGetter)) {
                $ref = new \ReflectionMethod(get_class($options['ws']['entity']), $fieldGetter);
                $asset = $ref->invoke($options['ws']['entity']);
                if ($asset instanceof AssetFile) {
                    $assetFileOptions['data'] = $asset;
                }
            }
        } catch (\Exception $e) {}

        $builder->add('asset_file', EntityType::class, $assetFileOptions);

        /*$builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $assetFile = $event->getForm()->get('asset_file');
            $submittedData = $event->getData();

            if (!array_key_exists('asset_file', $submittedData) && $assetFile->getData() instanceof AssetFile) {
                $submittedData['asset_file'] = $assetFile->getData()->getId();
                $event->setData($submittedData);
            }
        });*/


    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars = array_replace($view->vars, [
            'ws' => [
                'entity' => $options['ws']['entity'],
                'constraints' => $options['ws']['constraints'],
            ],
            'type' => 'ws-asset-file',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'compound' => true,
            'mapped' => false,
            'ws' => [
                'entity' => null,
                'constraints' => []
            ]
        ]);
    }
}
