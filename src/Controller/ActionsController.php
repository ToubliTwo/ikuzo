<?php

namespace App\Controller;

use App\Entity\Sorties;
use App\Form\ModifierSortieFormType;
use App\Repository\SortiesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    #[Route('/sorties/modifier/{id}', name:'actions_modifier')]
    public function modifier(SortiesRepository $sortiesRepository, Request $request, EntityManagerInterface $entityManager, Sorties $modifSortie): Response
    {
        $modifSortieForm = $this->createForm(ModifierSortieFormType::class, $modifSortie);

        $modifSortieForm->handleRequest($request);

        //ci-dessous, on va chercher la fonction find du repository pour récupérer l'ID de la sortie à modifier
        $modifSortie = $sortiesRepository -> find($modifSortie->getId());

        if ($modifSortieForm->isSubmitted() && $modifSortieForm->isValid()) {

            $entityManager->persist($modifSortie);
            $entityManager->flush();

            $this->addFlash('success', 'Évènement correctement modifié !');

            return $this->redirectToRoute('main_home');
        }
        else if ($modifSortieForm->isSubmitted() && !$modifSortieForm->isValid())
        {
            $this->addFlash('fail', 'OOOOoops !');
        }

        return $this -> render('sorties\sorties_modifier.html.twig',[
            'sortieForm' => $modifSortieForm,
            'sortie' => $modifSortie
        ]);
    }
    #[Route('/sorties/supprimer/{id}', name:'actions_supprimer')]
    public function supprimer(EntityManagerInterface $entityManager, Sorties $supprimerSortie): Response
    {

        $entityManager->remove($supprimerSortie);
        $entityManager->flush();

        $this->addFlash('success', 'Évènement supprimé avec succès !');

        return $this->redirectToRoute('main_home');
    }
}


