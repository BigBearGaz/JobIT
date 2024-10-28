<?php

namespace App\Controller\Admin;

use App\Entity\Statut;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityManagerInterface;


class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    /* private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function configureFields(string $pageName): iterable
    {
    $statutRepository = $this->entityManager->getRepository(Statut::class);
    $statuts = $statutRepository->findAll();
    
    return [
        TextField::new('nom'),
        ChoiceField::new('statut')
            ->setChoices(array_combine(
                array_map(fn($statut) => $statut->getNom(), $statuts), // Labels
                $statuts // Valeurs
            ))
            ->setFormType(EntityType::class)
            ->setFormTypeOptions([
                'class' => Statut::class,
                'choice_label' => 'nom', // Propriété de l'entité à afficher
            ]),
        TextEditorField::new('prenom'),
    ];
    } */

}
