<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/user')]
final class UserController extends AbstractController
{
    #[Route(name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }
    
    

#[Route('/userEdit/{id}', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        User $user,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger,
        #[Autowire('%kernel.project_dir%/public/uploads')] string $uploadsDirectory
    ): Response
    {   
        if ($user->getId() !== $this->getUser()->getId()){
            return $this->redirectToRoute('app_lock'); 
        }
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

           
            // $photoFile = $form->get('photo')->getData();
            
            // if ($photoFile) {
            //     $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
            //     $safeFilename = $slugger->slug($originalFilename);
            //     $newFilename = $safeFilename.'-'.uniqid().'.'.$photoFile->guessExtension();
    
            //     try {
            //         $photoFile->move($uploadsDirectory, $newFilename);
            //         $user->setPhoto($newFilename);
            //     } catch (FileException $e) {
            //         $this->addFlash('error', 'Une erreur est survenue lors de l\'upload de la photo.');
            //     }
            // }
            
            $entityManager->flush();
    
            $this->addFlash('success', 'Votre profil a été mis à jour avec succès.');
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    }
