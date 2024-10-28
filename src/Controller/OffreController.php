<?php

namespace App\Controller;

use App\Entity\Offre;
use App\Entity\TypeContrat;
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

        if ($this->getUser())
        {
            $userId = ($this->getUser()->getId());
        }
        else {
            $userId=0;
        }
        $offres = $offreRepository->findBy([], ['date_publication' => 'ASC']);


        return $this->render('offre/index.html.twig', [
            'offres' => $offres,
            'userId' => $userId,
            
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
    
    $offres = $offreRepository->findBy([], ['date_publication' => 'DESC']);
    
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
    
    $offres = $offreRepository->findBy([], ['date_publication' => 'ASC']);
    
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
            }

            $offre->setAuteur($this->getUser()->getId());
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
    public function show(Offre $offre): Response
    {
        $nomAuteur = $this->getUser()->getNom();
        if ($this->getUser())
        {
            $userId = ($this->getUser()->getId());
        }
        else {
            $userId=0;
        }
        return $this->render('offre/show.html.twig', [
            'offre' => $offre,
            'userId' => $userId,
            'nomAuteur' => $nomAuteur
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
        if ($offre->getAuteur() != $this->getUser()->getId()){
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

        if ($this->getUser())
        {
           $userId = ($this->getUser()->getId());
        }
        else {
            $userId=0;
        }

        return $this->render('offre/annonces.html.twig', [
            'annonces' => $annonces,
            'userId' => $userId,
            'nomCategorie' => $nomCategorie
        ]);
    }
}
