<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Sorties;
use App\Form\AjouterSortieType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SortiesController extends AbstractController
{
    #[Route('/sorties', name:'sorties_afficher')]
    public function afficher(): Response
    {
        return $this->render('sorties\sorties.html.twig');
    }


    #[Route('/sorties/ajouter', name:'sorties_ajouter')]
    public function ajouter(): Response
    {
        $sortie = new Sorties();
        $sortieForm = $this->createForm(AjouterSortieType::class, $sortie);
        //todo : traiter le formulaire
        return $this -> render('sorties\sorties_ajouter.html.twig',
        [
            'sortieForm' => $sortieForm->createView()
        ]);
    }

}
