<?php

namespace App\Controller;

use App\Form\VilleFormType;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class VillesController extends AbstractController
{
    #[Route('/gerer-villes', name: 'gerer_villes')]
    public function gererVilles(Request $request, EntityManagerInterface $entityManager, VilleRepository $villeRepository): Response
    {
        // Recherche des villes en fonction du nom
        $villes = $villeRepository->findAll();

        // Création du formulaire
        $form = $this->createForm(type: VilleFormType::class);
        $form->handleRequest($request);

        // Traitement du formulaire s'il est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $nom = $data['nom'];


            // Renvoi vers le template avec les résultats de la recherche
            return $this->render( 'villes/gerer_villes.html.twig',  [
                'form' => $form,
                'villes' => $villes,
            ]);
        }

        // Renvoi vers le template avec le formulaire vide
        return $this->render('villes/gerer_villes.html.twig', [
            'form' => $form,
            'villes' => $villes,
        ]);

    }
}
