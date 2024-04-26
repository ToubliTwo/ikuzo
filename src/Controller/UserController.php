<?php

namespace App\Controller;

use App\Form\ProfilFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
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
        /*$this->denyAccessUnlessGranted(ProfilVoter::EDIT, $id);*/
        $user = $ur->find($id);
        $userForm = $this->createForm(ProfilFormType::class, $user);

        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Profil modifié avec succès !');

            return $this->redirectToRoute('main_home');
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
}
