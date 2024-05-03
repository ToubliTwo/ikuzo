<?php

namespace App\Controller;



use App\Entity\Campus;
use App\Form\AjoutCampusFormType;
use App\Form\GererCampusFormType;
use App\Repository\CampusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CampusController extends AbstractController
{
    public function __construct(private readonly CampusRepository $campusRepository)
    {
    }

    #[Route('/campus/gerer', name: 'campus_gerer')]
    public function gererCampus(Request $request, EntityManagerInterface $entityManager): Response
    {
        $campus = new Campus();
//        Récupérer la liste de tous les campus existants pour les afficher dans le twig
        $campusList = $this->campusRepository->findAll();



//      ***** GESTION DE LA RECHERCHE DES CAMPUS *****
//        Création du formulaire de recherche des campus
            $rechercheCampusForm = $this->createForm(GererCampusFormType::class, $campus);
            $rechercheCampusForm->handleRequest($request);
        if ($rechercheCampusForm->isSubmitted() && $rechercheCampusForm->isValid() && $request->request->has('clickSurRechercher')) {
            $nomSaisi = $rechercheCampusForm->get('nom')->getData();
            $campusList = $this->campusRepository->findByCriteriaWithCampus($nomSaisi);
//          $this -> addFlash('success', 'Le campus recherché à bien été trouvé ! ');
        }


        //      ***** GESTION DE L'AJOUT DE NOUVEAUX CAMPUS *****
        $ajoutCampusForm = $this->createForm(AjoutCampusFormType::class);
        $ajoutCampusForm->handleRequest($request);
        if ($ajoutCampusForm ->isSubmitted() && $ajoutCampusForm ->isValid() && $request->request->has('clickSurEnregistrer')) {
            $newCampus = $ajoutCampusForm->getData();
            $entityManager->persist($newCampus);
            $entityManager->flush();
            $this -> addFlash('success', 'Le campus a bien été ajouté ! ');
            return $this->redirectToRoute('campus_gerer');
        }







//      ***** GESTION DE L'AJOUT DE NOUVEAUX CAMPUS *****
//        $ajoutCampusForm = $this->createForm(AjoutCampusFormType::class);
//        $ajoutCampusForm->handleRequest($request);
//        if ($ajoutCampusForm ->isSubmitted() && $ajoutCampusForm ->isValid()) {
//            $newCampus = $ajoutCampusForm->getData();
//            $entityManager->persist($newCampus);
//            $entityManager->flush();
//            $this -> addFlash('success', 'Le campus a bien été ajouté ! ');
//          return $this->redirectToRoute('campus_gerer');
//        }

    return $this->render('\campus\campus_gerer.html.twig', [
        'rechercheCampusForm' => $rechercheCampusForm, //permet la recherche par mot-clé
        'campusList' => $campusList, //permet l'affichage complet des campus existants
        'ajouterNouveauCampus' => $ajoutCampusForm
        ]);
    }

}

