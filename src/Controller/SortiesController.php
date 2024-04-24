<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Sorties;
use App\Form\AjouterSortieType;
use App\Form\RechercheSortieFormType;
use App\Repository\SortiesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SortiesController extends AbstractController
{
    #[Route('/sorties', name:'sorties_afficher')]
    public function afficher(SortiesRepository $sortiesRepository): Response
    {
        $sorties = $sortiesRepository->findAll();
        return $this->render('sorties\sorties.html.twig', ["sorties" => $sorties]);
    }


    #[Route('/sorties/ajouter', name: 'sorties_ajouter')]
    public function ajouter(Request $request, EntityManagerInterface $entityManager): Response
    {
        $sortie = new Sorties();
        $sortieForm = $this->createForm(AjouterSortieType::class, $sortie);
        $sortieForm->handleRequest($request);

        if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {
            $entityManager->persist($sortie);
            $entityManager->flush();

            // Ajouter un message flash pour informer l'utilisateur de la réussite
            $this->addFlash('success', 'La sortie a été ajoutée avec succès.');

            return $this->redirectToRoute('sorties_afficher');
        }

        return $this->render('sorties/sorties_ajouter.html.twig', [
            'sortieForm' => $sortieForm->createView(),
        ]);
    }
        #[Route('/sorties/par-campus', name: 'sorties_par_campus')]
            public function sortiesParCampus(Request $request, SortiesRepository $sortiesRepository): Response
        {
                $sortie = new Sorties();
                $sortieform = $this->createForm(RechercheSortieFormType::class);
                $sortieform->handleRequest($request);

                if ($sortieform->isSubmitted() && $sortieform->isValid()) {
                   $campusId = $sortieform->get('campus')->getData()->getId();
                    $sorties = $sortiesRepository->findByCampus($campusId);
                } else {
                    $sorties = []; // Mettre à jour pour obtenir toutes les sorties si aucun campus n'est sélectionné
                }

                return $this->render('sorties/sorties_par_campus.html.twig', [
                  'sortieform' => $sortieform,
                   'sorties' => $sorties,
               ]);

    }

}
