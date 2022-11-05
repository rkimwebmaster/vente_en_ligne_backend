<?php

namespace App\Controller;

use App\Entity\Recherche;
use App\Form\RechercheType;
use App\Repository\RechercheRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/recherche')]
class RechercheController extends AbstractController
{
    #[Route('/', name: 'app_recherche_index', methods: ['GET'])]
    public function index(RechercheRepository $rechercheRepository): Response
    {
        return $this->render('recherche/index.html.twig', [
            'recherches' => $rechercheRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_recherche_new', methods: ['GET', 'POST'])]
    public function new(Request $request, RechercheRepository $rechercheRepository): Response
    {
        $recherche = new Recherche();
        $form = $this->createForm(RechercheType::class, $recherche);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rechercheRepository->save($recherche, true);

            return $this->redirectToRoute('app_recherche_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('recherche/new.html.twig', [
            'recherche' => $recherche,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_recherche_show', methods: ['GET'])]
    public function show(Recherche $recherche): Response
    {
        return $this->render('recherche/show.html.twig', [
            'recherche' => $recherche,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_recherche_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Recherche $recherche, RechercheRepository $rechercheRepository): Response
    {
        $form = $this->createForm(RechercheType::class, $recherche);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rechercheRepository->save($recherche, true);

            return $this->redirectToRoute('app_recherche_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('recherche/edit.html.twig', [
            'recherche' => $recherche,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_recherche_delete', methods: ['POST'])]
    public function delete(Request $request, Recherche $recherche, RechercheRepository $rechercheRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$recherche->getId(), $request->request->get('_token'))) {
            $rechercheRepository->remove($recherche, true);
        }

        return $this->redirectToRoute('app_recherche_index', [], Response::HTTP_SEE_OTHER);
    }
}
