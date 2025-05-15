<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\User;

class UserAvatarForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $avatars = $options['avatars']; // tableau de strings : ['Cat.svg', 'Dog.svg', ...]

        $choices = [];
        foreach ($avatars as $avatar) {
            $choices[$avatar] = $avatar; // clé = label, valeur = filename
        }

        $builder
            ->add('avatar', ChoiceType::class, [
                'choices' => $choices,
                'label' => 'Choisir une avatar de profil',
                'expanded' => true, // rendra des radios
                'multiple' => false,
                'choice_attr' => function ($choice, $key, $value) {
                    return ['data-avatar' => '/uploads/avatars/' . $value];
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'avatars' => [],
        ]);
    }
}

