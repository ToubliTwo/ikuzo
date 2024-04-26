<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfilFormType;
use App\Repository\UserRepository;
use App\Security\Voter\ProfilVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class UserController extends AbstractController
{
    #[Route('/user/modifier/{id}', name: 'user_modifier')]
/*#[IsGranted(ProfilVoter::EDIT, subject: 'id')]*/ //FIXME : conflit entre Id et user
    public function modifier(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository, int $id
    ): Response
    {
        /*$this->denyAccessUnlessGranted(ProfilVoter::EDIT, $id);*/
        $user = $userRepository->find($id);
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


}
