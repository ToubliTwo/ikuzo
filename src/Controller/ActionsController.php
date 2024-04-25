<?php

namespace App\Controller;

use App\Entity\Sorties;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ActionsController extends AbstractController
{
    #[Route('/sorties/inscription/{id}', name:'actions_inscription')]
    public function inscrireSortie(Sorties $sortie, EntityManagerInterface $em): Response
    {
        // Récupérer l'utilisateur actuellement connecté
        $user = $this->getUser();
        // Vérifier si la date d'inscription est dépassée :
        if ($sortie->getDateLimiteInscription() < new \DateTime()) {
            $this->addFlash('warning', 'La date limite d\'inscription est dépassée.');
            return $this->redirectToRoute('main_home');
        }

        // Vérifier si le nombre d'inscrits ne dépasse pas le nombre de places :
        if ($sortie->getNombreDePlaces() <= $sortie->getUsers()->count()) {
            $this->addFlash('warning', 'Le nombre de places est atteint.');
            return $this->redirectToRoute('main_home');
        }

        // Vérifier si l'utilisateur est déjà inscrit à la sortie. Sinon, l'inscrire :
        if (!$sortie->getUsers()->contains($user)) {
            // Ajouter l'utilisateur à la sortie
            $sortie->addUser($user);
            $em->flush();

            $this->addFlash('success', 'Inscription réussie !');
        } else {
            $this->addFlash('warning', 'Vous êtes déjà inscrit à cette sortie.');
        }

        // Rediriger l'utilisateur vers la page d'accueil ou une autre page appropriée
        return $this->redirectToRoute('main_home');
    }

    #[Route('/sorties/desinscription/{id}', name:'actions_desinscription')]
    public function desinscrireSortie(Sorties $sortie, EntityManagerInterface $em): Response
    {
        // Récupérer l'utilisateur actuellement connecté (vous devez gérer cela en fonction de votre système d'authentification)
        $user = $this->getUser();

        // Vérifier si l'utilisateur est inscrit à la sortie
        if ($sortie->getUsers()->contains($user)) {
            // Vérifier si la date de sortie est dépassée
            if ($sortie->getDate() > new \DateTime()) {
                // Retirer l'utilisateur de la sortie
                $sortie->removeUser($user);
                $em->flush();

                $this->addFlash('success', 'Désinscription réussie !');
            } else {
                $this->addFlash('warning', 'La date de la sortie est dépassée, vous ne pouvez pas vous désinscrire.');
            }
        } else {
            $this->addFlash('warning', 'Vous n\'êtes pas inscrit à cette sortie.');
        }

        // Rediriger l'utilisateur vers la page d'accueil ou une autre page appropriée
        return $this->redirectToRoute('main_home');
    }
    #[Route('/sorties/details/{id}', name:'actions_details')]
    public function details(Sorties $sortie): Response
    {
        //Récupérer la liste des participants à la sortie avec le DTO
        $participants = $sortie->getUsers();

        return $this->render('sorties/sorties_details.html.twig', [
            'sortie' => $sortie,
            'participants' => $participants
        ]);
    }
}


