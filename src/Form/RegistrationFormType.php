<?php

namespace App\Form;

use App\Entity\Statut;
use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\DateType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;


class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('statut', EntityType::class, [
            'class' => Statut::class,
            'choice_label' => 'nom',
        ])
        ->add('nom')
        ->add('prenom')
        ->add('email')
        ->add('entreprise')
        ->add('url')
        ->add('plainPassword', PasswordType::class, [
            // instead of being set onto the object directly,
            // this is read and encoded in the controller
            'mapped' => false,
            'attr' => ['autocomplete' => 'new-password'],
            'constraints' => [
                new NotBlank([
                    'message' => 'Please enter a password',
                ]),
                new Length([
                    'min' => 1,
                    'minMessage' => 'Your password should be at least {{ limit }} characters',
                    // max length allowed by Symfony for security reasons
                    'max' => 4096,
                ]),
            ],
        ])
        ->add('date_naissance', DateType::class, [
            'widget' => 'single_text',
            'required' => false,
            'constraints' => [
                new LessThanOrEqual([
                    'value' => new \DateTime(), // La date actuelle
                    'message' => 'La date de naissance ne peut pas être dans le futur.',
                ]),
            ],
        ])
        ->add('Adresse')
        ->add('tel', TelType::class, [
            'label' => 'Numéro de téléphone',
            'required' => true,
            'attr' => [
                'placeholder' => '01 23 45 67 89',
                'minlenght' => 10,
                'maxlength' => 15, // Limite d'affichage du nombre de caractères
            ],
            'constraints' => [
                new Assert\NotBlank([
                    'message' => 'Ce champ ne peut pas être vide.',
                ]),
                new Assert\Length([
                    'min' => 10,
                    'max' => 15,
                    'maxMessage' => 'Ce champ ne peut pas contenir plus de {{ limit }} caractères.',
                ]),
            ],
        ])
        ->add('logo', FileType::class, [
            'label' => 'Logo',
            'mapped' => false,
            'required' => false, 
            'constraints' => [
                new Assert\File([
                    'maxSize' => '2M',
                    'mimeTypes' => [
                        'image/jpeg',
                        'image/png',
                        'image/gif',
                        'image/webp',
                    ],
                    'mimeTypesMessage' => 'Veuillez télécharger une image valide (JPEG, PNG, GIF ou WebP)',
                ])
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
