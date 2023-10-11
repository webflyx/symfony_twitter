<?php

namespace App\Form;

use DateTime;
use App\Entity\UserProfile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class UserProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('bio', TextareaType::class, [
                'attr' => [
                    'class' => 'tinymce',
                    'rows' => 3
                ],
            ])
            ->add('websiteUrl')
            ->add('twitterUsername')
            ->add('company')
            ->add('location')
            ->add(
                'dateOfBirth',
                DateType::class,
                [
                    'widget' => 'single_text',
                    'required' => false
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserProfile::class,
        ]);
    }
}
