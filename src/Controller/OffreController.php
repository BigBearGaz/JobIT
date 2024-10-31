<?php

namespace App\Controller;

use App\Entity\Offre;
use App\Entity\TypeContrat;
use App\Entity\User;
use App\Form\OffreType;
use App\Repository\CategoryRepository;
use App\Repository\OffreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/')]
final class OffreController extends AbstractController
{
    #[Route(name: 'app_offre_index', methods: ['GET'])]
    public function index(OffreRepository $offreRepository): Response
    {   
        $statut = 0;
        if ($this->getUser())
        {
            $statut =$this->getUser()->getStatut();
            $userId = ($this->getUser()->getId());
        }
        else {
            $userId=0;
        }
        $offres = $offreRepository->findBy([], ['date_publication' => 'ASC']);


        return $this->render('offre/index.html.twig', [
            'offres' => $offres,
            'userId' => $userId,
            'statut' => $statut
        ]);
    }

    #[Route('/dateDesc',name:'app_offre_par_date', methods: ['GET'])]
public function Desc(OffreRepository $offreRepository): Response
{
    if ($this->getUser())
        {
            $userId = ($this->getUser()->getId());
        }
        else {
            $userId=0;
        }
    
    $offres = $offreRepository->findBy([], ['date_modification' => 'DESC']);
    
    return $this->render('offre/index.html.twig', [
        'offres' => $offres,
        'userId' => $userId
    ]);
}

#[Route('/dateAsc',name:'app_offre_par_datee', methods: ['GET'])]
public function Asc(OffreRepository $offreRepository): Response
{
    if ($this->getUser())
        {
            $userId = ($this->getUser()->getId());
        }
        else {
            $userId=0;
        }
    
    $offres = $offreRepository->findBy([], ['date_modification' => 'ASC']);
    
    return $this->render('offre/index.html.twig', [
        'offres' => $offres,
        'userId' => $userId
    ]);
}

    #[Route('/new', name: 'app_offre_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger,
        #[Autowire('%kernel.project_dir%/public/uploads')] string $brochuresDirectory
    ): Response
    {
        if (!$this->getUser()){
            return $this->redirectToRoute('app_login'); 
        }
        $offre = new Offre();
        $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($request);
        $brochureFile = $form->get('logo')->getData();

        if ($form->isSubmitted() && $form->isValid()) {

            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move($brochuresDirectory, $newFilename);
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $offre->setLogo($newFilename);
            } else {
                $logo = $this->getUser()->getPhoto(); 
                $offre->setLogo($logo);
            }

            $offre->setAuteur($this->getUser());
            $offre->setDatePublication(new \DateTime());
            $offre->setDateModification(new \DateTime());
            $entityManager->persist($offre);
            $entityManager->flush();

            return $this->redirectToRoute('app_offre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('offre/new.html.twig', [
            'offre' => $offre,
            'form' => $form,
        ]);
    }

    #[Route('/show/{id}', name: 'app_offre_show', methods: ['GET'])]
    public function show(Offre $offre, OffreRepository $repository, $id): Response
    {   
        $selectedOffre = $repository->find($id);
        $users = $selectedOffre->getUsers();
        // dd($selectedOffre);
        return $this->render('offre/show.html.twig', [
            'offre' => $offre,
            'users' => $users,
        ]);
    }

    #[Route('/edit/{id}', name: 'app_offre_edit', methods: ['GET', 'POST'])]
    
    public function edit(
        Request $request,
        Offre $offre,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger,
        #[Autowire('%kernel.project_dir%/public/uploads')] string $brochuresDirectory
    ): Response
    {
        if ($offre->getAuteur()->getId() !== $this->getUser()->getId()){
            return $this->redirectToRoute('app_lock'); 
        }
        $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $brochureFile= $form->get('logo')->getData();

            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move($brochuresDirectory, $newFilename);
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $offre->setLogo($newFilename);
            }
       

                

            $offre->setDateModification(new \DateTime());
            $entityManager->flush();

        
            return $this->redirectToRoute('app_offre_index', [], Response::HTTP_SEE_OTHER);
        }

            return $this->render('offre/edit.html.twig', [
            'offre' => $offre,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_offre_delete', methods: ['POST'])]
    public function delete(Request $request, Offre $offre, EntityManagerInterface $entityManager): Response
    {
        if ($offre->getAuteur()->getId() !== $this->getUser()->getId()){
            return $this->redirectToRoute('app_lock'); 
        }
        if ($this->isCsrfTokenValid('delete'.$offre->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($offre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_offre_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('offre/annonces/{id}', name: 'app_annonces', methods: ['GET'])]
    public function showCategorie(OffreRepository $categories, CategoryRepository $cr, $id): Response

    {
        $nomCategorie = $cr->find($id)->getNom();
        $annonces = $categories->findBy(['category' => $id]);

        return $this->render('offre/index.html.twig', [
            'offres' => $annonces,
            'nomCategorie' => $nomCategorie
        ]);
    }

    #[Route('/favoris/{id}', name: 'app_add_favori', methods: ['GET', 'POST'])]
    public function addFavori(Offre $offre, EntityManagerInterface $entityManager): Response
    {   
        $user = $this->getUser();
        
        if (!$user) {
            $this->addFlash('error', 'Vous devez être connecté pour ajouter un favori.');
            return $this->redirectToRoute('app_login');
        }
        
        if ($offre->getAuteur()->getId() !== $this->getUser()->getId()){
            return $this->redirectToRoute('app_lock'); 
        }
        if ($offre->getUsers()->contains($user)) {
            $offre->removeUser($user);
            $entityManager->persist($offre);
            $entityManager->flush();
            $this->addFlash('info', 'Cette offre est déjà dans vos favoris.');
        } else {
            $offre->addUser($user);
            $entityManager->persist($offre);
            $entityManager->flush();
            $this->addFlash('success', 'L\'offre a été ajoutée à vos favoris.');
        }

        return $this->redirectToRoute('app_offre_show', ['id' => $offre->getId()]);
    }

    #[Route('/favoris', name: 'app_favori', methods: ['GET', 'POST'])]
    public function mesFavoris(OffreRepository $offreRepository): Response
    {   
        $user= $this->getUser();
        if (!$user) {
            $this->addFlash('error', 'Vous devez être connecté.');
            return $this->redirectToRoute('app_login');
        }
        $favorisOffre = $user->getTara();
        return $this->render('offre/favoris.html.twig',['favorisOffre'=> $favorisOffre]);

    }

    public function search(Request $request, EntityManagerInterface $entityManager): Response
{
    $searchTerm = $request->query->get('q');
    $offres = $entityManager->getRepository(Offre::class)->searchByTerm($searchTerm);

    return $this->render('offre/index.html.twig', [
        'offres' => $offres,
        'searchTerm' => $searchTerm
    ]);
}
}
