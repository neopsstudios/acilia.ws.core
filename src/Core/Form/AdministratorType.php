<?php

namespace WS\Core\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use WS\Core\Entity\Administrator;
use WS\Core\Library\Form\ToggleChoiceType;
use WS\Core\Service\Entity\AdministratorService;

class AdministratorType extends AbstractType
{
    protected $administratorService;
    protected $encoder;

    /**
     * AdministratorType constructor.
     *
     */
    public function __construct(AdministratorService $administratorService, UserPasswordEncoderInterface $encoder)
    {
        $this->administratorService = $administratorService;
        $this->encoder = $encoder;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'form.name.label',
                'attr' => [
                    'placeholder' => 'form.name.placeholder',
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'form.email.label',
                'attr' => [
                    'placeholder' => 'form.email.placeholder',
                ],
            ])
            ->add('password', RepeatedType::class, [
                'label' => 'form.password.label',
                'type' => PasswordType::class,
                'invalid_message' => 'form.repeat_password.invalid',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => !$options['edit'],
                'first_options' => ['label' => 'form.password.label'],
                'second_options' => ['label' => 'form.repeat_password.label'],
                'mapped' => false,
            ])
            ->add('profile', ChoiceType::class, [
                'choice_translation_domain' => 'cms',
                'choices' => $this->administratorService->getFormProfiles(),
                'label' => 'form.profile.label',
            ])
            ->add('active', ToggleChoiceType::class, [
                'label' => 'form.active.label',
                'choices' => [
                    'status.inactive' => 0,
                    'status.active' => 1,
                ],
                'row_attr' => [
                    'class' => 'l-form__item--medium',
                ],
            ])
        ;

        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                /** @var Administrator $administrator */
                $administrator = $event->getForm()->getData();

                $newPassword = $event->getForm()->get('password')->getData();
                if (!empty($newPassword)) {
                    $newPassword = $this->encoder->encodePassword($administrator, $newPassword);
                    $administrator->setPassword($newPassword);
                }
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Administrator::class,
            'edit' => false,
            'attr' => [
                'novalidate' => 'novalidate',
                'autocomplete' => 'off',
                'accept-charset' => 'UTF-8',
            ],
        ]);
    }
}
