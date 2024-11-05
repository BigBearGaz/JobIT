<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;


class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        Security $security,
        SluggerInterface $slugger,
        EntityManagerInterface $entityManager,
        
        #[Autowire('%kernel.project_dir%/public/uploads')] string $uploadsDirectory
    ): Response {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion du mot de passe
            $plainPassword = $form->get('plainPassword')->getData();
            if ($plainPassword) {
                $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
                $user->setPassword($hashedPassword);
            }

            // Gestion de l'upload de photo
            $photoFile = $form->get('logo')->getData();
            if ($photoFile) {
                $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photoFile->guessExtension();

                try {
                    $photoFile->move($uploadsDirectory, $newFilename);
                    $user->setPhoto($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Une erreur est survenue lors de l\'upload de la photo.');
                }
            }

            $user->setRoles(['ROLE_USER']);
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'L\'utilisateur a été créé avec succès.');
            $security->login($user);
            return $this->redirectToRoute('app_offre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
// namespace App\Controller;

// use App\Entity\User;
// use App\Form\RegistrationFormType;
// use App\Form\UserType;
// use Doctrine\ORM\EntityManagerInterface;
// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Bundle\SecurityBundle\Security;
// use Symfony\Component\DependencyInjection\Attribute\Autowire;
// use Symfony\Component\HttpFoundation\File\Exception\FileException;
// use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
// use Symfony\Component\Routing\Attribute\Route;
// use Symfony\Component\String\Slugger\SluggerInterface;

// class RegistrationController extends AbstractController
// {
//     #[Route('/register', name: 'app_register')]
//     public function register(
//         Request $request,
//         UserPasswordHasherInterface $passwordHasher,
//         Security $security,
//         SluggerInterface $slugger,
//         EntityManagerInterface $entityManager,
//         #[Autowire('%kernel.project_dir%/public/uploads')] string $uploadsDirectory): Response
//     {
//         $user = new User();
//         $form = $this->createForm(RegistrationFormType::class, $user);
//         $form->handleRequest($request);

//         if ($form->isSubmitted() && $form->isValid()) {
//             /** @var string $plainPassword */
//             $plainPassword = $form->get('plainPassword')->getData();

//             // encode the plain password
//             $user->setPassword($passwordHasher->hashPassword($user, $plainPassword));

//             // if ($form->isSubmitted() && $form->isValid()) {
//             //     // Gestion du mot de passe
//             //     $plainPassword = $form->get('plainPassword')->getData();
//             //     if ($plainPassword) {
//             //         $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
//             //         $user->setPassword($hashedPassword);
//             //     }

//                 // Gestion de l'upload de photo
//                 $photoFile = $form->get('logo')->getData();
//                 if ($photoFile) {
//                     $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
//                     $safeFilename = $slugger->slug($originalFilename);
//                     $newFilename = $safeFilename.'-'.uniqid().'.'.$photoFile->guessExtension();

//                     try {
//                         $photoFile->move($uploadsDirectory, $newFilename);
//                         $user->setPhoto($newFilename);
//                     } catch (FileException $e) {
//                         $this->addFlash('error', 'Une erreur est survenue lors de l\'upload de la photo.');
//                     }
//                 }
                
//                 $this->addFlash('success', 'L\'utilisateur a été créé avec succès.');
//                 return $this->redirectToRoute('app_offre_index', [], Response::HTTP_SEE_OTHER);
                
//                 $user->setRoles(['ROLE_USER']);
//                 $entityManager->persist($user);
//                 $entityManager->flush();
            

//             // do anything else you need here, like send an email

//             return $security->login($user, 'form_login', 'main');
//         }

//         return $this->render('registration/register.html.twig', [
//             'registrationForm' => $form,
//         ]);
//     }






















//     #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
// public function new(
//     Request $request,
//     EntityManagerInterface $entityManager,
//     SluggerInterface $slugger,
//     UserPasswordHasherInterface $passwordHasher,
//     #[Autowire('%kernel.project_dir%/public/uploads')] string $uploadsDirectory
// ): Response
// {
//     $user = new User();
//     $form = $this->createForm(UserType::class, $user);
//     $form->handleRequest($request);

//     if ($form->isSubmitted() && $form->isValid()) {
//         // Gestion du mot de passe
//         $plainPassword = $form->get('plainPassword')->getData();
//         if ($plainPassword) {
//             $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
//             $user->setPassword($hashedPassword);
//         }

//         // Gestion de l'upload de photo
//         $photoFile = $form->get('logo')->getData();
//         if ($photoFile) {
//             $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
//             $safeFilename = $slugger->slug($originalFilename);
//             $newFilename = $safeFilename.'-'.uniqid().'.'.$photoFile->guessExtension();

//             try {
//                 $photoFile->move($uploadsDirectory, $newFilename);
//                 $user->setPhoto($newFilename);
//             } catch (FileException $e) {
//                 $this->addFlash('error', 'Une erreur est survenue lors de l\'upload de la photo.');
//             }
//         }
//         $user->setRoles(['ROLE_USER']);
//         $entityManager->persist($user);
//         $entityManager->flush();

//         $this->addFlash('success', 'L\'utilisateur a été créé avec succès.');
//         return $this->redirectToRoute('app_offre_index', [], Response::HTTP_SEE_OTHER);
//     }

//     return $this->render('user/new.html.twig', [
//         'user' => $user,
//         'form' => $form,
//     ]);
// }
