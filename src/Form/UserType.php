<?php

namespace App\Form;

use App\Entity\Offre;
use App\Entity\Statut;
use App\Entity\TypeContrat;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('email')
            ->add('entreprise')

            ->add('date_naissance', DateType::class, [
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer une date de naissance.',
                    ]),
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
                /* new Assert\Range([
                    'min' => 10,
                    'max' => 10,
                    'notInRangeMessage' => 'La valeur doit être exactement 10.',
                ]), */
                /* 'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Le numéro de téléphone ne peut pas être vide.',
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^(?:(?:+|00)33|0)\s[1-9](?:[\s.-]\d{2}){4}$/',
                        'message' => 'Le numéro de téléphone doit être un numéro français valide.',
                    ]),
                ], */
            
        
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
