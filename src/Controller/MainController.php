<?php

namespace App\Controller;

use App\Form\RechercheSortieFormType;
use App\Repository\SortiesRepository;
use App\Services\ChangementEtat;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class MainController extends AbstractController
{
    #[Route('/', name: 'main_home')]
    public function home(Request $request,
                         SortiesRepository $sortiesRepository,
                         ChangementEtat $changementEtat
    ): Response
    {
        $dateActuelle = new \DateTime();

        /*$sorties = $sortiesRepository->findAll();

        // Vérifier l'état de l'activité sur le point d'être affichée
        */

        $sortieform = $this->createForm(type: RechercheSortieFormType::class);
        $sortieform->handleRequest($request);

        if ($sortieform->isSubmitted() && $sortieform->isValid()) {
            // Initialiser les variables de filtre
            $campus = $sortieform->get('campus')->getData();
            $campusId = null;
            if($campus) {
                $campusId = $campus->getId();
            }
            $organisateur = $sortieform->get('organisateur')->getData();
            $inscrit = $sortieform->get('inscrit')->getData();
            $pasInscrit = $sortieform->get('pasInscrit')->getData();
            $sortiesPassees = $sortieform->get('sortiesPassees')->getData();
            $titre = $sortieform->get('titre')->getData();
            $dateRechercheDebut = $sortieform->get('dateRechercheDebut')->getData();
            $dateRechercheFin = $sortieform->get('dateRechercheFin')->getData();


            // Filtrer les sorties en fonction des champs renseignés
            $sorties = $sortiesRepository->findByCriteria(
                $campusId,
                organisateur: $organisateur ? $organisateur : null,
                inscrit: $inscrit ? $inscrit : null,
                pasInscrit: $pasInscrit ? $pasInscrit : null,
                sortiesPassees: $sortiesPassees ? $sortiesPassees : null,
                titre:  $titre,
                dateRechercheDebut: $dateRechercheDebut,
                dateRechercheFin: $dateRechercheFin
            );
            foreach ($sorties as $instanceDeSortie) {
                $changementEtat->modifierEtatPourSortie($instanceDeSortie);
            }
            return $this->render('main/home.html.twig', [
                'sortieform' => $sortieform,
                'sorties' => $sorties,
            ]);
        }

        return $this->render('main/home.html.twig', [
            'sortieform' => $sortieform,
            'sorties' => [],
        ]);
    }
}