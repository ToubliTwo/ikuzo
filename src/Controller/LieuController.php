<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Form\AjoutLieuFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LieuController extends AbstractController
{
    #[Route('/lieu/ajouter', name: 'lieu_ajouter')]
    public function ajouter(Request $request, EntityManagerInterface $em): Response
    {
        $lieu = new Lieu();

        $lieuForm = $this->createForm(AjoutLieuFormType::class, $lieu);
        $lieuForm->handleRequest($request);

        if ($lieuForm->isSubmitted() && $lieuForm->isValid()) {
            $em->persist($lieu);
            $em->flush();

            $this->addFlash('success', 'Lieu ajouté avec succès !');

            return $this->redirectToRoute('sorties_ajouter');
        }

        return $this->render('lieux/ajouter_lieu.html.twig', [
            'lieuForm' => $lieuForm,
        ]);
    }
}
