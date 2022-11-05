<?php

namespace App\Controller;

use App\Entity\Annulation;
use App\Form\AnnulationType;
use App\Repository\AnnulationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/annulation')]
class AnnulationController extends AbstractController
{
    #[Route('/', name: 'app_annulation_index', methods: ['GET'])]
    public function index(AnnulationRepository $annulationRepository): Response
    {
        return $this->render('annulation/index.html.twig', [
            'annulations' => $annulationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_annulation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $annulation = new Annulation();
        $form = $this->createForm(AnnulationType::class, $annulation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($annulation);
            $entityManager->flush();

            return $this->redirectToRoute('app_annulation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('annulation/new.html.twig', [
            'annulation' => $annulation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_annulation_show', methods: ['GET'])]
    public function show(Annulation $annulation): Response
    {
        return $this->render('annulation/show.html.twig', [
            'annulation' => $annulation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_annulation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Annulation $annulation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AnnulationType::class, $annulation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_annulation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('annulation/edit.html.twig', [
            'annulation' => $annulation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_annulation_delete', methods: ['POST'])]
    public function delete(Request $request, Annulation $annulation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$annulation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($annulation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_annulation_index', [], Response::HTTP_SEE_OTHER);
    }
}
