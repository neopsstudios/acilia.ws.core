<?php

namespace WS\Core\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use WS\Core\Entity\Administrator;

class AdministratorProfileType extends AbstractType
{
    protected $encoder;

    /**
     * AdministratorProfileType constructor.
     *
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'profile.form.name.label',
                'attr' => [
                    'placeholder' => 'profile.form.name.placeholder',
                ],
            ])
            ->add('password', PasswordType::class, [
                'label' => 'profile.form.password.label',
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'profile.form.password.placeholder',
                ],
            ])
            ->add('newPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'ws.cms.profile.form.second.new_password.error',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => false,
                'first_options' => [
                    'label' => 'profile.form.first.new_password.label',
                    'attr' => [
                        'placeholder' => 'profile.form.first.new_password.placeholder',
                    ],
                ],
                'second_options' => [
                    'label' => 'profile.form.second.new_password.label',
                    'attr' => [
                        'placeholder' => 'profile.form.second.new_password.placeholder',
                    ],

                ],
                'mapped' => false,
            ])
        ;

        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $administrator = $event->getForm()->getData();

                $currentPassword = $event->getForm()->get('password')->getData();
                $newPassword = $event->getForm()->get('newPassword')->getData();
                if (!empty($newPassword)) {
                    if (!$this->encoder->isPasswordValid($administrator, $currentPassword)) {
                        $event->getForm()->addError(new FormError('ws.cms.profile.form.password.missmatch.error'));
                        return;
                    }

                    if (strlen($newPassword) < 8) {
                        $event->getForm()->addError(new FormError('ws.cms.profile.form.password.length.error'));
                        return;
                    }

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
            'attr' => [
                'novalidate' => 'novalidate',
                'autocomplete' => 'off',
                'accept-charset' => 'UTF-8',
            ],
        ]);
    }
}
