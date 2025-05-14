<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\User;

class UserImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $images = $options['images']; // tableau de strings : ['Cat.svg', 'Dog.svg', ...]

        $choices = [];
        foreach ($images as $image) {
            $choices[$image] = $image; // clé = label, valeur = filename
        }

        $builder
            ->add('avatar', ChoiceType::class, [
                'choices' => $choices,
                'label' => 'Choisir une image de profil',
                'expanded' => true, // rendra des radios
                'multiple' => false,
                'choice_attr' => function ($choice, $key, $value) {
                    return ['data-image' => '/uploads/avatars/' . $value];
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'images' => [],
        ]);
    }
}

