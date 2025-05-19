<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', null, [
                'label' => "Nom d'utilisateur",
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => "Merci d'entrer un nom d'utilisateur.",
                    ]),
                ],
            ])

            ->add('email', null, [
                'label' => "Adresse e-mail",
                'attr' => [
                    'placeholder' => "Entrez votre adresse e-mail",
                ],
                'help' => "Nous ne partagerons jamais votre adresse e-mail avec d'autres personnes.",
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => "Merci d'entrer une adresse e-mail.",
                    ]),
                    new Email([
                        'message' => "L'adresse '{{ value }}' n'est pas valide.",
                    ]),
                ],
            ])
            
            ->add('plainPassword', PasswordType::class, [
                'label' => 'Mot de passe',
                'mapped' => false,
                'required' => true,
            ])
            ->add('confirmPlainPassword', PasswordType::class, [
                'label' => 'Confirmez le mot de passe',
                'mapped' => false,
                'required' => true,
            ])

            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'Accepter les conditions',
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => "Vous devez accepter les conditions.",
                    ]),
                ],
            ])
        ;

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            $password = $form->get('plainPassword')->getData();
            $confirm = $form->get('confirmPlainPassword')->getData();

            if ($password !== $confirm) {
                $form->get('confirmPlainPassword')->addError(new FormError('Les mots de passe ne correspondent pas.'));
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class, // ou null si tu n'utilises pas d'entité
        ]);
    }
}
