<?php

namespace App\Controller;

use App\Form\ProfilFormType;
use App\Form\UserProfilFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class UserController extends AbstractController
{
    #[Route('/user/modifier/{id}', name: 'user_modifier')]
/*#[IsGranted(ProfilVoter::EDIT, subject: 'id')]*/ //FIXME : conflit entre Id et user
    public function modifier(Request $request,
                             EntityManagerInterface $entityManager,
                             UserRepository $ur,
                             int $id
    ): Response
    {
        //$this->denyAccessUnlessGranted(ProfilVoter::EDIT, $id);/
        $user = $ur->find($id);
        $userForm = $this->createForm(type: ProfilFormType::class, data: $user);

        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash(type: 'success', message: 'Profil modifié avec succès !');

            return $this->redirectToRoute(route: 'main_home');
        }
        return $this->render('user/modifier.html.twig',
            [
                'userForm' => $userForm,
                'user' => $user
            ]);
    }
    #[Route('/user/detail/{id}', name: 'user_details')]
    public function afficherProfil(UserRepository $ur, int $id): Response
    {
        $user = $ur->find($id);

        return $this->render('user/profil.html.twig', ['user' => $user]);
    }
    #[Route('/user/photo', name: 'user_photo')]
    public function photo(Request $request, EntityManagerInterface $entityManager): Response
    {
        $userProfilForm = $this->createForm(UserProfilFormType::class);
        $userProfilForm->handleRequest($request);

        if ($userProfilForm->isSubmitted()&& $userProfilForm->isValid()) {
            $entityManager->persist($userProfilForm);
            $entityManager->flush();

            return $this->redirectToRoute('main_home');
        }
        return $this->render('user/photo.html.twig', ['userProfilForm' => $userProfilForm]);
    }
}

