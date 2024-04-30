<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Sorties;
use App\Entity\User;
use App\Form\AjouterSortieFormType;
use App\Form\ModifierSortieFormType;
use App\Repository\SortiesRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
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
            $this->addFlash(type: 'warning', message:  'La date limite d\'inscription est dépassée.');
            return $this->redirectToRoute(route: 'main_home');
        }

        // Vérifier si le nombre d'inscrits ne dépasse pas le nombre de places :
        if ($sortie->getNombreDePlaces() <= $sortie->getUsers()->count()) {
            $this->addFlash(type: 'warning', message: 'Le nombre de places est atteint.');
            return $this->redirectToRoute(route: 'main_home');
        }
        // Vérifier si l'utilisateur est connecté et est une instance de \App\Entity\User
        if ($user instanceof User) {
            // Vérifier si l'utilisateur est déjà inscrit à la sortie. Sinon, l'inscrire :
            if (!$sortie->getUsers()->contains($user)) {
                // Ajouter l'utilisateur à la sortie
                $sortie->addUser($user);
                $em->flush();

                $this->addFlash(type: 'success', message: 'Inscription réussie !');
            } else {
                $this->addFlash(type: 'warning', message: 'Vous êtes déjà inscrit à cette sortie.');
            }
        } else {
            $this->addFlash(type: 'warning', message: 'Vous devez être connecté pour vous inscrire à une sortie.');
            return $this->redirectToRoute(route: 'app_login');
        }


        // Rediriger l'utilisateur vers la page d'accueil ou une autre page appropriée
        return $this->redirectToRoute(route: 'main_home');
    }

    #[Route('/sorties/desinscription/{id}', name:'actions_desinscription')]
 /*   #[IsGranted(ProfilVoter::DELETE, subject: 'user')]*/
    public function desinscrireSortie(Sorties $sortie, EntityManagerInterface $em): Response
    {
        // Récupérer l'utilisateur actuellement connecté (vous devez gérer cela en fonction de votre système d'authentification)
        $user = $this->getUser();
        // Vérifier si l'utilisateur est connecté et est une instance de \App\Entity\User
        if ($user instanceof \App\Entity\User) {
            // Vérifier si l'utilisateur est inscrit à la sortie
            if ($sortie->getUsers()->contains($user)) {
                // Vérifier si la date de sortie est dépassée
                if ($sortie->getDate() > new \DateTime()) {
                    // Retirer l'utilisateur de la sortie
                    $sortie->removeUser($user);
                    $em->flush();

                    $this->addFlash(type: 'success', message: 'Désinscription réussie !');
                } else {
                    $this->addFlash(type: 'warning', message: 'La date de la sortie est dépassée, vous ne pouvez pas vous désinscrire.');
                }
            } else {
                $this->addFlash(type: 'warning', message: 'Vous n\'êtes pas inscrit à cette sortie.');
            }
        } else {
            $this->addFlash(type: 'warning', message: 'Vous devez être connecté pour vous désinscrire d\'une sortie.');
        }

        // Rediriger l'utilisateur vers la page d'accueil ou une autre page appropriée
        return $this->redirectToRoute(route: 'main_home');
    }
    #[Route('/sorties/details/{id}', name:'actions_details')]
    public function details(Request $request, Sorties $sortie, PaginatorInterface $paginator, UserRepository $userRepository): Response
    {
        // Paginer les utilisateurs
         $page = $request->query->getInt('page', 1);
         $limite = 4;
         $participantsPagination = $userRepository->paginateUsers($page, $limite);
         $maxPages = ceil($participantsPagination->getTotalItemCount() / $limite);
         return $this->render('sorties/sorties_details.html.twig', [
             'maxPages' => $maxPages,
             'page' => $page,
            'participants' => $participantsPagination,
             'sortie' => $sortie
        ]);
    }
    #[Route('/sorties/modifier/{id}', name:'actions_modifier')]
    public function modifier(SortiesRepository $sortiesRepository, Request $request, EntityManagerInterface $entityManager, Sorties $modifSortie): Response
    {
        $modifSortieForm = $this->createForm(type: ModifierSortieFormType::class, data: $modifSortie);

        $modifSortieForm->handleRequest($request);

        //ci-dessous, on va chercher la fonction find du repository pour récupérer l'ID de la sortie à modifier
        $modifSortie = $sortiesRepository -> find($modifSortie->getId());

        if ($modifSortieForm->isSubmitted() && $modifSortieForm->isValid()) {

            $entityManager->persist($modifSortie);
            $entityManager->flush();

            $this->addFlash(type: 'success', message: 'Évènement correctement modifié !');

            return $this->redirectToRoute(route: 'main_home');
        }
        else if ($modifSortieForm->isSubmitted() && !$modifSortieForm->isValid())
        {
            $this->addFlash(type: 'fail', message: 'OOOOoops !');
        }

        return $this -> render( 'sorties\sorties_modifier.html.twig',  [
            'sortieForm' => $modifSortieForm,
            'sortie' => $modifSortie
        ]);
    }
    #[Route('/sorties/publier/{id}', name:'actions_publier')]
    public function publier(EntityManagerInterface $entityManager, Sorties $publierSortie): Response
    {
        $etatId = $entityManager->getReference(entityName: Etat::class, id: 2);
        $publierSortie->setEtat($etatId);
        $entityManager->flush();

        $this->addFlash(type: 'success', message: 'Évènement publié avec succès !');

        return $this->redirectToRoute(route: 'main_home');
    }
    #[Route('/sorties/supprimer/{id}', name:'actions_supprimer')]
    public function supprimer(EntityManagerInterface $entityManager, Sorties $supprimerSortie): Response
    {
        $entityManager->remove($supprimerSortie);

        $entityManager->flush();

        $this->addFlash(type: 'success', message: 'Évènement supprimé avec succès !');

        return $this->redirectToRoute(route: 'main_home');
    }
}


