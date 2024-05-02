<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException(message: 'This method can be blank - it will be intercepted by the logout key on your firewall.');
    }


//    #[Route(path: '/mdpOublie', name: 'app_mdpOublie')]
//    public function mdpOublie(Request $request) : Response
//    {
//        $mdp = new User();
//
//        $mdpOublieForm = $this->createForm(MotDePasseOublieFormType::class, $mdp);
//
//        $mdpOublieForm->handleRequest($request);
//
//            return $this->render('security/mdpOublie.html.twig', ['mdpOublie' => $mdpOublieForm]);
//    }

}
