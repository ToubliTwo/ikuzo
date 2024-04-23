<?php

namespace App\Controller;

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
    #[IsGranted(ProfilVoter::EDIT, subject: 'user')]
    public function modifier(int $id,
                             UserRepository $userRepository,
                             EntityManagerInterface $entityManager,
                             Request $request
    ): Response
    {
        $user = $userRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }

        $userForm = $this->createForm(ProfilFormType::class, $user);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {

            $user->setAdministrateur(false);
            $user->$this->setActif(true);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Profil modifié');
            return $this->redirectToRoute('user_modifier', ['id' => $user->getId()]);
        }

        return $this->render('user/modifier.html.twig', [
        'user' => $user,
        'userForm' => $userForm
        ]);
    }


}
