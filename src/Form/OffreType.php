<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Distanciel;
use App\Entity\Offre;
use App\Entity\TypeContrat;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Mime\Part\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class OffreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('description')
            ->add('Lieu')
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
            ->add('type_contrat', EntityType::class, [
                'class' => TypeContrat::class,
                'choice_label' => 'nom',
            ])
            ->add('duree')
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'nom',
            ])
            ->add('salaire')
            ->add('distanciel', EntityType::class, [
                'class' => Distanciel::class,
                'choice_label' => 'distanciel',
            ])
            ->add('competences')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offre::class,
        ]);
    }
}
