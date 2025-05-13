<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\{TextType, EmailType, PasswordType, SubmitType};
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Pseudo (username)
            ->add('username', TextType::class, [
                'required' => false,
                'label' => 'Nom d\'utilisateur',
            ])
            
            // Email
            ->add('email', EmailType::class, [
                'required' => false,
                'label' => 'Adresse email',
            ])
            
            // Nouveau mot de passe (optionnel)
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Nouveau mot de passe',
            ])
            
            // Confirmer le mot de passe (optionnel)
            ->add('plainPasswordConfirm', PasswordType::class, [
                'label' => 'Confirmer le mot de passe',
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'required' => false,  // Rendre ce champ non requis si le mot de passe n'est pas modifié
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de confirmer votre mot de passe.',
                    ]),
                ],
            ])
            
            // Bouton de mise à jour
            ->add('submit', SubmitType::class, [
                'label' => 'Mettre à jour mon profil',
                'attr' => ['class' => 'btn btn-primary mt-3'],
            ]);
        }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
