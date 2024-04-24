<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Sorties;
use App\Form\AjouterSortieType;
use App\Form\InscriptionSortieFormType;
use App\Repository\SortiesRepository;
use App\Security\Voter\SortieVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class SortiesController extends AbstractController
{
    #[Route('/sorties', name:'sorties_afficher')]
    public function afficher(SortiesRepository $sortiesRepository): Response
    {
        $sorties = $sortiesRepository->findAll();
        return $this->render('sorties\sorties.html.twig', ["sorties" => $sorties]);
    }

    #[Route('/sorties/ajouter', name:'sorties_ajouter')]
    #[IsGranted(SortieVoter::CREATE)]
    public function ajouter(Request $request, EntityManagerInterface $entityManager): Response
    {
        $sortie = new Sorties();
        $sortieForm = $this->createForm(AjouterSortieType::class, $sortie);

        $sortieForm->handleRequest($request);
        if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {
            $entityManager->persist($sortie);
            $entityManager->flush();
            $this->addFlash('success', 'Evènement correctement ajouté ;)');
            return $this->redirectToRoute('sorties_afficher');
        }
        return $this -> render('sorties\sorties_ajouter.html.twig',
        [
            'sortieForm' => $sortieForm
        ]);
    }

    #[Route('/sorties/{id}/inscription', name:'sorties_inscription')]
    public function inscriptionSortie(Sorties $sortie, Request $request): Response
    {
        $form = $this->createForm(InscriptionSortieFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Inscription à la sortie réussie');
            return $this->redirectToRoute('main_home');
        }
        return $this->render('main/home.html.twig', [
            'sortie' => $sortie,
            'form' => $form->createView()
        ]);
    }

}
