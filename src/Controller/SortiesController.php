<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Sorties;
use App\Form\AjouterSortieFormType;
use App\Repository\LieuRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SortiesController extends AbstractController
{

    private $entityManager;
    private $user;

    #[Route('/sorties/ajouter', name: 'sorties_ajouter')]
    public function ajouter(Request $request,
                            EntityManagerInterface $entityManager,
                            VilleRepository $villeRepository
    ): Response
    {
        $sortie = new Sorties();


        // Crée le formulaire sans passer de lieu initial
        $sortieForm = $this->createForm(AjouterSortieFormType::class, $sortie);

        $sortieForm->handleRequest($request);

        if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {
            // Récupère la ville sélectionnée à partir du formulaire
            $ville = $sortieForm->get('ville')->getData();

            // Filtrer les lieux en fonction de la ville sélectionnée
            $lieux = $entityManager->getRepository(Lieu::class)->findBy(['ville' => $ville]);

            // Passe les lieux filtrés au formulaire
            $sortieForm = $this->createForm(type: AjouterSortieFormType::class, data:  $sortie);

            // Traite la soumission du formulaire...
            $sortieForm->handleRequest($request);

            if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {
                $ville = $sortieForm->get('ville')->getData();

                // Vérifie si la ville est valide
                if ($ville !== null) {
                    // Filtrer les lieux en fonction de la ville sélectionnée
                    $lieux = $entityManager->getRepository(Lieu::class)->findBy(['ville' => $ville]);


                    // Récupère l'utilisateur connecté
                    $user = $this->getUser();

                    // Définit l'utilisateur comme organisateur de la sortie
                    $sortie->setOrganisateur($user);

                    // Définit l'état de la sortie
                    $etat = $entityManager->getRepository(Etat::class)->findOneBy(['libelle' => 'Créée']);
                    $sortie->setEtat($etat);

                    // Enregistre la sortie en base de données
                    $entityManager->persist($sortie);
                    $entityManager->flush();

                    $this->addFlash(type: 'success', message: 'La sortie a bien été ajoutée !');

                } else {
                    $this->addFlash(type: 'danger', message: 'La sortie n\'a pu être validée !');
                }

                return $this->redirectToRoute(route: 'main_home');
            }
        }

        return $this->render('sorties\sorties_ajouter.html.twig', [
            'sortieForm' => $sortieForm->createView(),
        ]);
    }


      #[Route("/fetch-lieux-by-ville", name:"fetch_lieux_by_ville")]
    public function fetchLieuxByVille(Request $request, LieuRepository $lieuRepository): JsonResponse
    {
        $villeId = $request->query->get('villeId');

        // Récupérer les lieux en fonction de l'ID de la ville
        $lieux = $lieuRepository->findByVille($villeId);

        // Créer un tableau d'options pour les lieux
        $options = [];
        foreach ($lieux as $lieu) {
            $options[] = [
                'id' => $lieu->getId(),
                'nom' => $lieu->getNom(),
                'rue' => $lieu->getRue(),
                'latitude' => $lieu->getLatitude(),
                'longitude' => $lieu->getLongitude(),
            ];
        }


        // Renvoyer les options au format JSON
        return new JsonResponse($options);
    }

    #[Route("/fetch-lieu-details", name:"fetch_lieu_details")]
    public function fetchLieuDetails(Request $request, LieuRepository $lieuRepository): JsonResponse
    {
        $lieuId = $request->query->get('lieuId');

        // Récupérer les détails du lieu en fonction de l'ID du lieu
        $lieu = $lieuRepository->find($lieuId);

// Vérifier si le lieu existe
        if ($lieu !== null) {
            // Créer un tableau de détails pour le lieu
            $details = [
                'id' => $lieu->getId(),
                'nom' => $lieu->getNom(),
                'rue' => $lieu->getRue(),
                'latitude' => $lieu->getLatitude(),
                'longitude' => $lieu->getLongitude(),
            ];
        } else {
            // Si le lieu n'existe pas, retourner un tableau vide ou un message d'erreur
            $details = []; // ou $details = ['error' => 'Lieu non trouvé'];
        }

        // Renvoyer les détails au format JSON
        return new JsonResponse($details);
    }
}

/*    #[Route('/sorties', name: 'sorties_afficher')]
        public function afficher(SortiesRepository $sortiesRepository, EntityManagerInterface $entityManager, ChangementEtat $changementEtat): Response
        {
            //obtenir la date actuelle :
            $dateActuelle = new \DateTime();

            $tableauDeSortiesQuiContientToutesLesSorties = $sortiesRepository->findAll();

            // Vérifier l'état de l'activité sur le point d'être affichée
            foreach ($tableauDeSortiesQuiContientToutesLesSorties as $instanceDeSortie) {
                $changementEtat->modifierEtat($instanceDeSortie);
            }

            return $this->render('sorties\sorties.html.twig', ["dateActuelle" => $dateActuelle, "tiensPrendsMonTableau" => $tableauDeSortiesQuiContientToutesLesSorties]); //"sorties" => $sorties,
        }*/