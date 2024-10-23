<?php

namespace App\Controller;

use App\Entity\TypeContrat;
use App\Form\TypeContratType;
use App\Repository\TypeContratRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/type/contrat')]
final class TypeContratController extends AbstractController
{
    #[Route(name: 'app_type_contrat_index', methods: ['GET'])]
    public function index(TypeContratRepository $typeContratRepository): Response
    {
        return $this->render('type_contrat/index.html.twig', [
            'type_contrats' => $typeContratRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_type_contrat_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $typeContrat = new TypeContrat();
        $form = $this->createForm(TypeContratType::class, $typeContrat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($typeContrat);
            $entityManager->flush();

            return $this->redirectToRoute('app_type_contrat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('type_contrat/new.html.twig', [
            'type_contrat' => $typeContrat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_type_contrat_show', methods: ['GET'])]
    public function show(TypeContrat $typeContrat): Response
    {
        return $this->render('type_contrat/show.html.twig', [
            'type_contrat' => $typeContrat,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_type_contrat_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TypeContrat $typeContrat, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TypeContratType::class, $typeContrat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_type_contrat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('type_contrat/edit.html.twig', [
            'type_contrat' => $typeContrat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_type_contrat_delete', methods: ['POST'])]
    public function delete(Request $request, TypeContrat $typeContrat, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$typeContrat->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($typeContrat);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_type_contrat_index', [], Response::HTTP_SEE_OTHER);
    }
}
