<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Distanciel;
use App\Entity\Offre;
use App\Entity\Statut;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\User;
use App\Entity\TypeContrat;
use Doctrine\ORM\EntityManagerInterface;

class AppFixtures extends Fixture
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        
        // Création des statuts
        $statuts = [];
        $nomsStatut = ['Candidat', 'Recruteur'];
        foreach ($nomsStatut as $nom) {
        $statut = new Statut();
        $statut->setNom($nom);
        $manager->persist($statut);
        $statuts[$nom] = $statut; // Associe le nom au statut pour un accès facile
        }

        $candidat = $this->entityManager->getRepository(Statut::class)->findOneBy(['nom' => 'Candidat']);
        $recruteur = $this->entityManager->getRepository(Statut::class)->findOneBy(['nom' => 'Recruteur']);

        // Création de l'Admin
        $user = new User();
        $user->setNom('ADMIN');
        $user->setPrenom('Admin');
        $user->setEmail('john@gmail.com');
        $user->setRoles(["ROLE_ADMIN"]);
        $user->setPassword('$2y$10$I107u/gNZil0T0wlHNSnCuey1WUV2yc.mRX3Rf59rB2Dy4GdAU3Ky');
        $user->setAdresse($faker->address);
        $user->setTel('0672853981');
        $user->setPhoto($faker->imageUrl(200, 480));
        $user->setStatut($statuts['Recruteur']);
        $manager->persist($user);
        $usersRecruteurs[] = $user;
        


        // Création d'utilisateurs Recruteurs
        $usersRecruteurs = [];
        for ($i = 0; $i < 8; $i++) {
            $user = new User();
            /* $user->setNom($faker->lastName);
            $user->setPrenom($faker->firstName); */
            $user->setEntreprise($faker->firstName);
            $user->setUrl($faker->url);
            $user->setEmail($faker->email);
            $user->setRoles(["ROLE_USER"]);
            $user->setPassword('$2y$10$I107u/gNZil0T0wlHNSnCuey1WUV2yc.mRX3Rf59rB2Dy4GdAU3Ky');
            /* $user->setDateNaissance($faker->dateTimeThisCentury()); */
            $user->setAdresse($faker->address);
            $user->setTel('0672853981');
            $user->setPhoto($faker->imageUrl(200, 480));
            $user->setStatut($statuts['Recruteur']);
            $manager->persist($user);
            $usersRecruteurs[] = $user;
        }

        // Création d'utilisateurs Candidats
        $usersCandidats = [];
        for ($i = 0; $i < 8; $i++) {
            $user = new User();
            $user->setNom($faker->lastName);
            $user->setPrenom($faker->firstName);
            /* $user->setEntreprise($faker->firstName); */
            /* $user->setUrl($faker->url); */
            $user->setEmail($faker->email);
            $user->setRoles(["ROLE_USER"]);
            $user->setPassword('$2y$10$I107u/gNZil0T0wlHNSnCuey1WUV2yc.mRX3Rf59rB2Dy4GdAU3Ky');
            $user->setDateNaissance($faker->dateTimeThisCentury());
            $user->setAdresse($faker->address);
            $user->setTel('0672853981');
            $user->setPhoto($faker->imageUrl(200, 480));
            $user->setStatut($statuts['Candidat']);
            $manager->persist($user);
            $usersCandidats[] = $user;
        }

        // Création des types de contrats
        $contrats = [];
        $nomsContrats = ['CDD', 'CDI', 'Interim'];
        foreach ($nomsContrats as $nom) {
            $contrat = new TypeContrat();
            $contrat->setNom($nom);
            $manager->persist($contrat);
            $contrats[$nom] = $contrat; // Associe le nom au contrat pour un accès facile
        }

        // Création des catégories
        $categories = [];
        $nomsCategories = ['Développement Web', 'Développement Mobile', 'Intelligence Artificielle et Machine Learning', 'Big Data et Data Science', 'Cybersécurité', 'Cloud Computing et DevOps', 'UI/UX Design', 'Administration Systèmes et Réseaux', 'Support et Assistance Technique'];
        foreach ($nomsCategories as $nom) {
            $categorie = new Category();
            $categorie->setNom($nom);
            $manager->persist($categorie);
            $categories[] = $categorie;
        }

        // Création de distanciel
        $distanciels = [];
        $nomsDistanciel = ['FullRemote', 'Hybrid', 'Non'];
        foreach ($nomsDistanciel as $nom) {
            $distanciel = new Distanciel();
            $distanciel->setDistanciel($nom);
            $manager->persist($distanciel);
            $distanciels[$nom] = $distanciel;
        }


        // Création des Offres
        for ($i = 0; $i < 20; $i++) {
            $offre[$i] = new Offre();
            $offre[$i]->setTitre($faker->sentence($nbWords = 4, $variableNbWords = true));
            $offre[$i]->setDescription($faker->paragraph($nbSentences = 15, $variableNbSentences = true));
            
            // Sélection aléatoire d'un type de contrat
            $typeContrat = $faker->randomElement([$contrats['CDD'], $contrats['CDI'], $contrats['Interim']]);
            $offre[$i]->setTypeContrat($typeContrat);

            // Si le type de contrat n'est pas CDI, définir la durée
            if ($typeContrat->getNom() !== 'CDI') {
                $offre[$i]->setDuree($faker->numberBetween(1, 24) . ' mois');
            }

            $offre[$i]->setCategory($faker->randomElement($categories));
            $offre[$i]->setAuteur($faker->randomElement($usersRecruteurs));
            $datePublication = $faker->dateTimeThisYear;
            $offre[$i]->setDatePublication($datePublication);
            if ($faker->boolean(30)) {
                $dateModification = (clone $datePublication)->modify('+' . $faker->numberBetween(1, 30) . ' days');
                $offre[$i]->setDateModification($dateModification);
            } else {
                $offre[$i]->setDateModification($datePublication);
            }
            $offre[$i]->setLieu($faker->city);
            $offre[$i]->setLogo($faker->imageUrl(200, 480));
            $offre[$i]->setSalaire('10€');
            $distanciel = $faker->randomElement([$distanciels['FullRemote'], $distanciels['Hybrid'], $distanciels['Non']]);
            $offre[$i]->setDistanciel($distanciel);
            $manager->persist($offre[$i]);
        }


        $manager->flush();
    } 
}