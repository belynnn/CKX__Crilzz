<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;

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

            ->add('isAnonymous', CheckboxType::class, [
                'label' => 'Je suis anonyme',
                'required' => false,
            ])
            
            ->add('plainPassword', PasswordType::class, [
                'label' => 'Mot de passe',
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => "Merci d'entrer un mot de passe",
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => "Votre mot de passe doit contenir au moins {{ limit }} caractères",
                        'max' => 300,
                    ]),
                ],
            ])

            ->add('plainPasswordConfirm', PasswordType::class, [
                'label' => 'Confirmer le mot de passe',
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de confirmer votre mot de passe.',
                    ]),
                ],
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
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
