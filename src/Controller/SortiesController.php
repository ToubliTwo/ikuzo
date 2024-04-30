<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Sorties;
use App\Form\AjouterSortieFormType;
use App\Security\Voter\SortieVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class SortiesController extends AbstractController
{
    #[Route('/sorties/ajouter', name: 'sorties_ajouter')]
    #[IsGranted(SortieVoter::CREATE)]
    public function ajouter(Request $request, EntityManagerInterface $entityManager): Response
    {
        $sortie = new Sorties();
        $sortieForm = $this->createForm(AjouterSortieFormType::class, $sortie);
        $sortieForm->handleRequest($request);

        if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {

            if ($request->request->has('clickSurValider'))
            {
                //associer par défaut l'état "Créée" à la nouvelle sortie sur le point d'être créée
                //définir l'état à Créée qui correspond à l'id 1 de Etat, le setteur dans l'entité Sorties prend en paramètre une instance de Etat
                $sortie->setEtat($entityManager->getReference(Etat::class, 1));

                $sortie->setOrganisateur($this->getUser());
                $entityManager->persist($sortie);
                $entityManager->flush();

                $this->addFlash('success', 'Évènement correctement ajouté !');

                return $this->redirectToRoute('main_home');
            }
            else if ($request->request->has('clickSurPublier'))
            {
                //définir l'état à Ouverte qui correspond à l'id 2 de Etat, le setteur dans l'entité Sorties prend en paramètre une instance de Etat
                $sortie->setEtat($entityManager->getReference(Etat::class, 2));

                $sortie->setOrganisateur($this->getUser());
                $entityManager->persist($sortie);
                $entityManager->flush();

                $this->addFlash('success', 'Évènement correctement ajouté et publié !');

                return $this->redirectToRoute('main_home');
            }

        }
            return $this->render('sorties\sorties_ajouter.html.twig',
                [
                    'sortieForm' => $sortieForm
                ]);
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