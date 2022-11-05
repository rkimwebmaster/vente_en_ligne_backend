<?php

namespace App\Controller;

use App\Entity\MobileMoney;
use App\Form\MobileMoneyType;
use App\Repository\MobileMoneyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/mobile/money')]
class MobileMoneyController extends AbstractController
{
    #[Route('/', name: 'app_mobile_money_index', methods: ['GET'])]
    public function index(MobileMoneyRepository $mobileMoneyRepository): Response
    {
        return $this->render('mobile_money/index.html.twig', [
            'mobile_moneys' => $mobileMoneyRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_mobile_money_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $mobileMoney = new MobileMoney();
        $form = $this->createForm(MobileMoneyType::class, $mobileMoney);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($mobileMoney);
            $entityManager->flush();

            return $this->redirectToRoute('app_mobile_money_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('mobile_money/new.html.twig', [
            'mobile_money' => $mobileMoney,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_mobile_money_show', methods: ['GET'])]
    public function show(MobileMoney $mobileMoney): Response
    {
        return $this->render('mobile_money/show.html.twig', [
            'mobile_money' => $mobileMoney,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_mobile_money_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, MobileMoney $mobileMoney, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MobileMoneyType::class, $mobileMoney);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_mobile_money_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('mobile_money/edit.html.twig', [
            'mobile_money' => $mobileMoney,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_mobile_money_delete', methods: ['POST'])]
    public function delete(Request $request, MobileMoney $mobileMoney, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mobileMoney->getId(), $request->request->get('_token'))) {
            $entityManager->remove($mobileMoney);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_mobile_money_index', [], Response::HTTP_SEE_OTHER);
    }
}
